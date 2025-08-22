<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Order;
use App\Models\Store;
use App\Models\Client;

use App\Models\Supplier;
use App\Models\ClientImage;
use Illuminate\Http\Request;

use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    public function create(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('customer/customer-add', ['stores' => $stores]);
    }

    public function store(Request $request){

        $messages = [
            'customername.required'   =>   "Name is required.",
            'mobile.required'   =>   "Contact number is required.",
            'mobile.unique'   =>   "Contact number already exists."
        ];

        $validator = Validator::make($request->all(), [
            'customername'   => 'required',
            'mobile'   => 'required|unique:clients'
        ], $messages);

        if ($validator->passes()) {
            $customer = new Client;

            $customer->name             = $request->customername;
            $customer->mobile           = $request->mobile;
            $customer->type             = 'customer';
            $customer->email            = $request->customeremail;
            $customer->address          = $request->customeraddress;
            $customer->note             = $request->note;
            // $customer->storeId          = $request->storeid;
            $customer->subscriber_id    = Auth::user()->subscriber_id;

            $clients = Client::where([
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->first();

            if($clients){
                $clients = Client::where([
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])->latest()->first();
                $customer->head_code = (int)$clients->head_code + 1;
            }else{
                $customer->head_code = (1010101 * 1000) + 1;
            }

            if ($request -> hasFile('customerimage')) {
                $clientImage =  new ClientImage;
                $file = $request -> file ('customerimage');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/clients/', $filename);
                $clientImage->imageName  = $filename;
                $customer->image            = $filename;

                $clientImage->extension = $extension;
                $clientImage->type = 'customer';
                $clientImage->size = $size;
                $clientImage->save();
            }
            $customer->save();

            $coa = new ChartOfAccount;
            $coa->head_code             = $customer->head_code;
            $coa->head_name             = $request->customername.' '.$request->mobile;
            $coa->parent_head           = 'Customer Receivable';
            $coa->parent_head_level     = 1010101;
            $coa->head_type             = 'A';
            $coa->is_transaction        = '1';
            $coa->is_active             = '1';
            $coa->is_general_ledger     = '1';
            $coa->subscriber_id         = Auth::user()->subscriber_id;
            $coa->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Client created Successfully!',
                'customerId' => $customer->id
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function listView(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('customer/customer-list', ['stores' => $stores]);
    }

    public function list(Request $request){
        $data = Client::where('subscriber_id', Auth::user()->subscriber_id)->get();

        // $data = Store::join('clients', 'stores.id', '=', 'clients.storeId')
        //                 ->where('clients.subscriber_id', Auth::user()->subscriber_id)
        //                 ->get(['stores.*', 'clients.*']);

        // return $data;
         if($request -> ajax()){
            return response()->json([
                'customer'=>$data,
            ]);
        }


    }

    public function edit($id){
        $client = Client::find($id);


        if($client){
            return response()->json([
                'status'=>200,
                'client'=>$client,

            ]);
        }
    }

    public function update(Request $request, $id){
        $messages = [
            'customername.required'   =>   "Name is required.",
            'mobile.required'   =>   "Contact number is required."
        ];

        $validator = Validator::make($request->all(), [
            'customername'   => 'required',
            'mobile'   => 'required'
        ], $messages);

        if ($validator->passes()) {
            $customer = Client::find($id);

            $customer->name                 = $request->customername;
            $customer->mobile               = $request->mobile;
            $customer->email                = $request->customeremail;
            $customer->address              = $request->customeraddress;
            $customer->note                 = $request->note;
            // $customer->storeId              = $request->storeid;

            if ($request -> hasFile('customerimage')) {

                // $path = 'uploads/clients/'.$customer->image;
                // if(File::exists($path)){
                //     File::delete($path);
                // }

                $file = $request -> file ('customerimage');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = $file->getClientOriginalName();
                $file->move('uploads/clients/', $filename);
                // $supplierImage->imageName  = $filename;
                $customer->image            = $filename;
            }

            // $supplierImage = ClientImage::where('imageName')

            $customer->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Customer updated successfully'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function destroy($id){
        Client::find($id)->delete($id);

        return redirect('customer-list')->with('status', 'Deleted successfully!');
    }

    public function details(Request $request, $id){

        $client = Client::find($id);

        $duePayment = DB::table("due_payments")
                ->select(DB::raw("SUM(due_amount) as da"), "clientId")
                // ->whereDate('created_at', '=', $from)
                ->where([
                    ['clientId', '=', $id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id ]
                ])
                ->groupBy("clientId")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

         $ordersCount = Order::
                    where([
                        ['clientId', $id],
                        ['subscriber_id', '=', Auth::user()->subscriber_id ]
                    ])
                    ->count();

         $payments = DB::table("payments")
                ->select(DB::raw("SUM(amount) as a"), "clientId")
                // ->whereDate('created_at', '=', $from)
                ->where([
                    ['clientId', '=', $id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id ]
                ])
                ->groupBy("clientId")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

        $order = Order::where('clientId', $id)->get();

        // return $order;

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'order'=>$order,
            ]);
        }

        return view('customer/customer-details', ['customer' => $client, 'dues' => $duePayment, 'orders'=>$ordersCount, 'payments'=>$payments]);
    }

}
