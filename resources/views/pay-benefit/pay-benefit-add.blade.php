@extends('layouts.master')
@section('title', 'Pay Benefit')

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
				            <h5 class="m-0"><strong>PAY BENEFIT</strong></h5>
				        </div>

				      	<div class="card-body">
							<!-- <div class="container"> -->
								<form>
									<div class="row">
									 	<div class="col-2">
	                                        <div class="form-group">
	                                            <label for="specialbenefit" class="form-label" style="font-weight: normal;">Special Benefit<span class="text-danger"><strong>*</strong></span></label>
	                                            <select data-width="100%" class="selectpicker" name="specialbenefit" id="specialbenefit" data-live-search="true" title="Select Special Benefit" >

	                                                @foreach ($specialBenefits as $specialBenefit)
	                                                <option value="{{ $specialBenefit->id }}">{{$specialBenefit->benefit_name}}</option>
	                                                @endforeach

	                                            </select>

	                                            <h6 class="text-danger pt-1" id="wrong_department" style="font-size: 14px;"></h6>

	                                        </div>
	                                    </div>
	                                 	<div class="col-2">
	                                        <div class="form-group">
	                                            <label for="department" class="form-label" style="font-weight: normal;">Department<span class="text-danger"><strong>*</strong></span></label><br>
	                                            <select data-width="100%" class="selectpicker" name="department" id="department" data-live-search="true" title="Select Employee Department" >

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
									</div>
								</form>



								<div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive pt-3">
                                                <table id="salary_table" class="table table-bordered" width="100%">
                                                    <thead>
                                                        <tr>
                                                        	<th width="4%"></th>
                                                            <th width="5%">#ID</th>
                                                            <th width="10%">Employee</th>
                                                            <th width="10%">Designation</th>
                                                            <th width="10%">Department</th>
                                                            <th class="hidden">Department_id</th>
                                                            <th width="10%">Benefit</th>
                                                            <th class="hidden">Benefit_id</th>
                                                            <th width="10%">Amount</th>
                                                            <th width="9%">Allotment</th>
                                                            <th width="9%">Paid</th>
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
<script type="text/javascript" src="js/pay-benefit.js"></script>
<script>
$( document ).ready(function() {
   // console.log( "ready!" );
   fetchData()
});
</script>
@endsection
