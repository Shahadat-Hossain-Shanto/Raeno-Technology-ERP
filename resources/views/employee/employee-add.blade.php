@extends('layouts.master')
@section('title', 'Create Employee')

@section('content')
<div class="content-wrapper">
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
                            <h5 class="m-0"><strong><i class="fas fa-wallet"></i> Create Employee</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                <div id="form_div">

                                    <form id="AddEmployeeForm" method="" enctype="multipart/form-data">
                                        {{-- {{ csrf_field() }} --}}
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;"> Employee Name<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="text" class="form-control w-75" name="employee_name" id="employee_name" placeholder="Enter Employee Name">
                                                    <h6 class="text-danger pt-1" id="wrong_employeename" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">

                                                    <label for="brandname" class="form-label" style="font-weight: normal;">Phone<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <input type="text" class="form-control w-75" name="phone" id="phone" placeholder="Enter Employee Phone No.">

                                                    <h6 class="text-danger pt-1" id="wrong_phone" style="font-size: 14px;"></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Designation<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="designation" id="designation" data-live-search="true" title="Select Designation" data-width="75%">
                                                        @foreach($designations as $designation )
                                                        <option value="{{ $designation->designation_name }}">{{
															$designation->designation_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_designation" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Email<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="email" class="form-control w-75" name="email" id="email" placeholder="Enter Email">
                                                    <h6 class="text-danger pt-1" id="wrong_email" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Employee Type<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="employee_type" id="employee_type" data-live-search="true" title="Select Employee Type" data-width="75%">

                                                        <option value="temporary">Temporary</option>
                                                        <option value="parmanent">Parmanent</option>

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_employeetype" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Blood Group<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="blood_group" id="blood_group" data-live-search="true" title="Select Blood Group" data-width="75%">

                                                        <option value="A+">(A+)</option>
                                                        <option value="A-">(A-)</option>
                                                        <option value="B+">(B+)</option>
                                                        <option value="B-">(B-)</option>
                                                        <option value="O+">(O+)</option>
                                                        <option value="O-">(O-)</option>
                                                        <option value="AB+">(AB+)</option>
                                                        <option value="AB-">(AB-)</option>

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_bloodgroup" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Address<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <input type="text" class="form-control w-75" name="address" id="address" placeholder="Enter Address">
                                                    <h6 class="text-danger pt-1" id="wrong_address" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="city" class="form-label" style="font-weight: normal;">City<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="text" class="form-control w-75" name="city" id="city" placeholder="Enter City">
                                                    <h6 class="text-danger pt-1" id="wrong_city" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Department<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="employeedepartment" id="employeedepartment" data-live-search="true" title="Select Employee Department" data-width="75%">


                                                        @foreach ($employeeDepartments as $employeeDepartment)
                                                        <option value="{{ $employeeDepartment->id }}">{{$employeeDepartment->department_name}}</option>
                                                        @endforeach

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_employeedepartment" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Status<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="status" id="status" data-live-search="true" title="Select Employee Status" data-width="75%">

                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_status" style="font-size: 14px;"></h6>

                                                </div>

                                            </div>




                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="paymenttype" class="form-label" style="font-weight: normal;">Salary Type<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select class="selectpicker" name="salarytype" id="salarytype" data-live-search="true" title="Select Payment Type" data-width="75%">


                                                        <option value="hourly">Hourly</option>
                                                        <option value="weekly">Weekly</option>
                                                        <option value="monthly">Monthly</option>
                                                        
                                                       

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_paymenttype" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="image" class="form-label" style="font-weight: normal;">Image<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="file" class="form-control w-75" name="image" id="image" placeholder="Choose Image">
                                                    <h6 class="text-danger pt-1" id="wrong_image" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-6" id="salarygrade_div">
                                                <div class="form-group">
                                                    <label for="salarygrade" class="form-label" style="font-weight: normal;">Salary Grade<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select class="selectpicker" name="salarygrade" id="salarygrade" data-live-search="true" title="Select Salary Grade" data-width="75%">


                                                        @foreach ($salaryGrades as $salaryGrade)
                                                        <option value="{{ $salaryGrade->id }}">{{$salaryGrade->grade_name}}</option>
                                                        @endforeach

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_salarygrade" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6" id="salarygrade_hourly_div">
                                                <div class="form-group">
                                                    <label for="salarygrade" class="form-label" style="font-weight: normal;">Amount<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <input type="text" class="form-control w-75" name="hourlypaymentamount" id="hourlypaymentamount" placeholder="Enter Hourly Payment Amount">
                                                    <h6 class="text-danger pt-1" id="wrong_hourlypaymentamount" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        
                                            <div class="col-6">
                                                <img id="preview-image" src="{{asset('images/title-image.jpeg')}}" alt="preview image" style="width:180px;height:160px;">
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                 
                                        </div> --}}
                                        <div class="form-group pt-3">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                            <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                        </div>

                                    </form>
                                </div>

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
<script type="text/javascript" src="js/employee.js"></script>
<script type="text/javascript">
    $('#image').change(function() {

        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);

    });

</script>

@endsection
