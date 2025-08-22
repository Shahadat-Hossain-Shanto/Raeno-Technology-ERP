@extends('layouts.master')
@section('title', 'Update Employee')

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
                            <h5 class="m-0"><strong><i class="fas fa-wallet"></i> Update Employee</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                <div id="form_div">

                                    <form id="UpdateEmployeeForm"  method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <input type="hidden" name="userid" id="userid" value="{{$employee->id}}">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;"> Employee Name<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="text" class="form-control w-75" name="employee_name" id="edit_employee_name" value="{{$employee->employee_name}}">
                                                    <h6 class="text-danger pt-1" id="edit_wrong_employeename" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">

                                                    <label for="brandname" class="form-label" style="font-weight: normal;">Phone<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <input type="text" class="form-control w-75" name="phone" id="edit_phone" value="{{$employee->phone}}">

                                                    <h6 class="text-danger pt-1" id="edit_wrong_phone" style="font-size: 14px;"></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Designation<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="designation" id="edit_designation" data-live-search="true" title="Select Designation" data-width="75%">
                                                        @foreach($designations as $designation )
                                                        <option value="{{ $designation->designation_name }}"{{$designation->designation_name == $employee->designation ? 'selected':''}}>{{$designation->designation_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <h6 class="text-danger pt-1" id="edit_wrong_designation" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Email<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="email" class="form-control w-75" name="email" id="edit_email" value="{{$employee->email}}">
                                                    <h6 class="text-danger pt-1" id="edit_wrong_email" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Employee Type<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="employee_type" id="edit_employee_type" data-live-search="true" title="Select Employee Type" data-width="75%">

                                                        <option value="temporary"{{$employee->employee_type=='temporary' ? 'selected':''}}>Temporary</option>
                                                        <option value="parmanent" {{$employee->employee_type=='parmanent' ? 'selected':''}}>Parmanent</option>

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="edit_wrong_employeetype" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Blood Group<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="blood_group" id="edit_blood_group" data-live-search="true" title="Select Blood Group" data-width="75%">

                                                        <option value="A+" {{$employee->blood_group=='A+' ? 'selected':''}}>(A+)</option>
                                                        <option value="A-" {{$employee->blood_group=='A-' ? 'selected':''}}>(A-)</option>
                                                        <option value="B+" {{$employee->blood_group=='B+' ? 'selected':''}}>(B+)</option>
                                                        <option value="B-" {{$employee->blood_group=='B-' ? 'selected':''}}>(B-)</option>
                                                        <option value="O+" {{$employee->blood_group=='O+' ? 'selected':''}}>(O+)</option>
                                                        <option value="O-" {{$employee->blood_group=='O-' ? 'selected':''}}>(O-)</option>
                                                        <option value="AB+" {{$employee->blood_group=='AB+' ? 'selected':''}}>(AB+)</option>
                                                        <option value="AB-" {{$employee->blood_group=='AB-' ? 'selected':''}}>(AB-)</option>

                                                    </select>
                                                    <h6 class="text-danger pt-1" id="edit_wrong_bloodgroup" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Address<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <input type="text" class="form-control w-75" name="address" id="edit_address" value="{{$employee->address}}">
                                                    <h6 class="text-danger pt-1" id="edit_wrong_address" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="city" class="form-label" style="font-weight: normal;">City<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="text" class="form-control w-75" name="city" id="edit_city" value="{{$employee->city}}">
                                                    <h6 class="text-danger pt-1" id="edit_wrong_city" style="font-size: 14px;"></h6>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="vatname" class="form-label" style="font-weight: normal;">Department<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="employeedepartment" id="edit_employeedepartment" data-live-search="true" title="Select Employee Department" data-width="75%">
                                        
                                                     
                                                    @foreach ($employeeDepartments as $employeeDepartment)
                                                    <option value="{{ $employeeDepartment->id }}"{{ $employeeDepartment->id ==$employee->department_id ? 'selected' : ''}}>{{$employeeDepartment->department_name}}</option>
                                                    @endforeach
                                        
                                                    </select>
                                                    <h6 class="text-danger pt-1" id="wrong_employeedepartment" style="font-size: 14px;"></h6>
                                        
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="status" class="form-label" style="font-weight: normal;">Status<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select style="width:50%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="status" id="edit_status" data-live-search="true" title="Select Employee Status" data-width="75%">
                                        
                                                        <option value="1" {{$employee->status==1 ? 'selected':''}}>Active</option>
                                                        <option value="0" {{$employee->status==0 ? 'selected':''}}>Inactive</option>
                                        
                                                    </select>
                                                    <h6 class="text-danger pt-1" id="edit_wrong_status" style="font-size: 14px;"></h6>
                                        
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="paymenttype" class="form-label" style="font-weight: normal;">Salary Type<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select class="selectpicker" name="salarytype" id="edit_salarytype" data-live-search="true" title="Select Payment Type" data-width="75%">
                                        
                                                        <option value="hourly" {{$employee->salary_type =="hourly" ?'selected' : ''}}>Hourly</option>
                                                        <option value="weekly"{{$employee->salary_type =="weekly" ?'selected' : ''}}>Weekly</option>
                                                        <option value="monthly"{{$employee->salary_type =="monthly" ?'selected' : ''}}>Monthly</option>
                                                        
                                                    </select>
                                                    <h6 class="text-danger pt-1" id="edit_wrong_paymenttype" style="font-size: 14px;"></h6>
                                        
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
                                           
                                            <div class="col-6" id="edit_salarygrade_hourly_div">
                                                <div class="form-group">
                                                    <label for="salarygrade" class="form-label" style="font-weight: normal;">Amount<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <input type="text" class="form-control w-75" name="hourlypaymentamount" id="edit_hourlypaymentamount" value="{{$employee->hourly_payment}}">
                                                    <h6 class="text-danger pt-1" id="edit_wrong_hourlypaymentamount" style="font-size: 14px;"></h6>

                                                </div>

                                            </div>    
                                            <input type="hidden" class="form-control w-75" id="edit_hourlypaymentamount_hold" value="{{$employee->hourly_payment}}">
                                            
                                            <div class="col-6" id="edit_salarygrade_div">
                                                <div class="form-group">
                                                    <label for="salarygrade" class="form-label" style="font-weight: normal;">Salary Grade<span class="text-danger"><strong>*</strong></span></label><br>
                                                    <select class="selectpicker" name="salarygrade" id="edit_salarygrade" data-live-search="true" title="Select Salary Grade" data-width="75%">
                                        
                                        
                                                        @foreach ($salaryGrades as $salaryGrade)
                                                        <option value="{{ $salaryGrade->id }}"{{$salaryGrade->id==$employee->salary_grade_id ? 'selected' : ''}} >{{$salaryGrade->grade_name}}</option>
                                                        @endforeach
                                        
                                                    </select>
                                                    <h6 class="text-danger pt-1" id="edit_wrong_salarygrade" style="font-size: 14px;"></h6>
                                        
                                                </div>
                                            </div>
                                           
                                            
                                        
                                            <div class="col-6">
                                                <img id="preview-image" src="{{asset('images/title-image.jpeg')}}" alt="preview image" style="width:180px;height:160px;">
                                            </div>
                                        </div>
                                        <div class="form-group pt-3">
                                           
                                            <button type="submit" class="btn btn-primary">Update</button>
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
<script type="text/javascript" src="{{asset('js/employee-loadimg.js')}}"></script>   
<script type="text/javascript" src="{{asset('js/employee.js')}}"></script>   
<script type="text/javascript">
    $('#edit_image').change(function(){
           
    let reader = new FileReader();
    reader.onload = (e) => { 
      $('#preview-image').attr('src', e.target.result); 
    }
    reader.readAsDataURL(this.files[0]); 
  
   });
  </script>

@endsection
