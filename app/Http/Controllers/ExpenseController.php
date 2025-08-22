<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\Store;
use App\Models\ClientImage;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ExpenseController extends Controller
{
    public function create(){

        $expenseTypes = ExpenseCategory::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('expense/expense-add', ["expense_types" => $expenseTypes, "stores" => $stores]);
    }

    public function store(Request $request){

        $messages = [
            'expensecategory.required'  =>    "Expense type is required.",
            'expenseamount.required'  =>    "Expense amount is required.",
            'store.required'  =>    "Store is required.",
            'expensedate.required'  =>    "Store is required.",
        ];

        $validator = Validator::make($request->all(), [
            'expensecategory' => 'required',
            'expenseamount' => 'required',
            'store' => 'required',
            'expensedate' => 'required',

        ], $messages);

        if ($validator->passes()) {
            $expense = new Expense;

            $expense->expense_type = $request->expensecategory;
            $expense->amount = $request->expenseamount;
            $expense->store_id = $request->store;
            $expense->note = $request->note;
            $expense->expense_date = $request->expensedate;
            $expense->subscriber_id = Auth::user()->subscriber_id;
            $expense->submitted_by = Auth::user()->name;

            // $expense->image = $request->expirydate;
            if ($request -> hasFile('expenseimage')) {
                $clientImage =  new ClientImage;
                $file = $request -> file ('expenseimage');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = time() . '.' .$file->getClientOriginalName();
                // $filename = $file->getClientOriginalName();

                $file->move('uploads/expenses/', $filename);
                $clientImage->imageName  = $filename;
                $expense->image            = $filename;

                $clientImage->extension = $extension;
                $clientImage->type = 'expense';
                $clientImage->size = $size;

                $clientImage->save();
                // $expense->save();
            }

            $expense->save();
            return response() -> json([
                'status'=>200,
                'message' => 'Expense created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function listView(){

        $expenseTypes = ExpenseCategory::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('expense/expense-list', ["expense_types" => $expenseTypes, "stores" => $stores]);
    }

    public function list(Request $request){

        // $data = ExpenseCategory::join('expenses', 'expense_categories.id', 'expenses.expense_type')
        // ->join('stores', 'expenses.store_id', 'stores.id')
        // ->select('expenses.*', 'expense_categories.expense_type_name', 'stores.store_name')
        // ->where('expenses.subscriber_id', Auth::user()->subscriber_id)
        // ->get();
        $data = Expense::join('stores', 'expenses.store_id', 'stores.id')
        ->select('expenses.*', 'stores.store_name')
        ->where('expenses.subscriber_id', Auth::user()->subscriber_id)
        ->orderBy('expenses.id', 'desc')
        ->get();

        if($request -> ajax()){
            return response()->json([
                'data'=>$data,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $data = Expense::find($id);

        if($data){
            return response()->json([
                'status'=>200,
                'data'=>$data,
                
            ]);
        }
    }

    public function update(Request $request, $id){

       $messages = [
            'expensecategory.required'  =>    "Expense type is required.",
            'expenseamount.required'  =>    "Expense amount is required.",
            'store.required'  =>    "Store is required.",
            'expensedate.required'  =>    "Date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'expensecategory' => 'required',
            'expenseamount' => 'required',
            'store' => 'required',
            'expensedate' => 'required',

        ], $messages);

        if ($validator->passes()) {
            $expense = Expense::find($id);

            $expense->expense_type = $request->expensecategory;
            $expense->amount = $request->expenseamount;
            $expense->store_id = $request->store;
            $expense->note = $request->note;
            $expense->expense_date = $request->expensedate;

            if ($request -> hasFile('expenseimage')) {

                $path = 'uploads/expenses/'.$expense->image;
                if(File::exists($path)){
                    File::delete($path);
                }

                $file = $request -> file ('expenseimage');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $filename = time() . '.' .$file->getClientOriginalName();
                // $filename = $file->getClientOriginalName();
                $file->move('uploads/expenses/', $filename);
                // $supplierImage->imageName  = $filename;
                $expense->image            = $filename;
            }

            $expense->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Expense updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        Expense::find($id)->delete($id);
        return redirect('expense-list')->with('status', 'Deleted successfully!');
    }
}
