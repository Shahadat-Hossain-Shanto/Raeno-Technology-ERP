<?php

namespace App\Http\Controllers;

use Log;
use Session;
use Redirect;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;


use App\Exports\ExportProduct;
use App\Imports\ImportProduct;
use App\Imports\ImportProductStore;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProductExcelController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id',Auth::user()->subscriber_id)->get();
        return view('product/product-excel',['stores' => $stores]);
    }

    public function demo(){
        $path = 'uploads/excel/demo.xlsx';
        $fileName = 'demo.xlsx';
        return Response::download($path, $fileName, ['Content-Type: application/xlsx']);
    }

    public function import(Request $request){

        $file = $request -> file ('file');
        if ($request->hasFile('file')) {
            $validator = Validator::make(
                [
                    'file'      => $file,
                    'extension' => strtolower($file->getClientOriginalExtension()),
                ],
                [
                    'extension'      => 'required|in:xlsx,csv',
                ]
            );

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            if($request->addORin=='add')
            {
                Excel::import(new ImportProduct, $request->file('file')->store('files'));
                return redirect()->back();
            }
            elseif($request->addORin=='in')
            {
                Session::put('productInStore', $request->store);
                Excel::import(new ImportProductStore, $request->file('file')->store('files'));
                return redirect()->back();
            }
            else{
            return Redirect::back()->withErrors(['msg' => 'Select required fields']);
            }

        }else{
            return Redirect::back()->withErrors(['msg' => 'File required! Please select a .xlsx file.']);
        }

    }

    // public function import(Request $request){
    //     Excel::import(new ImportProduct, $request->file('file')->store('files'));
    //     return redirect()->back();
    // }

    public function addProduct(Request $request){
        $path = 'uploads/excel/Add_Products.xlsx';
        $fileName = 'Add_Products.xlsx';
        return Response::download($path, $fileName, ['Content-Type: application/xlsx']);
    }

    public function productIn(Request $request){
        return Excel::download(new ExportProduct, 'Stock_In.xlsx');
    }
}
