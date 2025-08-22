<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\ImeiIn;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ImeiInfo;
use App\Models\Retail;


class ImeiInController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $products = Product::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $imei = ImeiIn::all();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();

        ImeiIn::query()->delete();

        return view('imei/imei-in', ['stores' => $stores, 'imei'=>$imei, 'products' => $products, 'variants' => $variants]);
    }

    public function afterSub(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $products = Product::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $imei = ImeiIn::all();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();

        return view('imei/imei-in', ['stores' => $stores, 'imei'=>$imei, 'products' => $products, 'variants' => $variants]);
    }
    public function data()
    {
        $imei = ImeiIn::all();
        return response()->json(['data' => $imei]);
    }

    public function info()
    {
        return view('imei.imei-info');
    }

    public function infoData()
    {
        $imei = ImeiInfo::with('entryUser', 'primaryUser', 'dealer','retail')->get();
        return response()->json(['data' => $imei]);
    }
    
    public function filterInfo(Request $request)
    {
        $imeiInfo = null;
        $dealer = null;
        $retail = null;

        if ($request->has('imei') && !empty($request->imei)) {
            $search = $request->imei;

            $imeiInfo = ImeiInfo::where('imei_1', $search)
                        ->orWhere('imei_2', $search)
                        ->orWhere('serial_number', $search)
                        ->first();

            if ($imeiInfo && $imeiInfo->dealer) {
                $dealer = Distributor::where('id', $imeiInfo->dealer)
                            ->select('distributor_name')
                            ->first();
            }

            if ($imeiInfo && $imeiInfo->retail) {
                $retail = Retail::where('id', $imeiInfo->retail)
                            ->select('retail_name')
                            ->first();
            }
        }

        return view('imei.filter-imei', compact('imeiInfo', 'dealer', 'retail'));
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        $data = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX);
        $rows = $data[0];

        if (count($rows) <= 1) {
            return back()->with('error', 'Excel file is empty or header row only!');
        }

        $errorMessages = [];
        $validRows = [];
        $isError = false;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            $imei_1 = isset($row[0]) ? trim(number_format($row[0], 0, '', '')) : null;
            $imei_2 = isset($row[1]) ? trim(number_format($row[1], 0, '', '')) : null;
            $serial_number = isset($row[2]) ? trim(number_format($row[2], 0, '', '')) : null;
            $product_name = isset($row[3]) ? trim($row[3]) : null;
            $model = isset($row[4]) ? trim($row[4]) : null;
            $variant = isset($row[5]) ? trim($row[5]) : null;

            // Fetch the product by name and model both
            $product = DB::table('products')
                ->where('productName', $product_name)
                ->where('model', $model)
                ->first();

            if (!$product) {
                $isError = true;
                $errorMessages[] = "Row " . ($index + 1) . " error: Product '$product_name' with Model '$model' does not exist.";
                continue;
            }

            $variantExists = DB::table('variants')
                ->where('product_id', $product->id)
                ->where('variant_name', $variant)
                ->exists();

            if (!$variantExists) {
                $isError = true;
                $errorMessages[] = "Row " . ($index + 1) . " error: Variant '$variant' does not exist for product '$product_name'.";
                continue;
            }

            $validator = Validator::make([
                'imei_1'        => $imei_1,
                'imei_2'        => $imei_2,
                'serial_number' => $serial_number,
                'product_name'  => $product_name,
                'model'         => $model,
                'variant'       => $variant,
            ], [
                'imei_1'        => ['required', 'digits:15', 'unique:imei_in,imei_1'],
                'imei_2'        => ['required', 'digits:15', 'unique:imei_in,imei_2'],
                'serial_number' => ['required', 'min:5', 'max:50', 'unique:imei_in,serial_number'],
                'product_name'  => ['required', 'string', 'min:1', 'max:100'],
                'model'         => ['required', 'string', 'min:1', 'max:100'],
                'variant'       => ['required', 'string', 'min:1', 'max:100'],
            ]);

            if ($validator->fails()) {
                $isError = true;
                $errorMessages[] = "Row " . ($index + 1) . " validation error: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // All checks passed
            $validRows[] = [
                'imei_1'        => $imei_1,
                'imei_2'        => $imei_2,
                'serial_number' => $serial_number,
                'product_name'  => $product_name,
                'model'         => $model,
                'variant'       => $variant,
                'created_by'    => Auth::id() ?? 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        if ($isError) {
            return back()->with('error', implode('<br>', $errorMessages));
        }

        DB::table('imei_in')->insert($validRows);

        return redirect()->route('imei.after.view')->with('success', 'Excel data imported successfully!');

    }

    public function store()
    {
        $imeiInRecords = ImeiIn::all();

        if ($imeiInRecords->isEmpty()) {
            return redirect()->back()->with('error', 'No data found in IMEI In table to process.');
        }

        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($imeiInRecords as $record) {

                if (!$record->imei_1 || !$record->imei_2 || !$record->serial_number || !$record->product_name || !$record->model || !$record->variant) {
                    $errors[] = "Record with ID {$record->id} is missing required fields.";
                    continue;
                }

                $exists = ImeiInfo::where('imei_1', $record->imei_1)
                            ->orWhere('imei_2', $record->imei_2)
                            ->orWhere('serial_number', $record->serial_number)
                            ->exists();

                if ($exists) {
                    $errors[] = "Record with ID {$record->id} has duplicate IMEI or Serial Number in IMEI Info table.";
                    continue;
                }

                ImeiInfo::create([
                    'imei_1'        => $record->imei_1,
                    'imei_2'        => $record->imei_2,
                    'serial_number' => $record->serial_number,
                    'product_name'  => $record->product_name,
                    'model'         => $record->model,
                    'variant'       => $record->variant,
                    // 'primary_in'    => now(),
                    'entry_user'    => Auth::id() ?? 0,
                    // 'primary_state' => 1,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // Delete from imei_in after successful insert
                $record->delete();
            }

            DB::commit();

            if (!empty($errors)) {
                return redirect()->back()->with('error', implode('<br>', $errors));
            }

            return redirect()->route('imei.in.view')->with('success', 'IMEI Uploaded Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(ImeiIn $imei)
    {

        $imei->delete();

        return redirect()->route('imei.after.view')->with('success', 'IMEI deleted successfully.');
    }

    public function deleteAll()
    {
        ImeiIn::truncate();
        return redirect()->route('imei.in.view')->with('success', 'All IMEI records deleted successfully.');
    }

}
