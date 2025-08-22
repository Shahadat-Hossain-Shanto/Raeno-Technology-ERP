<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;
use App\Models\Transaction;

class DistributorTransactionLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if($user->distributor){
            $distributor = Distributor::findOrFail($user->distributor);
            $distributors = collect([$distributor]);
        } else{
            $distributors = Distributor::all();
        }
        
        return view('distributor_transaction_ledger.index',compact('distributors','user'));
    }

      public function getData(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'transaction_date',
            2 => 'transaction_id',
            3 => 'transaction_type',
            4 => 'reference_note',
            5 => 'debit',
            6 => 'credit',
        ];
    
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'transaction_date';
        $orderDir = $request->input('order.0.dir', 'asc');
    
        if (!$request->distributorHeadCode) {
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    
        $headCode = $request->distributorHeadCode;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
    
        // === Calculate Opening Balance before start date ===
        $openingBalance = 0;
        if ($startDate) {
            $openingBalance = Transaction::where('head_code', $headCode)
                ->whereDate('transaction_date', '<', $startDate)
                ->selectRaw('SUM(debit - credit) as balance')
                ->value('balance') ?? 0;
        }
    
        // === Main Query ===
        $query = Transaction::where('head_code', $headCode);
    
        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }
    
        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('transaction_type', 'like', "%{$search}%")
                  ->orWhere('reference_note', 'like', "%{$search}%")
                  ->orWhere('transaction_date', 'like', "%{$search}%");
            });
        }
    
        $totalFiltered = $query->count();
    
        $limit = intval($request->input('length', 10));
        $start = intval($request->input('start', 0));
        
        $query->orderBy($orderColumn, $orderDir);
        
        if ($limit > 0) {
            $query->offset($start)->limit($limit);
        }
        
        $transactions = $query->get();
   
        // === Assemble Response Data ===
        $data = [];
        $serial = $start + 1;
        $runningBalance = $openingBalance;
    
        // Add Opening Balance row first (ONLY on first page)
        if ($start == 0) {
            $data[] = [
                'sl' => '',
                'transaction_date' => '',
                'transaction_id' => '',
                'transaction_type' => '',
                'reference_note' => 'Opening Balance',
                'debit' => '',
                'credit' => '',
                'balance' => number_format($openingBalance, 2),
            ];
        }
    
        foreach ($transactions as $tran) {
            $runningBalance += ($tran->debit - $tran->credit);
    
            $data[] = [
                'sl' => $serial++,
                'transaction_date' => date('d-m-Y', strtotime($tran->transaction_date)),
                'transaction_id' => $tran->transaction_id,
                'transaction_type' => $tran->transaction_type,
                'reference_note' => $tran->reference_note,
                'debit' => number_format($tran->debit, 2),
                'credit' => number_format($tran->credit, 2),
                'balance' => number_format($runningBalance, 2),
            ];
        }
    
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalFiltered + ($start == 0 ? 1 : 0), // +1 for opening balance on first page
            "recordsFiltered" => $totalFiltered + ($start == 0 ? 1 : 0),
            "data" => $data,
        ]);
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
