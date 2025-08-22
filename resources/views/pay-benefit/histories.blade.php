@extends('layouts.master')
@section('title', 'Pay Benefit history')

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
				            <h5 class="m-0"><strong>PAY BENEFIT history</strong></h5>
				        </div>
                            <input type="hidden" id="empId" value="{{$empId}}">
				      	<div class="card-body">
							<!-- <div class="container"> -->
								<div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive pt-3">
                                                <table id="history_table" class="table table-bordered" width="100%">
                                                    <thead>
                                                        <tr>

                                                            <th width="5%">#ID</th>
                                                            <th width="10">Employee</th>
                                                            <th width="10%">Designation</th>
                                                            <th width="10%">Department</th>
                                                            <th width="10%">Benefit</th>
                                                            <th width="10%">Amount</th>
                                                            <th width="10%">month</th>
                                                            <th width="15%"></th>
                                                            <th class="hidden">Benefit_id</th>


                                                            <th width="10%"></th>
                                                        </tr>
                                                    </thead>
                                                    <!-- <tbody>

                                                    </tbody> -->
                                                </table>
                                            </div>
                                        </div>
                                    </div>
							<!-- </div> -->
						</div> <!-- card-body -->
			  		</div> <!-- card card-primary card-outline -->
				</div> <!-- col-lg-4 -->
			</div> <!-- row -->
		</div>
	</div>
</div>
@endsection

@section('script')
{{-- <script type="text/javascript" src="js/pay-benefit.js"></script> --}}
<script type="text/javascript" src="{{ URL::asset('js/pay-benefit.js') }}"></script>

<script>
$( document ).ready(function() {
    //console.log( "ready!" );
    historyData()

});

</script>
@endsection
