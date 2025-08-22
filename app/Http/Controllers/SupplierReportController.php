<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SupplierReportController extends Controller
{
    // public function purchaseReportView()
    // {
    //     $suppliers = Supplier::all();
    //     return view('supplier/supplier-purchase-report', compact('suppliers'));
    // }
    // public function purchaseReportData(Request $request, $supplier_id, $startdate, $enddate)
    // {
    //     $data = DB::table('purchase_products')
    //         ->select('store', 'poNumber', 'totalPrice', 'discount', 'other_cost', 'grandTotal')
    //         ->where('supplierId', '=', $supplier_id)
    //         ->whereBetween('purchaseDate', [$startdate, $enddate])
    //         ->get();

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'status' => 200,
    //             'data' => $data,
    //             'message' => 'Success!'
    //         ]);
    //     }
    // }

    public function transectionView()
    {
        $suppliers = Supplier::all();
        return view('supplier/supplier-transection-report', compact('suppliers'));
    }

    public function transectionData(Request $request, $head_code, $startdate, $enddate)
    {
        $data =   $data = DB::table('transactions')
            ->select('transaction_date', 'transaction_id', 'debit', 'credit', 'balance', 'reference_note')
            ->where('head_code', '=', $head_code)
            ->whereBetween('transaction_date', [$startdate, $enddate])
            ->get();

            log::info($data);
        if ($request->ajax()) {
            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Success!'
            ]);
        }
    }
}
