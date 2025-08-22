<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\BenefitList;
use App\Models\SalaryGrade;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalaryController extends Controller
{
    public function create(Request $req)
    {
        $benefit = Benefit::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if ($req->ajax()) {
            return response()->json([
                'status' => 200,
                'benefit' => $benefit,
            ]);
        }
        return view('salary.salary-add');
    }

    public function store(Request $req)
    {
        $messages = [
            'salarygradename.required'  =>    "Salary Grade Name is required.",
            'salarytype.required'       =>    "Salary Type is required.",

            'basicpay.required'             =>    "Basic Pay is required.",


        ];

        $salaryGradeNameX = $req->gradeName;
        $salaryTypeX = $req->salarytype;
        $salaryBasicPayX = $req->basicPay;

        $x = [
            'salarygradename'  => $salaryGradeNameX,
            'salarytype'          => $salaryTypeX,
            'basicpay'    => $salaryBasicPayX,
        ];

        $validator = Validator::make($x, [
            'salarygradename'  => 'required',
            'salarytype'          => 'required',
            'basicpay'    => 'required',

        ], $messages);
        if ($validator->passes()) {
            $salary_grade = new SalaryGrade;
            $salary_grade->grade_name = $req->gradeName;
            $salary_grade->salary_type = $req->salarytype;
            $salary_grade->basic_pay = $req->basicPay;
            $salary_grade->addition = $req->totalAddition;
            $salary_grade->deduction = $req->totalDeduction;

            if ($req->specialAdditionAmount != '') {
                $salary_grade->special_addition = $req->specialAdditionAmount;
            } else {
                $salary_grade->special_addition = 0;
            }
            $salary_grade->gross_salary = $req->grsalary;

            $salary_grade->subscriber_id = Auth::user()->subscriber_id;
            $salary_grade->save();

            foreach ($req->additionList as $addition) {

                $benifit_list = new BenefitList;
                $benifit_list->benefit_name    = $addition['additionField'];
                $benifit_list->benefit_id      = $addition['benefit_id'];
                $benifit_list->benefit_type    = $addition['benefit_type'];
                $benifit_list->amount          = $addition['additionAmount'];
                $benifit_list->total_amount    = $addition['total_amount'];

                // Log::info($benifit_list);
                // $benifit_list->save();
                $benifit_list->grade_name = $req->gradeName;
                $benifit_list->grade_id = $salary_grade->id;
                $benifit_list->subscriber_id = Auth::user()->subscriber_id;
                $benifit_list->save();
            }
            foreach ($req->deductionList as $deduction) {
                $benifit_list = new BenefitList;
                $benifit_list->benefit_name    = $deduction['deductionField'];
                $benifit_list->benefit_id      = $deduction['benefit_id'];
                $benifit_list->benefit_type    = $deduction['benefit_type'];
                $benifit_list->amount          = $deduction['deductionAmount'];
                $benifit_list->total_amount    = $deduction['total_amount'];

                // Log::info($benifit_list);
                // $benifit_list->save();
                $benifit_list->grade_name = $req->gradeName;
                $benifit_list->grade_id = $salary_grade->id;
                $benifit_list->subscriber_id = Auth::user()->subscriber_id;
                $benifit_list->save();
            }
            foreach ($req->specialBenefitList as $specialAdditon) {



                $benifit_list = new BenefitList;
                $benifit_list->benefit_name    = $specialAdditon['specialAdditionField'];
                $benifit_list->benefit_id      = $specialAdditon['benefit_id'];
                $benifit_list->benefit_type    = $specialAdditon['benefit_type'];
                $benifit_list->amount          = $specialAdditon['specialAdditionAmount'];
                $benifit_list->total_amount    = $specialAdditon['total_amount'];
                $benifit_list->yearly_allocation = $specialAdditon['allotmentAmount'];


                // log::info("specialAdditon \n");
                // log::info($specialAdditon);
                // $benifit_list->save();
                $benifit_list->grade_name = $req->gradeName;
                $benifit_list->grade_id = $salary_grade->id;
                $benifit_list->subscriber_id = Auth::user()->subscriber_id;
                $benifit_list->save();
            }


            return response()->json([
                'status' => 200,
                'benifit_list' => $benifit_list,
                'message' => 'Added Successfully.'
            ]);
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function salaryIndex()
    {
        return view('salary.salary-list');
    }


    public function salaryIndexData(Request $req)
    {
        $SalaryGrade = SalaryGrade::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if ($req->ajax()) {
            return response()->json([
                'status' => 200,
                'SalaryGrade' => $SalaryGrade
            ]);
        }
    }
    public function salaryEdit($id)
    {

        $SalaryGrade = SalaryGrade::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['id', $id],
        ])->first();
        // $benefits_add = BenefitList::where([
        //     ['subscriber_id', Auth::user()->subscriber_id],
        //     ['grade_id', $id],
        //     ['benefit_type', 'add'],

        // ])->get();
        $benefits_add = DB::table('benefit_lists')
            ->select('benefit_lists.benefit_id', 'benefit_lists.benefit_type', 'benefit_lists.benefit_name', 'benefit_lists.amount', 'benefits.payment_type')
            ->join('benefits', 'benefit_lists.benefit_id', '=', 'benefits.id')
            ->where([['benefit_lists.subscriber_id', '=', Auth::user()->subscriber_id], ['benefit_lists.grade_id', '=', $id], ['benefit_lists.benefit_type', '=', 'add'],['benefits.benefit_regularity', '=', 'regular']])
            ->get();
        $benefits_deduct = DB::table('benefit_lists')
            ->select('benefit_lists.benefit_id', 'benefit_lists.benefit_type', 'benefit_lists.benefit_name', 'benefit_lists.amount', 'benefits.payment_type')
            ->join('benefits', 'benefit_lists.benefit_id', '=', 'benefits.id')
            ->where([['benefit_lists.subscriber_id', '=', Auth::user()->subscriber_id], ['benefit_lists.grade_id', '=', $id], ['benefit_lists.benefit_type', '=', 'deduct']])
            ->get();

        $special_benefits_add = DB::table('benefit_lists')
            ->select('benefit_lists.benefit_id', 'benefit_lists.benefit_type', 'benefit_lists.yearly_allocation', 'benefit_lists.benefit_name', 'benefit_lists.amount', 'benefits.payment_type', 'benefits.benefit_regularity')
            ->join('benefits', 'benefit_lists.benefit_id', '=', 'benefits.id')
            ->where([['benefit_lists.subscriber_id', '=', Auth::user()->subscriber_id], ['benefit_lists.grade_id', '=', $id], ['benefit_lists.benefit_type', '=', 'add'], ['benefits.benefit_regularity', '=', 'special']])
            ->get();

        return view('salary.salary-edit', compact('SalaryGrade', 'benefits_add', 'benefits_deduct', 'special_benefits_add'));
    }

    public function salaryUpdate(Request $req, $id)
    {
        $messages = [
            'salarygradename.required'  =>    "Salary Grade Name is required.",
            // 'salarytype.required'       =>    "Salary Type is required.",

            'basicpay.required'             =>    "Basic Pay is required.",


        ];

        $salaryGradeNameX = $req->gradeName;
        // $salaryTypeX = $req->salarytype;
        $salaryBasicPayX = $req->basicPay;

        $x = [
            'salarygradename'  => $salaryGradeNameX,
            // 'salarytype'          => $salaryTypeX,
            'basicpay'    => $salaryBasicPayX,
        ];

        $validator = Validator::make($x, [
            'salarygradename'  => 'required',
            // 'salarytype'          => 'required',
            'basicpay'    => 'required',

        ], $messages);
        // $input = $req->all();
        if ($validator->passes()) {
            foreach ($req->additionList as $addition) {
                $benefit_lists =  BenefitList::where([
                    ['benefit_id', $addition['benefit_id']],
                    ['grade_id', $id],
                ])
                    ->get();

                foreach ($benefit_lists as $benefit_list) {
                    $benefit_list_id = $benefit_list->id;
                    $benefit_update = BenefitList::find($benefit_list_id);
                    $benefit_update->amount = $addition['additionAmount'];
                    // $benefit_update->total_amount    = $addition['total_amount'];
                    $benefit_update->total_amount    = $addition['total_amount'];

                    $benefit_update->save();
                }
            }
            foreach ($req->deductionList as $deduction) {
                $deduction_lists =  BenefitList::where([
                    ['benefit_id', $deduction['benefit_id']],
                    ['grade_id', $id],
                ])
                    ->get();

                foreach ($deduction_lists as $deduction_list) {
                    $deduction_list_id = $deduction_list->id;
                    $deduction_update = BenefitList::find($deduction_list_id);
                    $deduction_update->amount = $deduction['deductionAmount'];
                    $deduction_update->total_amount    = $deduction['total_amount'];

                    $deduction_update->save();
                }
            }

            foreach ($req->specialBenefitList as $specialAdditon) {
                $special_additon_list =  BenefitList::where([
                    ['benefit_id', $specialAdditon['benefit_id']],
                    ['grade_id', $id],
                ])
                    ->get();

                foreach ($special_additon_list as $addition_list) {
                    $addition_list_id                         = $addition_list->id;
                    $special_addition_update                  = BenefitList::find($addition_list_id);
                    $special_addition_update->amount          = $specialAdditon['specialAdditionAmount'];
                    $special_addition_update->total_amount    = $specialAdditon['total_amount'];
                    $special_addition_update->yearly_allocation = $specialAdditon['allotmentAmount'];

                    $special_addition_update->save();
                }
            }

            $salary_grade_update = SalaryGrade::where('id', $id)->first();
            $salary_grade_update->grade_name = $req->gradeName;
            $salary_grade_update->basic_pay = $req->basicPay;
            $salary_grade_update->salary_type = $req->salarytype;
            $salary_grade_update->addition = $req->totalAddition;
            $salary_grade_update->deduction = $req->totalDeduction;
            $salary_grade_update->gross_salary = $req->grsalary;
            if ($req->specialAdditionAmount != '') {
                $salary_grade_update->special_addition = $req->specialAdditionAmount;
            } else {
                $salary_grade_update->special_addition = 0;
            }


            $salary_grade_update->save();

            return response()->json([
                'status' => 200,
                'message' => 'Updated Successfully'
            ]);
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        SalaryGrade::find($id)->delete($id);
        BenefitList::where('grade_id', $id)->delete($id);

        return redirect('salary-list')->with('status', 'Deleted successfully!');
    }
}
