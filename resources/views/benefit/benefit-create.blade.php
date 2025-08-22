@extends('layouts.master')
@section('title', 'Create Benefit')

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

                  <div class="col-lg-5">
				
					<div class="card card-primary">
				        <div class="card-header">
				            <h5 class="m-0"><strong>BENEFIT</strong></h5>
				        </div>

				      	<div class="card-body">
							<div class="container" id="form_div">
									<form id="AddBenefitForm" method="POST" enctype="multipart/form-data">
                                        @csrf
									<div class="form-group row">
                                        <label for="benefit_name" class="col-sm-4 col-form-label" style="font-weight: normal;">Name<span class="text-danger"><strong>*</strong></span></label>
									    <div class="col-sm-8">
                                            <input type="text" class="form-control w-75" name="benefit_name" id="benefit_name" placeholder="e.g. Transport Allowance or TA">
                                            <h6 class="text-danger pt-1" id="wrong_benefit_name" style="font-size: 14px;"></h6>
									    </div>
							  		</div>

							  		<div class="form-group row">
									    <label for="benefit_type" class="col-sm-4 col-form-label" style="font-weight: normal;">Type<span class="text-danger"><strong>*</strong></span></label>
									    <div class="col-sm-8">
									      <select class="selectpicker" data-width="75%" data-live-search="true" aria-label="Default select example" name="benefit_type" id="benefit_type">
									      	<option value="option_select" disabled selected>Select Type</option>
							            	
                                              <option value="add">Add</option>
                                              <option value="deduct">Deduct</option>
									      	{{-- @foreach($stores as $store)
							            	<option value="{{ $store->store_name  }}">{{ $store->store_name  }}</option>
							        		@endforeach --}}
									      </select>
                                          <h6 class="text-danger pt-1" id="wrong_benefit_type" style="font-size: 14px;"></h6>
									    </div>
							  		</div>

                                      <div class="form-group row">
									    <label for="status" class="col-sm-4 col-form-label" style="font-weight: normal;">Status<span class="text-danger"><strong>*</strong></span></label>
									    <div class="col-sm-8">
									      <select class="selectpicker" data-width="75%" data-live-search="true" aria-label="Default select example" name="status" id="status">
									      	<option value="option_select" disabled selected>Select Status Type</option>
							            	
                                              <option value=1>Active</option>
                                              <option value=0>Inactive</option>
									      	{{-- @foreach($stores as $store)
							            	<option value="{{ $store->store_name  }}">{{ $store->store_name  }}</option>
							        		@endforeach --}}
									      </select>
                                          <h6 class="text-danger pt-1" id="wrong_status" style="font-size: 14px;"></h6>
									    </div>
							  		</div>

									  <div class="form-group row">
									    <label for="benefit_regularity" class="col-sm-4 col-form-label" style="font-weight: normal;">Regularity<span class="text-danger"><strong>*</strong></span></label>
									    <div class="col-sm-8">
									      <select class="selectpicker" data-width="75%" data-live-search="true" aria-label="Default select example" name="benefit_regularity" id="benefit_regularity">
									      	<option value="option_select" disabled selected>Select Benefit Regularity</option>
							            	
                                              <option value='regular'>Regular</option>
                                              <option value='special'>Special</option>
									      	{{-- @foreach($stores as $store)
							            	<option value="{{ $store->store_name  }}">{{ $store->store_name  }}</option>
							        		@endforeach --}}
									      </select>
                                          <h6 class="text-danger pt-1" id="wrong_benefit_regularity" style="font-size: 14px;"></h6>
									    </div>
							  		</div>
									  {{-- <div class="form-group row" id="yearly_allotment_div"> 
                                        <label for="yearly_allotment" class="col-sm-4 col-form-label" style="font-weight: normal;">Yearly Allotment<span class="text-danger"><strong>*</strong></span></label>
									    <div class="col-sm-8">
                                            <input type="text" class="form-control w-75" name="yearly_allotment" id="yearly_allotment" placeholder="Input Yearly Allotment e.g. 2">
                                            <h6 class="text-danger pt-1" id="wrong_yearly_allotment" style="font-size: 14px;"></h6>
									    </div>
							  		</div> --}}
								
                                      <div class="form-group row">
									    <label for="payment_type" class="col-sm-4 col-form-label" style="font-weight: normal;">Payment Type<span class="text-danger"><strong>*</strong></span></label>
									    <div class="col-sm-8">
									      <select class="selectpicker" data-width="75%" data-live-search="true" aria-label="Default select example" name="payment_type" id="payment_type">
									      	<option value="option_select" disabled selected>Select Payment type</option>
							            	
                                              <option value="%">%(percentage)</option>
                                              <option value="amount">Amount</option>
									      	{{-- @foreach($stores as $store)
							            	<option value="{{ $store->store_name  }}">{{ $store->store_name  }}</option>
							        		@endforeach --}}
									      </select>
                                          <h6 class="text-danger pt-1" id="wrong_payment_type" style="font-size: 14px;"></h6>
									    </div>
							  		</div>
								  	<div class="form-group row">
								  		<div class="col-sm-10">
								  			<h6 class="text-danger float-right" ><strong id="errorMsg1"></strong></h6>
								  			<!-- <button type="reset" value="Reset" class="btn btn-outline-danger float-right ml-2" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button> -->
									  		<button type="submit"  class="btn btn-primary float-right  ml-2"></i> Save</button>  
                                              <button type="reset" value="Reset" class="btn btn-outline-danger float-right" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                        </div>
								  	</div>
								</form>
							</div> <!-- container -->
						</div> <!-- card-body -->
			  		</div> <!-- card card-primary card-outline -->
				</div> <!-- col-lg-4 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
	
</div> <!-- content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/benefit.js')}}"></script>

@endsection