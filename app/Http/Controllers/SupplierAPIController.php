<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\ClientImage;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SupplierAPIController extends Controller
{
    public function store(Request $request, $subscriberId){

        Session::put('subscriberId', $subscriberId);
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

            $supplier->subscriber_id    = $subscriberId;

            $suppliers = Supplier::where([
                ['subscriber_id', $subscriberId]
            ])->first();

           if($suppliers){
                $suppliers = Supplier::where([
                    ['subscriber_id', $subscriberId]
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
            $coa->subscriber_id         = $subscriberId;
            $coa->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Supplier created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function list(Request $request, $subscriberId){
        $data = Supplier::where('subscriber_id', $subscriberId)->get();

        return response()->json($data);
    }
}
