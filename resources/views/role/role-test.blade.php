@extends('layouts.master')
@section('title', 'Role Index')


<div class="content-wrapper" id="container-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content pt-4 ">
		<div class="container-fluid ">
			<div class="row">
      			<div class="col-lg-12">
          			<div class="card card-primary card-outline ">
			            <div class="card-header">
@hasanyrole('admin|superadmin')
			<h1>Hello admin/super admin {{auth()->user()->name }}</h1> 
		    @endhasanyrole

                        </div>
                      </div>
                  </div>
            </div>
        </div>
    </div>
</div>