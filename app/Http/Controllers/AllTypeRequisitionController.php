<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\Distributor;
use Illuminate\Http\Request;

class AllTypeRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requisitions = Requisition::all();
        $distributorIds = $requisitions->pluck('distributor_id')->unique();

        $user = auth()->user();
        if($user->distributor){
            $requisitions = $requisitions->where('distributor_id',$user->distributor);
            $distributors = collect();
        } else{
            $distributors = Distributor::whereIn('id', $distributorIds)->get();
        }

        return view('all_type_requisitions.index',compact('requisitions','distributors'));
    }


    public function getData(Request $request)
    {
        $user= auth()->user();
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
        ];
    
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $orderDir = $request->input('order.0.dir', 'asc');
    
        $query = Requisition::query();

        if($user->distributor){
        $query->where('distributor_id',$user->distributor);
        }
    
        // Requisition ID filter
        if ($request->requisition_id) {
            $query->where('requisition_id', $request->requisition_id);
        }

        if($request->distributor_id){
            $query->where('distributor_id',$request->distributor_id);
        }
    
        // Date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('requisition_date', [$request->start_date, $request->end_date]);
        } elseif ($request->start_date) {
            $query->whereDate('requisition_date', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->whereDate('requisition_date', '<=', $request->end_date);
        }
    
        // Global search
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
            // Status Logic
            if ($req->status == 2) {
                $statusText = '<span class="badge bg-danger">Canceled</span>';
            } elseif (
                $req->accounts_approved_status == 1 &&
                $req->sales_approved_status == 1
            ) {
                $statusText = '<span class="badge bg-success">Fully Approved</span>';
            } elseif (
                $req->accounts_approved_status == 0 &&
                $req->sales_approved_status == 0
            ) {
                $statusText = '<span class="badge bg-warning">Pending</span>';
            } else {
                $statuses = [];
                if ($req->accounts_approved_status == 1) $statuses[] = 'Accounts';
                if ($req->sales_approved_status == 1) $statuses[] = 'Sales';
                $statusText = '<span class="badge bg-info">Partially Approved: ' . implode(', ', $statuses) . '</span>';
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
                'status' => $statusText,
                'posting' => ucfirst($req->posting == 0 ? 'No' : 'Yes'),
                'actions' => '
                    
                    <a href="' . route('all-type-requisitions.print', $req->id) . '" class="btn btn-sm btn-secondary ml-1" target="_blank" title="Print">
                        <i class="fas fa-print"></i>
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
       
    
    public function print($id)
    {
        $requisition = Requisition::findOrFail($id);
        $details = RequisitionDetail::where('requisition_id', $requisition->requisition_id)->get();
        $distributor = Distributor::find($requisition->distributor_id);
    
        return view('all_type_requisitions.print', compact('requisition', 'details', 'distributor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
