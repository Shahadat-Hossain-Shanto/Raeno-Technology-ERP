<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\DistributorIn;
use App\Models\ImeiInfo;
use App\Models\PrimaryStockIn;
use App\Models\ProductReturn;
use App\Models\ProductReturnDetail;
use App\Models\ProductReturnQuantity;
use App\Models\Requisition;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\RequisitionDetail;
use Illuminate\Support\Facades\DB;


class ProductReturnController extends Controller
{
    public function returns()
    {
        return view('product-return.product-return');
    }

    public function sentData()
    {
        $user = auth()->user();

        if (!empty($user->distributor)) {
            $return = ProductReturn::where('distributor_id', $user->distributor)->get();
        } else {
            $return = ProductReturn::all();
        }

        return response()->json(['data' => $return]);
    }

    public function getDetails($id)
    {
        Log::info('Fetching return details for ID: ' . $id);
        $details = ProductReturnQuantity::where('return_id', $id)->get();
        return response()->json(['details' => $details]);
    }

    public function return()
    {
        $user = auth()->user();

        // $transport = Transport::all();

        if ($user->distributor) {
            $distributor = Distributor::where('id', $user->distributor)
                ->select('id', 'distributor_name')
                ->get();
        } else {
            $distributor = Distributor::select('id', 'distributor_name')->get();
        }

        return view('product-return.index', compact('distributor'));
    }

    public function store(Request $request)
    {
        // Log::info('Store Request: ', $request->all());

        $request->validate([
            'imei_count'               => 'required|integer|min:1',
            'items'                    => 'required|array|min:1',
            'items.*.imei_1'           => 'required|string',
            'items.*.serial_number'    => 'required|string',
            'items.*.product_name'     => 'required|string',
            'items.*.model'            => 'required|string',
            'items.*.variant'          => 'required|string',
            'items.*.brand'            => 'nullable|string',
            'items.*.manufacturer'     => 'nullable|string',
            'items.*.imei_2'           => 'nullable|string',
            'items.*.order_id'         => 'required|string',
            'medium'                   => 'nullable|string|max:400',
            'note'                     => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $now   = now();
            $items = collect($request->items);
            $distributor = Requisition::where ('requisition_id', $items->first()['order_id'])
                ->select('distributor_id', 'name', 'mobile')
                ->first();

            $return = ProductReturn::create([
                // 'order_id'         => null,
                'distributor_id'   => $distributor->distributor_id ?? null,
                'distributor_name' => $distributor->name ?? 'N/A',
                'mobile'           => $distributor->mobile ?? null,
                'quantity'         => $request->imei_count,
                'medium'           => $request->medium ?? null,
                'note'             => $request->note ?? null,
                'created_by'       => auth()->id(),
                // 'status'           => 0,
                'posting_status'   => 0,
            ]);

            foreach ($items as $item) {
                // if (ProductReturnDetail::where('imei_1', $item['imei_1'])->exists()) {
                //     throw new \Exception("IMEI already returned: {$item['imei_1']}");
                // }

                ProductReturnDetail::create([
                    'imei_1'         => $item['imei_1'],
                    'imei_2'         => $item['imei_2'] ?? null,
                    'serial_number'  => $item['serial_number'],
                    'product_name'   => $item['product_name'],
                    'model'          => $item['model'],
                    'variant'        => $item['variant'],
                    'brand'          => $item['brand'],
                    'manufacturer'   => $item['manufacturer'],
                    'order_id'       => $item['order_id'],
                    'distributor_id' => $distributor->distributor_id ?? null,
                    'return_id'      => $return->id,
                ]);

                $distributorIn = DistributorIn::where('imei_1', $item['imei_1'])->first();

                $hasRetail = false;

                if ($distributorIn) {
                    $hasRetail = !is_null($distributorIn->retail_id)
                                && $distributorIn->retail_id !== ''
                                && $distributorIn->retail_id != 0;

                    $updateData = ['status' => 2];
                    if ($hasRetail) {
                        $updateData['retail_status'] = 2;
                    }
                    $distributorIn->update($updateData);
                }

                $imeiInfoUpdate = [
                    'primary_state'  => 0,
                    'product_return' => $now,
                    'dealer_state'   => 3,
                ];

                if ($hasRetail) {
                    $imeiInfoUpdate['retail_state'] = 3;
                }

                ImeiInfo::where('imei_1', $item['imei_1'])->update($imeiInfoUpdate);

                PrimaryStockIn::where('imei_1', $item['imei_1'])->update(['status' => 1]);
            }

            $grouped = $items->groupBy(function ($item) {
                return $item['product_name'] . '|' . $item['model'] . '|' . $item['variant'] . '|' . $item['order_id'];
            });

            foreach ($grouped as $key => $groupItems) {
                [$productName, $model, $variant, $orderId] = explode('|', $key);
                $groupedQuantity = $groupItems->count();

                $reqDetail = RequisitionDetail::where([
                    ['requisition_id', $orderId],
                    ['product_name', $productName],
                    ['model', $model],
                    ['variant', $variant],
                ])->first();

                if (!$reqDetail) {
                    throw new \Exception("Requisition detail not found for {$productName} {$model} {$variant} - Order ID: {$orderId}");
                }

                $rate         = $reqDetail->rate;
                $totalAmount  = $groupedQuantity * $rate;
                $unitRebate   = $reqDetail->rebate / max($reqDetail->quantity, 1);
                $totalRebate  = $unitRebate * $groupedQuantity;
                $totalFinal   = $totalAmount - $totalRebate;

                ProductReturnQuantity::create([
                    'product_name' => $productName,
                    'model'        => $model,
                    'variant'      => $variant,
                    'quantity'     => $groupedQuantity,
                    'order_id'     => $orderId,
                    'return_id'    => $return->id,
                    'rate'         => $rate,
                    'amount'       => $totalAmount,
                    'rebate'       => $totalRebate,
                    'total'        => $totalFinal,
                ]);
            }

            DB::commit();

            return redirect()->route('return.details', ['id' => $return->id])
                            ->with('success', 'Return saved successfully.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return Store Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function distributorInfo($imei, $distributorId)
    {
        $stockData = DistributorIn::where('imei_1', $imei)
            ->where('distributor_id', $distributorId)
            ->where('status', '!=', 2)
            ->first();

        if (!$stockData) {
            return response()->json(['message' => 'This IMEI is not in your stock.'], 404);
        }

        return response()->json([
            'imei_1'        => $stockData->imei_1,
            'imei_2'        => $stockData->imei_2,
            'serial_number' => $stockData->serial_number,
            'product_name'  => $stockData->product_name,
            'brand'         => $stockData->brand,
            'model'         => $stockData->model,
            'manufacturer'  => $stockData->manufacturer,
            'variant'       => $stockData->variant,
            'order_id'      => $stockData->order_id,
        ]);
    }

    public function show($id)
    {
        $return = ProductReturn::where('id', $id)->firstOrFail();

        $returnQuantity = ProductReturnQuantity::where('return_id', $return->id)->get();

        $distributor = Distributor::select('address')->where('id', $return->distributor_id)->first();

        return view('product-return.view', compact('return', 'distributor', 'returnQuantity'));
    }

    public function returnedDevices()
    {
        return view('product-return.returned-devices');
    }

    public function returnedData()
    {
        $data = ProductReturnDetail::with('distributor')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }
}
