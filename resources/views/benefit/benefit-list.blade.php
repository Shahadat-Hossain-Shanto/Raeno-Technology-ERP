@extends('layouts.master')
@section('title', 'Benefit List')

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
                            <h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> BENEFITS</strong></h5>
                        </div>
                        <div class="card-body">
                            <!-- <h6 class="card-title">Special title treatment</h6> -->
                            <!-- Table -->

                            <a href="{{url('/benefit-create')}}"><button type="button" class="btn btn-outline-info"><i
                                        class="fas fa-plus"></i> Create Benefit</button></a>


                            <div class="pt-3">
                                <table id="benefit_table" class="display" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Benefit Name</th>
                                            <th>Benefit Type</th>
                                            <th>Payment Type</th>
                                            <th>Benefit Regularity</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th class="hidden">input</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody>

                                    </tbody> -->
                                </table>
                            </div>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->

                </div> <!-- /.col-lg-6 -->
            </div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- Edit Customer Modal -->
<div class="modal fade" id="EDITBenefitMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE BENEFIT</strong></h5>
            </div>

            <form id="UPDATEBenefitFORM" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <!-- Update Customer Form -->
                {{-- <form id="UPDATEDesignationFORM" enctype="multipart/form-data"> --}}

                    {{-- <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}

                    <div class="modal-body">

                        <input type="hidden" name="benefitId" id="benefitId">


                        <label for="vatname" class="col-sm-4 col-form-label" style="font-weight: normal;">Benefit
                            Name<span class="text-danger"><strong>*</strong></span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="benefit_name" id="edit_benefit_name"
                                placeholder="Enter Designation Name">
                            <h6 class="text-danger pt-1" id="edit_wrong_benefit_name" style="font-size: 14px;"></h6>
                        </div>

                        <label for="store" class="col-sm-4 col-form-label" style="font-weight: normal;">Benefit
                            Type<span class="text-danger"><strong>*</strong></span></label>
                        <div class="col-sm-10">
                            <select class="selectpicker" data-width="100%" data-live-search="true"
                                aria-label="Default select example" name="benefit_type" id="edit_benefit_type">
                                <option value="option_select" disabled selected>Select Benefit Type</option>

                                <option value="add">Add</option>
                                <option value="deduct">Deduct</option>
                                {{-- @foreach($stores as $store)
                                <option value="{{ $store->store_name  }}">{{ $store->store_name }}</option>
                                @endforeach --}}
                            </select>
                            <h6 class="text-danger pt-1" id="edit_wrong_benefit_type" style="font-size: 14px;"></h6>
                        </div>

                        <label for="store" class="col-sm-4 col-form-label" style="font-weight: normal;">Status<span
                                class="text-danger"><strong>*</strong></span></label>
                        <div class="col-sm-10">
                            <select class="selectpicker" data-width="100%" data-live-search="true"
                                aria-label="Default select example" name="status" id="edit_status">
                                <option value="option_select" disabled selected>Select Status Type</option>

                                <option value=1>Active</option>
                                <option value=0>Inactive</option>
                                {{-- @foreach($stores as $store)
                                <option value="{{ $store->store_name  }}">{{ $store->store_name }}</option>
                                @endforeach --}}
                            </select>
                            <h6 class="text-danger pt-1" id="edit_wrong_status" style="font-size: 14px;"></h6>
                        </div>
                       <div>
                            <label for="benefit_regularity" class="col-sm-4 col-form-label" style="font-weight: normal;">Benefit Regularity<span class="text-danger"><strong>*</strong></span></label>
                            <input type="hidden" class="form-control" name="edit_benefit_regularity_check" id="edit_benefit_regularity_check"> 
                            <div class="col-sm-10">
                              <select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="benefit_regularity" id="edit_benefit_regularity">
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
                      
                         <div id="edit_yearly_allotment_div">
                            <label for="yearly_allotment" class="col-sm-4 col-form-label" style="font-weight: normal;">Yearly Allotment<span class="text-danger"><strong>*</strong></span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control w-100" name="yearly_allotment" id="edit_yearly_allotment" placeholder="Input Yearly Allotment e.g. 2">
                                <h6 class="text-danger pt-1" id="edit_wrong_yearly_allotment" style="font-size: 14px;"></h6>
                            </div>
                        </div>

                        <label for="store" class="col-sm-4 col-form-label" style="font-weight: normal;">Payment
                            type<span class="text-danger"><strong>*</strong></span></label>
                        <div class="col-sm-10">
                            <select class="selectpicker" data-width="100%" data-live-search="true"
                                aria-label="Default select example" name="payment_type" id="edit_payment_type">
                                <option value="option_select" disabled selected>Select Payment type</option>

                                <option value="%">%(percentage)</option>
                                <option value="amount">Amount</option>
                                {{-- @foreach($stores as $store)
                                <option value="{{ $store->store_name  }}">{{ $store->store_name }}</option>
                                @endforeach --}}
                            </select>
                            <h6 class="text-danger pt-1" id="edit_wrong_payment_type" style="font-size: 14px;"></h6>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
                <!-- End Update Customer Form -->

        </div>
    </div>
</div>
<!-- End Edit Customer Modal -->

<!-- Delete Modal -->

<div class="modal fade" id="DELETEBenefitMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="DELETEBenefitFORM" method="POST" enctype="multipart/form-data">

                {{ csrf_field() }}
                {{ method_field('DELETE') }}


                <div class="modal-body">
                    <input type="hidden" name="" id="delete_benefitid">
                    <h5 class="text-center">Are you sure you want to delete?</h5>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="cancel btn btn-secondary cancel_btn"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="delete btn btn-danger">Yes</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- END Delete Modal -->

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/benefit.js')}}"></script>
<script type="text/javascript">
    $(document).on('click', '#close', function (e) {
		$('#EDITBenefitMODAL').modal('hide');
		$('#edit_wrong_benefit_name').empty();
		$('#edit_wrong_benefit_type').empty();
        $('#edit_wrong_status').empty();
        $('#edit_wrong_payment_type').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEBenefitMODAL').modal('hide');
	});
</script>

@endsection