<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Subscriber;
use App\Models\ClientImage;
use Illuminate\Http\Request;


use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function create(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('supplier/supplier-add', ['stores' => $stores]);
    }

    public function store(Request $request){

        $messages = [
            'suppliername.required'   =>   "Name is required.",
            'mobile.required'   =>   "Contact number is required.",
            'supplieraddress.required'   =>   "Address is required.",
            'mobile.unique'   =>   "Contact number already exist.",
        ];

        $validator = Validator::make($request->all(), [
            'suppliername'   => 'required',
            'mobile'   => 'required|unique:suppliers',
            'supplieraddress'   => 'required',
        ], $messages);

        if ($validator->passes()) {
            $supplier = new Supplier;

            $supplier->name             = $request->suppliername;
            $supplier->mobile           = $request->mobile;
            $supplier->type             = 'supplier';
            $supplier->email            = $request->supplieremail;
            $supplier->address          = $request->supplieraddress;
            $supplier->supplier_website = $request->supplierwebsite;
            $supplier->note             = $request->note;

            $supplier->subscriber_id    = Auth::user()->subscriber_id;

            $suppliers = Supplier::where([
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->first();

           if($suppliers){
                $suppliers = Supplier::where([
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])->latest()->first();
                $supplier->head_code = (int)$suppliers->head_code + 1;
            }else{
                $supplier->head_code = (50101 * 1000) + 1;
            }

            if ($request -> hasFile('supplierimage')) {
                $supplierImage =  new ClientImage;
                $file = $request -> file ('supplierimage');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = $file->getClientOriginalName();
                $file->move('uploads/suppliers/', $filename);
                $supplierImage->imageName  = $filename;
                $supplier->image            = $filename;

                $supplierImage->extension = $extension;
                $supplierImage->size = $size;
                $supplierImage->type = 'supplier';
                $supplierImage->save();
            }

            $supplier->save();

           $coa = new ChartOfAccount;
            $coa->head_code             = $supplier->head_code;
            $coa->head_name             = $request->suppliername;
            $coa->parent_head           = 'Account Payable';
            $coa->parent_head_level     = 50101;
            $coa->head_type             = 'L';
            $coa->is_transaction        = '0';
            $coa->is_active             = '1';
            $coa->is_general_ledger     = '0';
            $coa->subscriber_id         = Auth::user()->subscriber_id;
            $coa->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Supplier created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function listView(){
        $supplier = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('supplier/supplier-list', ['suppliers' => $supplier, 'stores'=>$stores]);

    }

    public function list(Request $request){

        // $data = Store::join('suppliers', 'stores.id', '=', 'suppliers.storeId')
        //             ->where('suppliers.subscriber_id', Auth::user()->subscriber_id)
        //             ->get(['stores.*', 'suppliers.*']);

        $data = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();

        // Log::info($data);

        if($request -> ajax()){
            return response()->json([
                'supplier'=>$data,
            ]);
        }

    }

    public function edit($id){
        $supplier = Supplier::find($id);


        if($supplier){
            return response()->json([
                'status'=>200,
                'supplier'=>$supplier,

            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'suppliername.required'   =>   "Name is required.",
            'mobile.required'   =>   "Contact number is required.",
            'supplieraddress.required'   =>   "Address is required.",
        ];

        $validator = Validator::make($request->all(), [
            'suppliername'   => 'required',
            'mobile'   => 'required',
            'supplieraddress'   => 'required',
        ], $messages);

        if ($validator->passes()) {
            $supplier = Supplier::find($id);

            $supplier->name                 = $request->suppliername;
            $supplier->mobile               = $request->mobile;
            $supplier->email                = $request->supplieremail;
            $supplier->supplier_website     = $request->supplierwebsite;
            $supplier->address              = $request->supplieraddress;
            $supplier->note                 = $request->note;
            // $supplier->storeId           = $request->storeid;

            if ($request -> hasFile('supplierimage')) {

                $path = 'uploads/suppliers/'.$supplier->image;
                if(File::exists($path)){
                    File::delete($path);
                }

                $file = $request -> file ('supplierimage');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = $file->getClientOriginalName();
                $file->move('uploads/suppliers/', $filename);
                // $supplierImage->imageName  = $filename;
                $supplier->image            = $filename;
            }

            // $supplierImage = ClientImage::where('imageName')

            $supplier->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Supplier updated successfully'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function destroy($id){
        Supplier::find($id)->delete($id);

        return redirect('supplier-list')->with('status', 'Deleted successfully!');
    }

    public function transectionView()
    {
        $supplier = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('supplier/supplier-transection', ['suppliers' => $supplier, 'stores' => $stores]);
    }

    public function transectionData(Request $request)
    {
        $supplier_data = DB::table('suppliers')
        ->select('id', 'name', 'mobile', 'head_code')
        ->where('subscriber_id', '=', Auth::user()->subscriber_id)
            ->get();

        foreach ($supplier_data as $item) {
            $supplierid = $item->id;
            $totalPurchase = DB::table('purchase_products')
            ->select('supplierId', DB::raw('SUM(grandTotal) AS total_purchase'))
            ->where('supplierId', '=', $supplierid)
        ->where('subscriber_id', '=', Auth::user()->subscriber_id)

                ->get();
            // $x = [
            //     "totalPurchase"=> $totalPurchase,
            //     "name" => $item->name,

            // ];

            $totalPurchaseData[] = $totalPurchase;
            $supplier_id[] = $item->id;
            $supplier_name[] = $item->name;
            $supplier_mobile[] = $item->mobile;
            $supplier_headcode[] = $item->head_code;
        }
        $i = 0;
        foreach ($totalPurchaseData as $item) {

            $totalPayment = DB::table('transactions')
            ->select('head_code')
            ->selectRaw('SUM(debit-credit) AS totalPayment')
            ->where('head_code', '=', $supplier_headcode[$i])
        ->where('subscriber_id', '=', Auth::user()->subscriber_id)

                ->get();
            $balance = DB::table('transactions')
            ->select('id', 'balance')
            ->where('head_code', '=', $supplier_headcode[$i])
            ->where('subscriber_id', '=', Auth::user()->subscriber_id)

                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();

            $totalPaymentData[] = $totalPayment;

            if (count($balance) == 0) {
                $balance = 'null';
            }
            $balanceData[] = $balance;
            $x = [
                "headcode" => $supplier_headcode[$i],
                "id" => $supplier_id[$i],
                "name" => $supplier_name[$i],
                "mobile" => $supplier_mobile[$i],
                "totalPurchase" => $item,
                "totalPayment" =>  $totalPaymentData[$i],
                "balance" => $balanceData[$i],
            ];


            // Log::info($balance);
            $i++;
            $data[] = $x;
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Success',
                'data' => $data,
            ]);
        }
    }

    public function supplierPurchaseDetails($id)
    {
        $supplier_data = Supplier::find($id);
        $data = DB::table('purchase_products')
        ->select('purchaseDate', 'store', 'poNumber', 'totalPrice', 'discount', 'other_cost', 'grandTotal')
        ->where('supplierId', '=', $id)
            ->get();

        return view('supplier/supplier-purchase-details', compact('data', 'supplier_data'));
    }

    public function supplierPaymentDetails($head_code)
    {
        $supplier_data = Supplier::where('head_code', $head_code)
        ->where('subscriber_id',Auth::user()->subscriber_id)
        ->first();

        $data = DB::table('transactions')
        ->select('transaction_date', 'transaction_id', 'debit', 'credit', 'balance', 'reference_note')
        ->where('head_code', '=', $head_code)
        ->where('subscriber_id',Auth::user()->subscriber_id)
            ->get();
        return view('supplier/supplier-transaction-details', compact('data', 'supplier_data'));
    }
}
