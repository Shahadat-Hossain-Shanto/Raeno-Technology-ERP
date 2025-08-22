<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;
use App\Models\Transaction;

class DistributorSalesSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distributors = Distributor::all();
        return view('distributor-sales-summery-report.index',compact('distributors'));
    }

public function getData(Request $request)
{
    $columns = [
        0 => 'id',
        1 => 'distributor_name',
    ];

    $limit = intval($request->input('length', 10));
    if ($limit <= 0) {
        $limit = 10;
    }
    $start = intval($request->input('start', 0));
    $start = intval($request->input('start', 0));
    if ($start < 0) {
        $start = 0;
    }
    $orderColumnIndex = $request->input('order.0.column', 0);
    $orderColumn = $columns[$orderColumnIndex] ?? 'id';
    $orderDir = $request->input('order.0.dir', 'asc');

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $searchValue = $request->input('search.value');
    $distributorQuery = Distributor::query();

    if (!empty($searchValue)) {
        $distributorQuery->where('distributor_name', 'like', "%{$searchValue}%");
    }

    // Get total filtered count before pagination
    $recordsFiltered = (clone $distributorQuery)->count();

    // Apply pagination to distributors query
    $distributors = (clone $distributorQuery)
        ->orderBy($orderColumn, $orderDir) 
        ->offset($start)
        ->limit($limit)
        ->get();

    $data = [];
    $serial = $start + 1; 

    foreach ($distributors as $distributor) {
        $headCode = $distributor->head_code;

        // Opening Balance
        $openingBalance = Transaction::where('head_code', $headCode)
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('transaction_date', '<', $startDate);
            })
            ->selectRaw('SUM(debit - credit) as balance')
            ->value('balance') ?? 0;

        // Transactions within selected range
        $transactions = Transaction::where('head_code', $headCode)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($q) use ($startDate) {
                $q->whereDate('transaction_date', '>=', $startDate);
            })
            ->when(!$startDate && $endDate, function ($q) use ($endDate) {
                $q->whereDate('transaction_date', '<=', $endDate);
            })
            ->get();

        // Running Balance
        $runningBalance = $openingBalance;
        foreach ($transactions as $tran) {
            $runningBalance += ($tran->debit - $tran->credit);
        }

        $totalSales = $transactions->where('voucher', 'Product Sell')->sum('debit');
        $salesReturn = $transactions->where('voucher', 'Product Return')->sum('credit');
        $distributorCommission = $transactions->where('voucher', 'Operating Expense')->sum('credit');
        $distributorDeposit = $transactions->where('voucher', 'Distributor Deposit')->sum('credit');
        $ledgerAdjustment = $transactions->where('voucher','Adjustment Voucher')->sum('credit');

        $data[] = [
            'sl' => $serial++,
            'distributor_name' => $distributor->distributor_name,
            'distributor_status' => $distributor->distributor_status == 0 ? 'Active' : 'Inactive',
            'opening_balance' => number_format($openingBalance, 2),
            'total_sales' => number_format($totalSales, 2),
            'sales_return' => number_format($salesReturn, 2),
            'discount' => number_format($distributorCommission, 2),
            'ledger_adjustment' => number_format($ledgerAdjustment,2),
            'total_collection' => number_format($distributorDeposit, 2),
            'closing_balance' => number_format($runningBalance, 2),
        ];
    }

    $recordsTotal = Distributor::count();

    return response()->json([
        "draw" => intval($request->input('draw')),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
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
