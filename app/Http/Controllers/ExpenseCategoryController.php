<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ChartOfAccount;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function create(){
        return view('expense/expense-category-add');
    }

    public function store(Request $request){
         $messages = [
            'expensecategoryname.required'  => "Expense type is required.",
        ];

        $validator = Validator::make($request->all(), [
            'expensecategoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $expenseType = new ExpenseCategory;

            $expenseType->expense_type_name = $request->expensecategoryname;
            $expenseType->note = $request->note;

            $expenseType->subscriber_id = Auth::user()->subscriber_id;
            $expenseType->user_id = Auth::user()->id;

            $expenseType->save();

            $expenCategory = ChartOfAccount::where([['subscriber_id', Auth::user()->subscriber_id],['parent_head_level',3]])->orderByDesc('head_code')->first('head_code');
            $coa = new ChartOfAccount;
            $coa->head_code             = (int)$expenCategory->head_code + 1;
            $coa->head_name             = $request->expensecategoryname;
            $coa->parent_head           = 'Expense';
            $coa->parent_head_level     = 3;
            $coa->head_type             = 'E';
            $coa->is_transaction        = 1;
            $coa->is_active             = 1;
            $coa->is_general_ledger     = 0;
            $coa->subscriber_id         = Auth::user()->subscriber_id;
            $coa->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Expense type created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    public function listView(){
        return view('expense/expense-category-list');
    }

    public function list(Request $request){

        $expenCategory = ExpenseCategory::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'data'=>$expenCategory,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $expenCategory = ExpenseCategory::find($id);

        if($expenCategory){
            return response()->json([
                'status'=>200,
                'data'=>$expenCategory,

            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'expensecategory.required'  =>    "Expense type is required.",

        ];

        $validator = Validator::make($request->all(), [
            'expensecategory' => 'required',

        ], $messages);

        if ($validator->passes()) {
            $expenCategory = ExpenseCategory::find($id);

            $expenCategory->expense_type_name    = $request->expensecategory;
            $expenCategory->note            = $request->note;

            $expenCategory->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Expense type updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);

    }

    public function destroy($id){
        ExpenseCategory::find($id)->delete($id);
        return redirect('expense-category-list')->with('status', 'Deleted successfully!');
    }
}
