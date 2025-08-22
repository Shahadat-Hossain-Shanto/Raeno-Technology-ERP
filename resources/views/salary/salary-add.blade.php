@extends('layouts.master')
@section('title', 'Create Salary Grade')

@section('content')
<div class="content-wrapper" id="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <!-- Header -->
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong>SALARY GRADE</strong></h5>

                        </div>

                        <div class="card-body">
                            <div class="container">

                                <form name="AddSalaryForm " method="POST" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <div class="row">
                                        <div class="col-2">
                                        </div>
                                        <div class="col-7">
                                            <div class="form-group">
                                                <label for="salarygradename" class="form-label" style="font-weight: normal;">Salary Grade Name<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="text" class="form-control" id="salarygradename" name="salarygradename" placeholder="Enter Salary Grade Name">
                                                <h6 class="text-danger pt-1" id="wrong_salarygradename" style="font-size: 14px;"></h6>

                                            </div>

                                            <div class="form-group">
                                                <label for="salarytype" class="form-label" style="font-weight: normal;">Salary Type<span class="text-danger"><strong>*</strong></span></label>
                                                <select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="salarytype" id="salarytype">
                                                    <option value="option_select" disabled selected>Select Salary Type
                                                    </option>

                                                    <option value="weekly">Weekly</option>
                                                    <option value="monthly">Monthly</option>
                                                    {{-- @foreach($stores as $store)
                                                    <option value="{{ $store->store_name  }}">{{ $store->store_name }}
                                                    </option>
                                                    @endforeach --}}
                                                </select>
                                                <h6 class="text-danger pt-1" id="wrong_salarytype" style="font-size: 14px;"></h6>
                                            </div>

                                            <div class="form-group">
                                                <label for="basicpay" class="form-label" style="font-weight: normal;">Basic Pay<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="text" class="form-control" id="basicpay" name="basicpay" placeholder="Enter Basic Payment Amount">
                                                <h6 class="text-danger pt-1" id="wrong_basicpay" style="font-size: 14px;"></h6>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-6">
                                            <table class="table table-bordered" id="addition_table">
                                                <thead>
                                                    <tr>
                                                        <th>Addition</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="addition_table_body">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-6">
                                            <table class="table table-bordered" id="deduction_table">
                                                <thead>
                                                    <tr>
                                                        <th>Deduction</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="deduction_table_body">

                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="text" class="form-control" style="display: none" id="additionamount" name="additionamount" placeholder="additionamount" value="0">

                                        <input type="text" class="form-control" style="display: none" id="deductionamount" name="deductionamount" placeholder="deductionamount" value="0">
                                        {{-- style="display: none" --}}

                                    </div>


                                    <div class="row">
                                        <div class="col-2">
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label for="payable" class="col-sm-3 col-form-label text-center">
                                                    <h6><b>Gross Salary</b></h6>
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="gross_salary" id="grsalary" readonly="">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-9" id="special_addition_div_per">
                                            <table class="table table-bordered" id="special_addition_table_per">
                                                <thead>
                                                    <tr>
                                                        <th>Special Addition Table</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Amount</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody id="special_addition_table_body_per">

                                                </tbody>
                                            </table>
                                        </div>

                                        <input type="text" class="form-control" style="display: none" id="special_additionamount" name="special_additionamount" placeholder="special_additionamount" value="0">


                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary mt-2 col-form-label " onclick="collectingData()">Create</button>
                                        <button type="reset" value="Reset" class="btn btn-outline-danger mt-2 col-form-label" onclick=""><i class="fas fa-eraser"></i> Reset</button>
                                    </div>
                                </form>

                            </div> <!-- container -->
                        </div> <!-- card-body -->
                    </div> <!-- card card-primary card-outline -->
                </div> <!-- col-lg-5 -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </div> <!-- content -->

</div> <!-- content-wrapper -->

@endsection

@section('script')

<script type="text/javascript" src="{{asset('js/salary.js')}}"></script>

@endsection
