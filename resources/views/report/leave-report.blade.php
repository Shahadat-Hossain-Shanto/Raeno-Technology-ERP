@extends('layouts.master')
@section('title', 'Attendance Report')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div><!-- /.col -->  
        </div><!-- /.row mb-2 -->
      </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
	          	<div class="col-lg-12">
	            
		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong><i class="fas fa-file-contract"></i> ATTENDANCE REPORT</strong></h5>
		              </div>
		              <div class="card-body">
                        <form id="LeaveReportForm" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="startdate" class="form-label" style="font-weight: normal;">From Date</label>
                                        <input type="date" class="form-control" name="startdate" id="startdate">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="enddate" class="form-label" style="font-weight: normal;">To Date</label>
                                        <input type="date" class="form-control" name="enddate" id="enddate">
                                    </div>
                                </div>
                                <!-- <div class="col-2">
                                    <div class="form-group">
                                        <label for="department" class="form-label" style="font-weight: normal;">Department</label><br>
                                        <select class="selectpicker" data-width="100%" title="" data-live-search="true" aria-label="Default select example" name="department" id="department" onchange="">
                                            <option value="" selected disabled>Select department</option>

                                        </select>
                                    </div>
                                </div> -->
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="employee" class="form-label" style="font-weight: normal;">Employee Name</label><br>
                                        <select class="selectpicker" data-width="100%" title="" data-live-search="true" aria-label="Default select example" name="employee" id="employee" onchange="">
                                            <option value="" selected disabled>Select employee</option>
                                            @foreach($employees as $employee)
                                              <option value="{{ $employee->id }}">{{ $employee->employee_name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group" style="padding-top: 32px;">
                                        <button type="submit" id="gen_btn" class="btn btn-primary">Generate</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="pt-2"><h5 class="text-center">Attendance: <b><span id="date"></span></b></h5></div>
                                <div class="table-responsive pt-3">

                                    <table id="leave_report_table" class="table table-bordered" width="100%">
                                        <thead class="">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="20%">Employee</th>
                                                <th width="13%">Department</th>
                                                <th width="12%">Designation</th>
                                                <th width="10%">Present</th>
                                                <th width="10%">Absent</th>
                                                <th width="10%">Late In</th>
                                                <th width="10%">Early Out</th>
                                                <th width="10%">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        


		              	
	                	
	                	

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->
		          </div>   <!-- /.col-lg-6 -->
        		</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/leave-report.js"></script>
@endsection


	
