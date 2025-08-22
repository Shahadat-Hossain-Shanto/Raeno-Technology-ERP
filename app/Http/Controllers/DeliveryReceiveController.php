<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\DeliveryQuantity;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use Illuminate\Support\Facades\Log;
use App\Models\DistributorIn;
use Illuminate\Support\Facades\Auth;
use App\Models\ImeiInfo;
use Illuminate\Support\Facades\DB;
use App\Models\Distributor;

class DeliveryReceiveController extends Controller
{
    public function index()
    {
        return view('delivery.order-receive');
    }
    public function receivable()
    {
        return view('delivery.order-receivable');
    }

    public function data(Request $request)
    {
        $user = auth()->user();

        $query = Delivery::with('requisition')->where('status', '!=', 0);

        if (!empty($user->distributor)) {
            $query->where('distributor_id', $user->distributor);
        }

        // Apply filters
        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $deliveries = $query->get();

        return response()->json(['data' => $deliveries]);
    }

    public function receivableData()
    {
        $user = auth()->user();

        $query = Delivery::with('requisition')->where('status', 0);

        if (!empty($user->distributor)) {
            $query->where('distributor_id', $user->distributor);
        }

        $deliveries = $query->get();

        return response()->json(['data' => $deliveries]);
    }

    public function receive($id)
    {
        $order = Delivery::findOrFail($id);

        $orderDate = Requisition::where('requisition_id', $order->order_id)
            ->value('requisition_date');

        $orderDetails = collect();

        if ($order->status == 0) {
            $details = DeliveryQuantity::where('delivery_id', $id)->get();

            $orderDetails = $details->groupBy(function ($item) {
                return $item->product_name . '|' . $item->model . '|' . $item->variant;
            })->map(function ($group) {
                $first = $group->first();

                return (object)[
                    'product_name' => $first->product_name,
                    'model'        => $first->model,
                    'variant'      => $first->variant,
                    'quantity'     => $group->sum('quantity'),
                ];
            })->values();
        }

        return view('delivery.order-data', [
            'order'        => $order,
            'orderDetails' => $orderDetails,
            'orderDate'    => $orderDate ?? 'N/A',
        ]);
    }

    public function deliveryInfo($imei, $orderId)
    {
        $deliveryData = DeliveryDetail::where('imei_1', $imei)
            ->where('delivery_id', $orderId)
            ->first();

        if (!$deliveryData || $deliveryData->receive_status != 0) {
            return response()->json([
                'message' => 'Not This Delivery Product OR Already Received',
                'status' => 0
            ], 404);
        }

        return response()->json([
            'imei_1'        => $deliveryData->imei_1,
            'imei_2'        => $deliveryData->imei_2,
            'serial_number' => $deliveryData->serial_number,
            'product_name'  => $deliveryData->product_name,
            'brand'         => $deliveryData->brand,
            'model'         => $deliveryData->model,
            'manufacturer'  => $deliveryData->manufacturer,
            'variant'       => $deliveryData->variant,
            // 'price'         => $deliveryData->price,
            'status'        => 1
        ]);
    }

    public function store(Request $request)
    {
        // Log::info('DistributorIn Request:', $request->all());

        $delivery = Delivery::where('order_id', $request->order_id)
            ->where('id', $request->delivery_id)
            ->first();

        if (!$delivery) {
            return redirect()->back()->with('error', 'Delivery record not found.');
        }
        $requisition = Requisition::where('requisition_id', $delivery->order_id)->first();

        if ((int) $request->imei_count !== (int) $delivery->quantity) {
            return redirect()->back()->with('error', 'All products not added. Please match IMEI count with quantity.');
        }

        DB::beginTransaction();

        try {
            $distributorId = $delivery->distributor_id;
            $distributorName = $delivery->distributor_name;

            foreach ($request->items as $item) {
                DistributorIn::create([
                    'imei_1'           => $item['imei_1'],
                    'imei_2'           => $item['imei_2'] ?? null,
                    'serial_number'    => $item['serial_number'] ?? null,
                    'product_name'     => $item['product_name'],
                    'brand'            => $item['brand'] ?? null,
                    'model'            => $item['model'],
                    'manufacturer'     => $item['manufacturer'] ?? null,
                    'variant'          => $item['variant'],
                    // 'price'            => $item['price'],
                    'distributor_id'   => $distributorId,
                    'distributor_name' => $distributorName,
                    'received_by'      => Auth::id(),
                    'status'           => 1,
                    'order_id'         => $request->order_id,
                    'delivery_id'      => $request->delivery_id,
                ]);

                DeliveryDetail::where('imei_1', $item['imei_1'])->update([
                    'receive_status' => 1,
                ]);

                ImeiInfo::where('imei_1', $item['imei_1'])->update([
                    'primary_state' => 2,
                    'dealer'        => $distributorId,
                    'dealer_in'     => now(),
                    'dealer_out'    => null,
                    'dealer_state'  => 0,
                ]);
            }

            if ($requisition && $requisition->quantity == $request->imei_count) {
                $delivery->status = 2;
            } else {
                $delivery->status = 1;
            }

            $delivery->receive_date = now();
            $delivery->save();

            DB::commit();
            return redirect()->route('distributor.in')->with('success', 'Distributor stock-in records saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DistributorIn Store Error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while saving data.');
        }
    }

    public function distributorData()
    {
        return view('delivery.received-data');
    }

    public function distributorInData()
    {
        $data = DistributorIn::with('receivedByUser','retail')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function show($id)
    {
        // Log::info('Delivery Show ID: ' . $id);
        $delivery = Delivery::where('id', $id)->firstOrFail();
        // $deliveryAll = DeliveryQuantity::where('order_id', $delivery->order_id)->get();
        // $totalQuantity = $deliveryAll->sum('quantity');

        $recentDelivery = Delivery::findOrFail($id);
        $recent = DeliveryQuantity::where('delivery_id', $recentDelivery->id)->get();

        $distributor = Distributor::select('address')->where('id', $delivery->distributor_id)->first();
        $order = Requisition::select('requisition_date', 'sales_approved_date')->where('requisition_id', $delivery->order_id)->first();

        return view('delivery.received-details', compact('delivery', 'distributor', 'order', 'recent', 'recentDelivery'));
    }

}
