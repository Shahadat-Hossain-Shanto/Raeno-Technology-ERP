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
use Seld\PharUtils\Timestamps;
use Illuminate\Support\Facades\Log;

class DistributorRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('distributor_requisition.index');
    }

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
        11 => 'status',
        12 => 'posting'
    ];

    $limit = $request->input('length', 10);
    $start = $request->input('start', 0);
    $orderColumnIndex = $request->input('order.0.column', 0);
    $orderColumn = $columns[$orderColumnIndex] ?? 'id';
    $orderDir = $request->input('order.0.dir', 'asc');

    $query = Requisition::query();

     if ($request->has('status') && $request->status !== '') {
      if ($request->status == 2) {
          // Only filter by sales_approved_status for canceled
          $query->where('sales_approved_status', 2);
      } else {
          // For pending/approved, filter by accounts_approved_status
          $query->where('status', $request->status);
      }
  } else {
      $query->where('status', 0); // Default to Pending
  }


    //  Search filter
    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('requisition_id', 'like', "%{$search}%")
              ->orWhere('requisition_date', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhere('requisition_note', 'like', "%{$search}%");
        });
    }

    $totalFiltered = $query->count();
    $totalData = Requisition::count();

    $requisitions = $query->orderBy($orderColumn, $orderDir)
                          ->offset($start)
                          ->limit($limit)
                          ->get();

    $data = [];
    $serial = $start + 1;

    foreach ($requisitions as $req) {
           $actions = '
            <a href="' . route('distributor_requisition.show', $req->id) . '" class="btn btn-sm btn-info" title="Details">
                <i class="fas fa-eye"></i>
            </a>
        ';

        // Allow delete button only if not canceled
        if ($req->sales_approved_status != 2) {
            $actions .= '
                <form action="' . route('distributor_requisition.delete', $req->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button onclick="return confirm(\'Delete this requisition?\')" class="btn btn-sm btn-light" title="Delete">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </button>
                </form>
            ';
        }
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
            'status' => ucfirst(
                $req->status == 0
                    ? 'Pending'
                    : ($req->status == 1 ? 'Approved' : 'Canceled')
            ),
            'posting' => ucfirst($req->posting == 0 ? 'No' : 'Yes'),
            'actions' => $actions
        ];
    }

    return response()->json([
        "draw" => intval($request->input('draw')),
        "recordsTotal" => $totalData,
        "recordsFiltered" => $totalFiltered,
        "data" => $data
    ]);
}






public function show($id)
{
    $requisition = Requisition::findOrFail($id);

    // Get related details from RequisitionDetail model
    $details = RequisitionDetail::where('requisition_id', $requisition->requisition_id)->get();
    $distributor = Distributor::where('id',$requisition->distributor_id)->first();


    return view('distributor_requisition.show', compact('requisition', 'details','distributor'));
}

    public function takeAction(Request $request, $id)
    {
        $requisition = Requisition::findOrFail($id);
        
        if ($requisition->accounts_approved_status == 1 && $request->action == 'cancel') {
            return redirect()->back()->with('error', 'Approved requisition from Accounts cannot be canceled.');
        }
    
        if ($request->action == 'approve') {
            $requisition->status = 1;
        } elseif ($request->action == 'cancel' ) {
            $requisition->status = 2;
        }
    
        $requisition->save();
    
        return redirect()->route('distributor_requisition.index')->with('success', 'Requisition updated successfully.');
    }
    
    



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        Log::info('aa');
        $products = Product::all();
        $distributors = Distributor::all();
        $variants = Variant::all();

        return view('distributor_requisition.create',compact('distributors','products','variants'   ));
    }
    

     public function getVariants($product_id)
    {
        $variants = Variant::where('product_id', $product_id)->get();

        return response()->json($variants);
    }

    public function getPricing($variant_id)
    {
        $pricing = Pricing::where('variant_id', $variant_id)->first();
    
        if ($pricing) {
            return response()->json([
                'dealer_cost' => $pricing->dealer_cost
            ]);
        } else {
            return response()->json(['dealer_cost' => 0]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $distributor = Distributor::findOrFail($request->distributorRequisition);      
    $requisitionId = now()->format('ymd') . '-' . rand(100, 999); 
    // Save main requisition
    $requisition = Requisition::create([
        'requisition_id' => $requisitionId,
        'requisition_date' => now()->toDateString(),
        'distributor_id' => $distributor->id,
        'name' => $distributor->distributor_name,
        'address' => $distributor->address,
        'mobile' => $distributor->contact_no,
        'quantity' => array_sum($request->quantities ?? []),
        'amount' => array_sum($request->subtotals ?? []),
        'rebate' => array_sum($request->rebates ?? []),
        'total_amount' => array_sum($request->totals ?? []),
        'requisition_note' => $request->requisition_note,
        'created_by' => Auth::id(),
    ]);
   

    // Save all product details
    $count = count($request->products ?? []);
    for ($i = 0; $i < $count; $i++) {
        RequisitionDetail::create([
            'requisition_id' => $requisition->requisition_id,
            'product_details' => $request->productDetails[$i],
            'product_id' => Product::find($request->products[$i])->id ?? null,
            'product_name' => Product::find($request->products[$i])->productName ?? 'Unknown',
            'variant_id' =>  Variant::find($request->variants[$i])->id ?? null,
            'variant' => Variant::find($request->variants[$i])->variant_name,
            'model' =>  Product::find($request->products[$i])->model,
            'quantity' => $request->quantities[$i],
            'rate' => $request->rates[$i],
            'amount' => $request->subtotals[$i],
            'rebate' => $request->rebates[$i],
            'rebat_type' =>  $request->rebat_types[$i],
            'total_amount' => $request->totals[$i],
        ]);
    }

    return redirect()->route('distributor_requisition.index')->with('success', 'Requisition saved successfully');
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

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
      $distributors = Distributor::all();
       $products = Product::all();
       $variants = Variant::all();

    
        return view('distributor_requisition.edit', compact('requisition', 'distributors', 'products','requisitionDetails'));
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
            'total_amount'     => $request->totals[$i],
        ]);
    }

    return redirect()->route('distributor_requisition.index')->with('success', 'Requisition updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $requisition = Requisition::findOrFail($id);
        $requisitionDetails = RequisitionDetail::where('requisition_id',$requisition->requisition_id);
        $requisition->delete();
        $requisitionDetails->delete();

    return redirect()->route('distributor_requisition.index')->with('success', 'Requisition deleted successfully.');
    }
}
