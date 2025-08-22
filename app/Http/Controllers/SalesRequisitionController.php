<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistributorRequisition;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Pricing;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use Illuminate\Support\Facades\Auth;

class SalesRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales_requisition.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function getData(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'requisition_id',
            2 => 'requisition_date',
            3 => 'name',
            4 => 'address',
            5 => 'mobile',
            6 => 'quantity',
            7 => 'amount',
            8 => 'rebate',
            9 => 'total_amount',
            10 => 'requisition_note',
            11 => 'sales_approved_note',
            12 => 'sales_approved_status',
        ];

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $orderDir = $request->input('order.0.dir', 'asc');

        
        $query= Requisition::where('accounts_approved_status', 1);

        // Default filter for Pending if no status sent

        if ($request->has('status') && $request->status !== '') {
        $query->where('sales_approved_status', $request->status);
        }

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('requisition_id', 'like', "%{$search}%")
                    ->orWhere('requisition_date', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('requisition_note', 'like', "%{$search}%")
                    ->orWhere('sales_approved_note', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();
        $totalData = Requisition::where('accounts_approved_status', 1)->count();

        $requisitions = $query->orderBy($orderColumn, $orderDir)
                              ->offset($start)
                              ->limit($limit)
                              ->get();

        $data = [];
        $serial = $start + 1;

        foreach ($requisitions as $req) {
            $data[] = [
                'sl' => $serial++,
                'requisition_id' => $req->requisition_id,
                'requisition_date' => $req->requisition_date,
                'name' => $req->name,
                'address' => $req->address,
                'mobile' => $req->mobile,
                'quantity' => $req->quantity,
                'amount' => number_format($req->amount, 2),
                'rebate' => number_format($req->rebate, 2),
                'total_amount' => number_format($req->total_amount, 2),
                'requisition_note' => $req->requisition_note,
                'sales_approved_note' => $req->sales_approved_note,
                'status' => ucfirst(
                    $req->sales_approved_status == 0
                        ? 'Pending'
                        : ($req->sales_approved_status == 1 ? 'Approved' : 'Canceled')
                ),
                'posting' => ucfirst($req->posting == 0 ? 'No' : 'Yes'),
                'actions' => '
                    <a href="' . route('sales-requisition.show', $req->id) . '" class="btn btn-sm btn-info" title="Details">
                        <i class="fas fa-eye"></i>
                    </a>'
                    . ($req->sales_approved_status == 0 ? '
                    <a href="' . route('sales-requisition.edit', $req->id) . '" class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>' : ''),


            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ]);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $requisition = Requisition::findOrFail($id);
    
        // Get related details from RequisitionDetail model
        $details = RequisitionDetail::where('requisition_id', $requisition->requisition_id)->get();
        $distributor = Distributor::where('id',$requisition->distributor_id)->first();
    
        return view('sales_requisition.show', compact('requisition', 'details','distributor'));
    }



   

    public function takeAction(Request $request, $id)
    {
        $requisition = Requisition::findOrFail($id);
    
        $requisition->sales_approved_note = $request->sales_requisition_note;
        $requisition->sales_approved_by = Auth::user()->name;
        $requisition->sales_approved_date = now();

        if($requisition->operations_approved_status == 1 && $request->action == 'cancel'){
            return redirect()->back()->with('error', 'Approved requisition from Operation cannot be canceled.');
        }
    
        if ($request->action === 'approve') {
            $requisition->sales_approved_status = 1;
            $requisition->status = 1;
            $message = 'Requisition approved successfully.';
        } elseif ($request->action === 'cancel') {
            $requisition->sales_approved_status = 2;
            $requisition->status = 2;
            $message = 'Requisition canceled successfully.';
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }
    
        $requisition->save();
    
        return redirect()->route('sales-requisition.index')->with('success', $message);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $requisition = Requisition::findOrFail($id);
       $requisitionDetails = RequisitionDetail::where('requisition_id', $requisition->requisition_id)->get();
       $distributor = Distributor::where('id',$requisition->distributor_id)->firstOrFail();
       $productIds = $requisitionDetails->pluck('product_id')->unique()->toArray();
       $products = Product::whereIn('id',$productIds)->get();

     return view('sales_requisition.edit', compact('requisition', 'distributor', 'products','requisitionDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
{
    $distributor = Distributor::findOrFail($request->distributorRequisition);
    $requisition = Requisition::findOrFail($id);

    $requisition->distributor_id = $distributor->id;
    $requisition->name = $distributor->distributor_name;
    $requisition->address = $distributor->address;
    $requisition->mobile = $distributor->contact_no;
    $requisition->quantity = array_sum($request->quantities ?? []);
    $requisition->amount = array_sum($request->subtotals ?? []);
    $requisition->rebate = array_sum($request->rebates ?? []);
    $requisition->total_amount = array_sum($request->totals ?? []);
    $requisition->requisition_note = $request->requisition_note;
    $requisition->save();

    // Delete old details
    RequisitionDetail::where('requisition_id', $requisition->requisition_id)->delete();

    // Insert new details
    $count = count($request->products ?? []);
    for ($i = 0; $i < $count; $i++) {
        $product = Product::find($request->products[$i]);
        $variant = Variant::find($request->variants[$i]);

        RequisitionDetail::create([
            'requisition_id'   => $requisition->requisition_id,
            'product_details'  => $request->productDetails[$i],
            'product_id'       => $product?->id ?? null,
            'product_name'     => $product?->productName ?? 'Unknown',
            'variant'          => $variant?->variant_name ?? 'Unknown',
            'variant_id'       => $variant?->id ?? null,
            'model'            => $product?->model ?? 'Unknown',
            'quantity'         => $request->quantities[$i],
            'rate'             => $request->rates[$i],
            'amount'           => $request->subtotals[$i],
            'rebate'           => $request->rebates[$i],
            'rebat_type'       => $request->rebat_types[$i],
            'total_amount'     => $request->totals[$i],
        ]);
    }

    return redirect()->route('sales-requisition.index')->with('success', 'Requisition updated successfully.');
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
