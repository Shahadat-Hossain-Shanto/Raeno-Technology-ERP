<?php

namespace App\Http\Controllers;

use App\Models\ImeiInfo;
use Illuminate\Http\Request;
use App\Models\PrimaryStockIn;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Store;
use App\Models\Pricing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PrimaryInHistory;

class PrimaryStockInController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $products = Product::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();
        // PrimaryPreStock::query()->delete();
        return view('imei/primary-in', ['stores' => $stores, 'products' => $products, 'variants' => $variants]);
    }

    public function store(Request $request)
    {
        $items = $request->input('items');

        if (empty($items)) {
            return redirect()->back()->with('error', 'No IMEI data submitted.');
        }

        $errors = [];
        $successItems = [];
        DB::beginTransaction();

        try {
            foreach ($items as $index => $item) {
                if (empty($item['imei_1']) || empty($item['product_name']) || empty($item['model']) || empty($item['variant'])) {
                    $errors[] = "Row #".($index + 1).": Missing required fields.";
                    continue;
                }

                $exists = PrimaryStockIn::where('imei_1', $item['imei_1'])
                            ->orWhere('imei_2', $item['imei_2'] ?? null)
                            ->orWhere('serial_number', $item['serial_number'] ?? null)
                            ->exists();

                if ($exists) {
                    $errors[] = "Row #".($index + 1).": Duplicate IMEI or Serial.";
                    continue;
                }

                // Insert into Primary Stock In
                PrimaryStockIn::create([
                    'imei_1'        => $item['imei_1'],
                    'imei_2'        => $item['imei_2'] ?? null,
                    'serial_number' => $item['serial_number'] ?? null,
                    'product_name'  => $item['product_name'],
                    'brand'         => $item['brand'] ?? null,
                    'model'         => $item['model'],
                    'manufacturer'  => $item['manufacturer'] ?? null,
                    'variant'       => $item['variant'],
                    // 'price'         => $item['price'] ?? 0.00,
                    'created_by'    => Auth::id() ?? 0,
                ]);

                // Optional: Update ImeiInfo
                ImeiInfo::where('imei_1', $item['imei_1'])->update([
                    'brand'         => $item['brand'] ?? null,
                    'manufacturer'  => $item['manufacturer'] ?? null,
                    'primary_in'    => now(),
                    'primary_user'  => Auth::id() ?? 0,
                    'primary_state' => 0,
                ]);

                // Save only successful item for grouping later
                $successItems[] = $item;
            }

            // Group successful items and store in product_in_histories
            $grouped = collect($successItems)->groupBy(function ($item) {
                return $item['product_name'] . '|' . $item['model'] . '|' . $item['variant'];
            });

            foreach ($grouped as $key => $group) {
                [$productName, $model, $variant] = explode('|', $key);

                PrimaryInHistory::create([
                    'product_name'   => $productName,
                    'model'          => $model,
                    'variant_name'   => $variant,
                    'quantity'       => $group->count(),
                    // 'unit_price'     => $group->first()['price'] ?? 0.00,
                    'brand'          => $group->first()['brand'] ?? 'N/A',
                    'manufacturer'   => $group->first()['manufacturer'] ?? 'N/A',
                    'created_by'     => Auth::id() ?? 0,
                    'updated_by'     => null,
                    'subscriber_id'  => Auth::user()->subscriber_id ?? 0,
                ]);
            }

            DB::commit();

            if (!empty($errors)) {
                return redirect()->back()->with('error', implode('<br>', $errors));
            }

            return redirect()->route('primary.in.index')->with('success', 'Stock In Successful');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $stock = PrimaryStockIn::findOrFail($id);
        $stock->delete();

        return redirect()->route('primary_in.index')->with('success', 'Stock entry deleted successfully.');
    }

    public function stock()
    {
        return view('imei.primary-stock');
    }

    public function getStockData(Request $request)
    {
        $query = PrimaryStockIn::with('primaryUser');

        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('imei_1', 'like', "%{$search}%")
                ->orWhere('imei_2', 'like', "%{$search}%")
                ->orWhere('serial_number', 'like', "%{$search}%")
                ->orWhere('product_name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('manufacturer', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%")
                ->orWhere('variant', 'like', "%{$search}%");
            });
        }

        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $direction = $request->order[0]['dir'];

            $columns = [
                1 => 'imei_1',
                2 => 'imei_2',
                3 => 'serial_number',
                4 => 'product_name',
                5 => 'brand',
                6 => 'manufacturer',
                7 => 'model',
                8 => 'variant',
                9 => 'status',
                11 => 'created_at',
            ];

            $orderColumn = $columns[$columnIndex] ?? 'created_at';

            $query->orderBy($orderColumn, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $totalRecords = $query->count();

        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $formattedData = $data->map(function ($item) {
            return [
                'imei_1'       => $item->imei_1,
                'imei_2'       => $item->imei_2,
                'serial_number'=> $item->serial_number,
                'product_name' => $item->product_name,
                'brand'        => $item->brand,
                'manufacturer' => $item->manufacturer,
                'model'        => $item->model,
                'variant'      => $item->variant,
                'status'       => $item->status,
                'primary_user' => $item->primaryUser ? ['name' => $item->primaryUser->name] : null,
                'created_at'   => $item->created_at ? $item->created_at->toDateTimeString() : '',
            ];
        });

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data'            => $formattedData,
        ]);
    }

    public function getImeiInfo($imei)
    {
        $imeiData = ImeiInfo::where('imei_1', $imei)->first();

        if (!$imeiData) {
            return response()->json(['message' => 'IMEI not found in master data.'], 422);
        }

        $pricing = Pricing::where('product_name', $imeiData->product_name)
            ->where('variant_name', $imeiData->variant)
            ->where('model', $imeiData->model)
            ->latest()
            ->first();

        return response()->json([
            'imei_1'        => $imeiData->imei_1,
            'imei_2'        => $imeiData->imei_2,
            'serial_number' => $imeiData->serial_number,
            'product_name'  => $imeiData->product_name,
            'model'         => $imeiData->model,
            'variant'       => $imeiData->variant,
            'brand'         => $pricing->brand ?? null,
            'manufacturer'  => $pricing->manufacturer ?? null,
            // 'price'         => $pricing->landed_cost ?? 0.00,
        ]);
    }

}
