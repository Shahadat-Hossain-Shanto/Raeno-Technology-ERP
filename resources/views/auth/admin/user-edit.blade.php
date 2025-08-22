@extends('layouts.master')
@section('title', 'Edit User')

@section('content')
<div class="content-wrapper" id="container-wrapper">
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
                <div class="col-lg-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong>EDIT USER</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                <form id="EditUserForm" action="" method="POST" enctype="multipart/form-data">

                                        {{ csrf_field() }}
                                        <div class="form-row">
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="name" style="font-weight: normal;">Name<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="text" class="form-control w-75" id="edit_name" name="name"
                                                placeholder="Enter Name" value="">
                                                <h6 class="text-danger pt-1" id="edit_wrongname" style="font-size: 14px;"></h6>

                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="email" style="font-weight: normal;">Email<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="text" class="form-control  w-75" id="edit_email" name="email"
                                                placeholder="Enter Email" value="">
                                                <h6 class="text-danger pt-1" id="edit_wrongemail" style="font-size: 14px;"></h6>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="password" style="font-weight: normal;">Password<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="password" class="form-control  w-75" id="edit_password" name="password"
                                                placeholder="Enter Password">
                                                <h6 class="text-danger pt-1" id="edit_wrongpassword" style="font-size: 14px;"></h6>

                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="password_confirmation" style="font-weight: normal;">Confirm Password<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="password" class="form-control w-75" id="edit_password_confirmation"
                                                    name="password_confirmation" placeholder="Confirm password">
                                                <h6 class="text-danger pt-1" id="edit_wrongpassword_confirmation" style="font-size: 14px;"></h6>

                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="contactnumber" style="font-weight: normal;">Contact Number<span class="text-danger"><strong>*</strong></span></label>
                                                <input type="text" class="form-control w-75" id="edit_contactnumber" name="contactnumber"
                                                value="">
                                                <h6 class="text-danger pt-1" id="edit_wrongcontactnumber" style="font-size: 14px;"></h6>

                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="store" style="font-weight: normal;">Assign Store<span class="text-danger"><strong>*</strong></span></label><br>
                                                <select class="selectpicker" data-live-search="true" aria-label="Default select example" name="store"
                                                  id="edit_store" title="Select store" data-width="75%">
                                                    <!-- <option value="default" selected>Select Store</option> -->
                                                    @foreach($stores as $store)
                                                    <option value="{{ $store->id }}"{{ $store->store_name ? 'selected' : '' }}>{{ $store->store_name  }}</option>
                                                    @endforeach
                                                </select>
                                                <h6 class="text-danger pt-1" id="edit_wrongstore" style="font-size: 14px;"></h6>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-6">
                                                <label for="roles" style="font-weight: normal;">Assign Roles<span class="text-danger"><strong>*</strong></span></label><br>
                                                <select name="roles[]" id="edit_roles" class="selectpicker" data-live-search="true" aria-label="Default select example"
                                                 title="Select role" data-width="75%">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}"{{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                                <h6 class="text-danger pt-1" id="edit_wrongroles" style="font-size: 14px;"></h6>

                                            </div>

                                        </div>

                                        <div class="row">
                                    <div class="col-12">
                                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                                            <button type="reset" value="Reset" class="btn btn-outline-danger mt-2 col-form-label" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                        </div>
                                    </div>
                                    </form>

                            </div> <!-- container -->
                        </div> <!-- card-body -->
                    </div> <!-- card card-primary card-outline -->
                </div> <!-- col-lg-5 -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </div> <!-- content -->

</div> <!-- content-wrapper -->
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/user-edit.js') }}"></script>

<script>
$(document).ready(function () {
    var user_id = $('#user_id').val();
    fetchUser(user_id);
});

function resetButton(){
    $('form').on('reset', function() {
        setTimeout(function() {
            $('.selectpicker').selectpicker('refresh');
        });
    });
}

</script>
@endsection
