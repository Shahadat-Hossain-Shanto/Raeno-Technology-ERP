<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentMethodWEBController extends Controller
{
    public function create(){
        // $subscribers = Subscriber::all();
        return view('payment-methods/payment-method-add');
    }

    public function store(Request $req){

        $messages = [
            'paymentmethod.required'  =>    "Payment method name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'paymentmethod' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $paymentMethod = new PaymentMethod;
        
            $paymentMethod->subscriber_id                 = Auth::user()->subscriber_id;
            $paymentMethod->paymentType                 = $req->paymentmethod;
            
            $paymentMethod->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Payment mehtod created successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function listView(){
        return view('payment-methods/payment-method-list');
    }

    public function list(Request $request){

        $paymentMethod = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'paymentMethods'=>$paymentMethod,
            ]);
        }

        
    }

    public function edit($id){
        $paymentMethod = PaymentMethod::find($id);

        if($paymentMethod){
            return response()->json([
                'status'=>200,
                'paymentMethod'=>$paymentMethod,
                
            ]);
        }
    }

    public function update(Request $req, $id){

        $messages = [
            'paymentmethod.required'  =>    "Payment method name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'paymentmethod' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $paymentMethod = PaymentMethod::find($id);
            $paymentMethod->paymentType                 = $req->paymentmethod;
            
            $paymentMethod->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Payment method updated successfully'
            ]);
        }
        
        return response()->json(['error'=>$validator->errors()]);
        
    }

    public function destroy($id){
        PaymentMethod::find($id)->delete($id);

        return redirect('payment-method-list')->with('status', 'Deleted successfully!');
    }

}
