@extends('layouts.master')
@section('title', 'Create Weekly Salary')

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
                            <h5 class="m-0"><strong> CREATE WEEKLY SALARY</strong></h5>

                        </div>

                        <div class="card-body">
                            <!-- <div class="container"> -->
                                <form>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="startdate" class="form-label" style="font-weight: normal;">Select Start Date<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="date" class="form-control" name="startdate" id="startdate" >
                                            
                                                <h6 class="text-danger pt-1" id="wrong_startdate" style="font-size: 14px;"></h6>
                                            
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="enddate" class="form-label" style="font-weight: normal;">Select End Date<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="date" class="form-control" name="enddate" id="enddate" >
                                            
                                                <h6 class="text-danger pt-1" id="wrong_startdate" style="font-size: 14px;"></h6>
                                            
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="department" class="form-label" style="font-weight: normal;">Select Department<span class="text-danger"><strong>*</strong></span></label>
                                                <select data-width="100%" class="selectpicker" name="department" id="department" data-live-search="true" title="Select Employee Department" data-width="75%">

                                                    @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{$department->department_name}}</option>
                                                    @endforeach

                                                </select>
                                            
                                                <h6 class="text-danger pt-1" id="wrong_department" style="font-size: 14px;"></h6>
                                            
                                            </div>
                                        </div>
                                        
                                        <div class="col-4">
                                            <div class="form-group" style="padding-top: 31px;">
                                                <button id="gen_btn" onclick="collection()" type="button" class="btn btn-primary">Generate</button>
                                                <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                                
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group float-right" style="padding-top: 31px;">
                                                <button id="payall_btn" style="width: 180px" onclick="" type="button" class="btn btn-danger">Pay All</button>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pt-2"><h5 class="text-center">WEEK: <b><span id="date"></span></b></h5></div>
                                            <div class="table-responsive pt-3">
                                                <table id="salary_table" class="table table-bordered" width="100%">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th width="5%">ID</th>
                                                            <th width="10%">Employee</th>
                                                            <th width="7%">Designation</th>
                                                            <th width="7%">Department</th>
                                                            <th width="7%">Present</th>
                                                            <th width="7%">Absent</th>
                                                            <th width="7%">Leave</th>
                                                            <th width="7%">Basic</th>
                                                            <th width="7%">Absent Deduction</th>
                                                            <th width="7%">Addition</th>
                                                            <th width="7%">Deduction</th>
                                                            <th width="7%">Net</th>
                                                            <th class="hidden">Salary Grade Id</th>
                                                            <th class="hidden">isSalaryPaid</th>
                                                            <th width="12%">action</th>
                                                        </tr>
                                                    </thead>
                                                    <!-- <tbody>

                                                    </tbody> -->
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </form>
                            <!-- </div> container -->
                        </div> <!-- card-body -->
                    </div> <!-- card card-primary card-outline -->
                </div> <!-- col-lg-5 -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </div> <!-- content -->

</div> <!-- content-wrapper -->

@endsection

@section('script')

<script type="text/javascript" src="{{asset('js/weekly-salary-create.js')}}"></script>

@endsection