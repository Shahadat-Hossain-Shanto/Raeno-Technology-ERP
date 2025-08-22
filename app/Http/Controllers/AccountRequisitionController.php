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


class AccountRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account_requisition.index');
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
        11 => 'accounts_approved_note',
        12 => 'accounts_approved_status',
    ];

    $limit = $request->input('length', 10);
    $start = $request->input('start', 0);
    $orderColumnIndex = $request->input('order.0.column', 0);
    $orderColumn = $columns[$orderColumnIndex] ?? 'id';
    $orderDir = $request->input('order.0.dir', 'asc');

    
    $query = Requisition::query();

    // Apply account approval status if passed from filter
    if ($request->has('status') && $request->status !== '') {
        $query->where('accounts_approved_status', $request->status);
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
                ->orWhere('accounts_approved_note', 'like', "%{$search}%");
        });
    }

    // Count for pagination
    $totalFiltered = $query->count();

    $requisitions = $query->orderBy($orderColumn, $orderDir)
        ->offset($start)
        ->limit($limit)
        ->get();

    // Total regardless of filter (only sales approved)
    $totalData = Requisition::count();

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
            'accounts_approved_note' => $req->accounts_approved_note,
            'status' => ucfirst(
                $req->accounts_approved_status == 0
                    ? 'Pending'
                    : ($req->accounts_approved_status == 1 ? 'Approved' : 'Canceled')
            ),
            'posting' => ucfirst($req->posting == 0 ? 'No' : 'yes'),
            'actions' => '
                <a href="' . route('account-requisitions.show', $req->id) . '" class="btn btn-sm btn-info" title="Details">
                    <i class="fas fa-eye"></i>
                </a>',
        ];
    }

    return response()->json([
        "draw" => intval($request->input('draw')),
        "recordsTotal" => $totalData,
        "recordsFiltered" => $totalFiltered,
        "data" => $data,
    ]);
}




    public function show($id)
    {
        $requisition = Requisition::findOrFail($id);
    
        // Get related details from RequisitionDetail model
        $details = RequisitionDetail::where('requisition_id', $requisition->requisition_id)->get();
        $distributor = Distributor::where('id',$requisition->distributor_id)->first();
    
        return view('account_requisition.show', compact('requisition', 'details','distributor'));
    }

    public function approve(Request $request, $id)
{
    $request->validate([
        'account_requisition_note' => 'nullable|string|max:1000',
    ]);

    $requisition = Requisition::findOrFail($id);

    $creditLimit = 5000;
    $balance = 1000;
    $total = $creditLimit + $balance;

    if ($total > 7000) {
        return redirect()->back()->withInput()->with('error', 'Approval failed: Credit limit and balance exceed ৳7000.');
    }

    $requisition->accounts_approved_note = $request->account_requisition_note;
    $requisition->accounts_approved_status = 1; // 1 = Approved
    $requisition->accounts_approved_by = Auth::user()->name;
    $requisition->accounts_approved_date = now();
    $requisition->save();

    return redirect()->route('account-requisition.index')->with('success', 'Requisition approved successfully.');
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
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
             
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
        //
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
