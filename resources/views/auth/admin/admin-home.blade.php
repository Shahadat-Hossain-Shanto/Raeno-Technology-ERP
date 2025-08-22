@extends('auth.admin.master')

@section('title', 'Home')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Home Header</h1>
          </div><!-- /.col -->  
        </div><!-- /.row mb-2 -->
      </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
          @hasanyrole('admin')
          <h1>Hello admin {{auth()->user()->name }}</h1> 
            @endhasanyrole

            @hasanyrole('manager')
            <h1>Hello editor {{auth()->user()->name }}</h1> 
              @endhasanyrole

        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection