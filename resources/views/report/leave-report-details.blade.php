@extends('layouts.master')
@section('title', 'Attendance Details')

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
		                	<h5 class="m-0"><strong><i class="fas fa-file-contract"></i> ATTENDANCE DETAILS</strong></h5>
		              </div>
		              <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <h5><b>Name:</b> <span>{{ $employee->employee_name}}</span></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <h5><b>Department:</b> <span>{{ $employee->department}}</span></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <h5><b>Designation:</b> <span>{{ $employee->designation}}</span></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="pt-2"><h5 class="text-center">Attendance: <b><span id="date">{{ $date }}</span></b></h5></div>
                                <div class="table-responsive pt-3">
                                    <table id="leave_report_table" class="table table-bordered" width="100%">
                                        <thead class="">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="20%">Date</th>
                                                <th width="20%">In-Time</th>
                                                <th width="20%">Out-Time</th>
                                                <th width="20%">Stay Time</th>
                                                <th width="15%">Status</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1
                                            @endphp
                                            @foreach($data as $item)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$item->attendance_date}}</td>
                                                <td>{{$item->sign_in}}</td>
                                                <td>{{$item->sign_out}}</td>
                                                <td>{{$item->stay_time}}</td>
                                                <td>{{$item->attendance_status}}</td>
                                            </tr>
                                            
                                            @endforeach
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
<script type="text/javascript" src="{{ asset('js/leave-report-details.js') }}"></script>
@endsection


	
