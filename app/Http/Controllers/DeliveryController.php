<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use Illuminate\Support\Facades\Log;
use App\Models\PrimaryStockIn;
use App\Models\DeliveryDetail;
use App\Models\Delivery;
use App\Models\ImeiInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Distributor;
use App\Models\DeliveryQuantity;
use App\Models\Transport;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('delivery.index');
    }

    public function data()
    {
        $requisitions = Requisition::where('status', 1)->get();

        return response()->json(['data' => $requisitions]);
    }

    public function deliveries()
    {
        return view('delivery.deliveries');
    }
    public function sentData()
    {

        $deliveries = Delivery::with('requisition')->get();
        return response()->json(['data' => $deliveries]);
    }


    public function destroy($id)
    {
        return redirect()->route('delivery.index')->with('success', 'Delivery deleted successfully.');
    }

    public function getDetails($id)
    {
        $details = RequisitionDetail::where('requisition_id', $id)->get();
        return response()->json(['details' => $details]);
    }

    public function sent($id)
    {
        $order = Requisition::where('requisition_id', $id)->firstOrFail();
        $transport = Transport::all();

        if ($order->delivery_status == 1) {
            $details = RequisitionDetail::where('requisition_id', $id)->get();

            // Group and calculate unique combinations with remaining quantity
            $orderDetails = $details->groupBy(function ($item) {
                return $item->product_name . '|' . $item->model . '|' . $item->variant;
            })->map(function ($group) use ($id) {
                $first = $group->first();

                $requisitionQty = $group->sum('quantity');

                $deliveredQty = DeliveryQuantity::where('order_id', $id)
                    ->where('product_name', $first->product_name)
                    ->where('model', $first->model)
                    ->where('variant', $first->variant)
                    ->sum('quantity');

                return (object)[
                    'product_name' => $first->product_name,
                    'model'        => $first->model,
                    'variant'      => $first->variant,
                    'quantity' => $requisitionQty - $deliveredQty,
                ];
            })->values();
        } else {
            $orderDetails = RequisitionDetail::where('requisition_id', $id)->get();
        }

        return view('delivery.delivery', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'transport' => $transport,
        ]);
    }

    public function store(Request $request)
    {
        // Log::info('Delivery Store Request: ', $request->all());

        $request->validate([
            'requisition_id'           => 'required',
            'imei_count'               => 'required|integer|min:1',
            'items'                    => 'required|array|min:1',
            'items.*.imei_1'           => 'required|string',
            'items.*.serial_number'    => 'required|string',
            'items.*.product_name'     => 'required|string',
            'items.*.model'            => 'required|string',
            'items.*.variant'          => 'required|string',
            'items.*.brand'            => 'nullable|string',
            'items.*.manufacturer'     => 'nullable|string',
            // 'items.*.price'            => 'nullable|numeric',
            'items.*.imei_2'           => 'nullable|string',
            'medium'                   => 'nullable|string|max:400',
            'note'                     => 'nullable|string|max:1000',
        ]);

        $orderData = Requisition::where('requisition_id', $request->requisition_id)->firstOrFail();
        $deliveryStatus = ((int)$request->imei_count === (int)$orderData->quantity) ? 2 : 1;
        $now = Carbon::now();

        DB::beginTransaction();

        try {
            // Create delivery and capture the instance
            $delivery = Delivery::create([
                'order_id'         => $orderData->requisition_id,
                'distributor_id'   => $orderData->distributor_id ?? null,
                'distributor_name' => $orderData->name ?? 'N/A',
                'mobile'           => $orderData->mobile ?? null,
                'quantity'         => $request->imei_count,
                'medium'           => $request->medium ?? null,
                'note'             => $request->note ?? null,
                'created_by'       => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                // Optional: Check for duplicate IMEI
                // if (DeliveryDetail::where('imei_1', $item['imei_1'])->exists()) {
                //     throw new \Exception("IMEI already delivered: {$item['imei_1']}");
                // }

                DeliveryDetail::create([
                    'imei_1'         => $item['imei_1'],
                    'imei_2'         => $item['imei_2'] ?? null,
                    'serial_number'  => $item['serial_number'],
                    'product_name'   => $item['product_name'],
                    'model'          => $item['model'],
                    'variant'        => $item['variant'],
                    'brand'          => $item['brand'],
                    'manufacturer'   => $item['manufacturer'],
                    // 'price'          => $item['price'],
                    'order_id'       => $request->requisition_id,
                    'distributor_id' => $orderData->distributor_id ?? null,
                    'delivery_id'    => $delivery->id,
                ]);

                ImeiInfo::where('imei_1', $item['imei_1'])
                    ->update([
                        'primary_state' => 1,
                        'primary_out'   => $now,
                    ]);

                PrimaryStockIn::where('imei_1', $item['imei_1'])
                    ->update(['status' => 0]);
            }

            // Group by product/model/variant and insert quantity summary
            $grouped = collect($request->items)->groupBy(function ($item) {
                return $item['product_name'] . '|' . $item['model'] . '|' . $item['variant'];
            });

            foreach ($grouped as $key => $items) {
                [$productName, $model, $variant] = explode('|', $key);
                DeliveryQuantity::create([
                    'product_name' => $productName,
                    'model'        => $model,
                    'variant'      => $variant,
                    'quantity'     => $items->count(),
                    'order_id'     => $request->requisition_id,
                    'delivery_id'  => $delivery->id,
                ]);
            }

            $orderData->update(['delivery_status' => $deliveryStatus]);

            DB::commit();

            return redirect()
                ->route('delivery.details', ['requisition_id' => $orderData->requisition_id])
                ->with('success', 'Delivery confirmed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delivery Store Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function stockInfo($imei)
    {
        $stockData = PrimaryStockIn::where('imei_1', $imei)->first();

        if (!$stockData || $stockData->status != 1) {
            return response()->json([
                'message' => 'Stock Out Or Not Exist',
                'status' => 0
            ], 404);
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
            // 'price'         => $stockData->price,
            'status'        => 1
        ]);
    }

    public function show($requisition_id)
    {
        $delivery = Delivery::where('order_id', $requisition_id)->firstOrFail();
        // $deliveryDetails = DeliveryDetail::where('order_id', $requisition_id)->get();
        $deliveryAll = DeliveryQuantity::where('order_id', $requisition_id)->get();
        $totalQuantity = $deliveryAll->sum('quantity');

        $recentDelivery = Delivery::where('order_id', $requisition_id)->latest()->first();
        $recent = DeliveryQuantity::where('delivery_id', $recentDelivery->id)->get();

        $distributor = Distributor::select('address')->where('id', $delivery->distributor_id)->first();
        $order = Requisition::select('requisition_date', 'sales_approved_date')->where('requisition_id', $requisition_id)->first();

        return view('delivery.details', compact('delivery', 'distributor', 'order' , 'deliveryAll' , 'totalQuantity' , 'recent' , 'recentDelivery'));
    }

    public function deliveryDevices()
    {
        return view('delivery.delivery-devices');
    }

    public function deliveryData()
    {
        $data = DeliveryDetail::with('distributor')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }
}
