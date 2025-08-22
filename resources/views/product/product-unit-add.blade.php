@extends('layouts.master')

@section('title', 'Create Unit')

@section('content')
<div class="content-wrapper" id="body">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"></h1>
                </div><!-- /.col -->
            </div><!-- /.row mb-2 -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
               <div class="row">
                <div class="col-lg-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-balance-scale-right"></i> UNIT</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">

                                <form id="AddUnitForm" method="POST" enctype="multipart/form-data">
                                    
                                    <div class="form-group">
                                        <label for="unitname" class="form-label" style="font-weight: normal;">Unit Name<span class="text-danger"><strong>*</strong></span></label>
                                        <input type="text" class="form-control w-50" name="unitname" id="unitname" placeholder="e.g. mg, gm, kg">
                                        <h6 class="text-danger pt-1" id="wrongunitname" style="font-size: 14px;"></h6>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-label" style="font-weight: normal;">Description</label>
                                        <textarea class="form-control w-50" name="description" id="description" rows="3" placeholder="any description for the unit"></textarea>
                                    </div>
                                    
                                    
                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <button type="reset" value="Reset" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
                                    </div>
                                    
                                </form>

                            </div> <!-- container -->
                        </div> <!-- card-body -->
                    </div> <!-- card card-primary card-outline -->
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->


@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/product-unit.js') }}"></script>

<script>
    


</script>
@endsection
