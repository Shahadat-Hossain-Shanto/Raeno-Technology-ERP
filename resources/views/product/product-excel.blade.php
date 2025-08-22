@extends('layouts.master')
@section('title', 'Products')

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
		                	<h5 class="m-0"><strong>PRODUCTS</strong></h5>
		              </div>
		              <div class="card-body">
		              	<form action="{{ route('product-excel-import') }}" method="POST" enctype="multipart/form-data">
						            @csrf
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="addORin" class="form-label" style="font-weight: normal;">Product Add or In<span class="text-danger"><strong>*</strong></span></label><br>
											<select style="width:70%" data-width="100%" class="selectpicker"  aria-label="Default select example" name="addORin" id="addORin" title="Product Add or In">
												<option value=""disabled>Select</option>
											    <option value="add">Add Product</option>
											    <option value="in" id="in">Product In</option>
											</select>
                                        </div>
                                        <div class="col-2" id="productIn" hidden>
                                            <label for="store" class="form-label" style="font-weight: normal;">Store</label><br>
											<select style="width:70%" data-width="100%" class="selectpicker"  aria-label="Default select example" name="store" id="store" title="Store">
												<option value=""disabled>Select</option>
											    <option value="0">Warehouse</option>
											    @foreach($stores as $store)
											    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
											    @endforeach
											</select>
                                        </div>
                                    </div>
                                    <br>
						            <div class="row">
						            	<div class="col-4">
						            		<div class="form-group mb-4">
								                <div class="text-left">
								                    <input type="file" name="file" class="form-control w-75" id="customFile">
								                    @if($errors->any())
														<h6 class="text-danger pt-2"><b>{{$errors->first()}}</b></h6>
													@endif
								                </div>
								            </div>
						            	</div>
						            </div>

                                    <div class="row">
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-primary">Import Products</button>
                                            <!-- <a class="btn btn-info" href="{{ route('product-excel-export') }}">Export Products</a> -->
                                        </div>
                                        <div class="col-7"></div>
                                        <div class="col-3" id="demo">
                                            <a class="btn btn-info float-right" href="/product-excel-demo">Download Demo XL File</a>
                                        </div>
                                        <div class="col-3" id="addProduct" hidden>
                                            <a class="btn btn-info float-right" href="/product-excel-addProduct">Download Product Add XL File</a>
                                        </div>
                                        <div class="col-3" id="stockIn" hidden>
                                            <a class="btn btn-info float-right" href="/product-excel-productIn">Download Product XL File For Stock In</a>
                                        </div>
                                    </div>
                                </form>

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->

		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/product-excel.js"></script>
<script type="text/javascript">
$('#addORin').change(function (e) {
    if($('#addORin').val() == 'in'){
        $( "#demo" ).attr("hidden",true);
        $( "#productIn" ).removeAttr("hidden");
        $( "#stockIn" ).removeAttr("hidden");
        $( "#addProduct" ).attr("hidden",true);
    }
    else{
        $( "#demo" ).attr("hidden",true);
        $( "#productIn" ).attr("hidden",true);
        $( "#stockIn" ).attr("hidden",true);
        $( "#addProduct" ).removeAttr("hidden");
    }
});
</script>

@endsection



