@extends('layouts.master')
@section('title', 'Salaries')

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
                            <h5 class="m-0"><strong> SALARIES</strong></h5>

                        </div>

                        <div class="card-body">
                            <!-- <h6 class="card-title">Special title treatment</h6> -->
                            <!-- Table -->
                            
                            <a href="{{url('/salary-add')}}"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Salary</button></a>
                            
                            
                            <div class="pt-3">
                                                    <table id="salary_table" class="display" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Grade Name</th>
                                                                <th>Salary Type</th>
                                                                <th>Basic Pay</th>
                                                                <th>Addition</th>
                                                                <th>Diduction</th>
                                                                <th>Gross Salary</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <!-- <tbody>
    
                                                        </tbody> -->
                                                    </table>
                                                </div>
    
                          </div> <!-- Card-body -->
                    </div> <!-- card card-primary card-outline -->
                </div> <!-- col-lg-5 -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </div> <!-- content -->

</div> <!-- content-wrapper -->



<!-- Delete Modal --> 

<div class="modal fade" id="DELETESalaryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

          <form id="DELETESalaryFORM" method="POST" enctype="multipart/form-data">

                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
              

              <div class="modal-body"> 
                  <input type="hidden" name="" id="salaryid"> 
                <h5 class="text-center">Are you sure you want to delete?</h5>
              </div>

              <div class="modal-footer justify-content-center">
                  <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="delete btn btn-outline-danger">Yes</button>
              </div>

          </form>

      </div>
  </div>
</div>

<!-- END Delete Modal -->
@endsection

@section('script')

<script type="text/javascript" src="{{asset('js/salary-list.js')}}"></script>
<script type="text/javascript">

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETESalaryMODAL').modal('hide');
	});
</script>

@endsection