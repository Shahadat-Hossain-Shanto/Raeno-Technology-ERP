<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\SpecailBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BenefitController extends Controller
{
    public function create()
    {
        return view('benefit.benefit-create');
    }

    public function store(Request $req)
    { {
            $messages = [
                'benefit_name.required'       =>    "Benefit Name is required.",
                'benefit_type.required'       =>    "Benefit Type is required.",
                'status.required'             =>    "Status is required.",
                'payment_type.required'       =>    "Payment Type is required.",
                'benefit_regularity.required'  =>    "Benefit Regularity is required.",
            ];
            $validator = Validator::make($req->all(), [
                'benefit_name'      => 'required',
                'benefit_type'      => 'required',
                'status'            => 'required',
                'payment_type'      => 'required',
                'benefit_regularity' => 'required',
            ], $messages);

            if ($validator->passes()) {
                // if ($req->benefit_regularity == 'regular') {
                $benefit = new Benefit;
                $benefit->benefit_name       = $req->benefit_name;
                $benefit->benefit_type       = $req->benefit_type;
                $benefit->status             = $req->status;
                $benefit->payment_type       = $req->payment_type;
                $benefit->benefit_regularity = $req->benefit_regularity;
                // $benefit->yearly_allotment   = $req->yearly_allotment;
                $benefit->subscriber_id      = Auth::user()->subscriber_id;

                $benefit->save();
                // } else {
                //     $special_benefit = new SpecailBenefit;
                //     $special_benefit->benefit_name       = $req->benefit_name;
                //     $special_benefit->benefit_type       = $req->benefit_type;
                //     $special_benefit->status             = $req->status;
                //     $special_benefit->payment_type       = $req->payment_type;
                //     $special_benefit->benefit_regularity = $req->benefit_regularity;
                //     $special_benefit->yearly_allotment   = $req->yearly_allotment;
                //     $special_benefit->subscriber_id      = Auth::user()->subscriber_id;


                //     $special_benefit->save();
                // }

                return redirect()->route('benefit.list.view');
            }
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function index()
    {
        return view('benefit.benefit-list');
    }

    public function indexData(Request $req)
    {
        $benefit = Benefit::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $specailBenefit = SpecailBenefit::where('subscriber_id', Auth::user()->subscriber_id)->get();

        // $x = $benefit->concat($specailBenefit);

        if ($req->ajax()) {
            return response()->json([
                'benefit' => $benefit,
            ]);
        }
    }

    public function edit(Request $req, $id)
    {
        $benefit = Benefit::find($id);
        // $specailBenefit = SpecailBenefit::where('subscriber_id', Auth::user()->subscriber_id)->get();
        if ($req->ajax()) {
            return response()->json([
                'status' => 200,
                'benefit' => $benefit,
            ]);
        }
    }

    public function update(Request $req, $id)
    {

        $messages = [
            'benefit_name.required'  =>    "Benefit Name is required.",
            'benefit_type.required'  =>    "Benefit Type is required.",
            'status.required'        =>    "Status is required.",
            'payment_type.required'  =>    "Payment Type is required.",
            'benefit_regularity.required'  =>    "Benefit Regularity is required.",
        ];
        $validator = Validator::make($req->all(), [
            'benefit_name' => 'required',
            'benefit_type' => 'required',
            'status' => 'required',
            'payment_type' => 'required',
            'benefit_regularity' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $benefit = Benefit::find($id);
            $benefit->benefit_name  = $req->benefit_name;
            $benefit->benefit_type  = $req->benefit_type;
            $benefit->status  = $req->status;
            $benefit->payment_type  = $req->payment_type;
            $benefit->benefit_regularity = $req->benefit_regularity;
            // $benefit->yearly_allotment   = $req->yearly_allotment;

            $benefit->save();
            return response()->json([
                'status' => 200,
                'message' => 'Designation updated successfully'
            ]);
            // return redirect()->route('benefit.list.view');
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        Benefit::find($id)->delete($id);
        return redirect()->route('benefit.list.view')->with('status', 'Deleted successfully!');
    }
}
