<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('dataTable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('select2/js/select2.min.js') }}"></script>
    <title>Subscriber List</title>
  </head>
  <body>
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
                            <div class="row">
                                <div class="col-11 ">
                                    <h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i>  Subscriber List</strong></h5>
                                </div>
                                <div class="col-1 ">
                                    <a class="btn btn-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                        </div>
                                </div>







                          </div>
                          <div class="card-body">
                            <!-- <h6 class="card-title">Special title treatment</h6> -->
                            <!-- Table -->




                            <div class="pt-3">
                                <div class="table-responsive">
                                    <table id="subscriber_table" class="display" width="100%">
                                        <thead>
                                            <tr>
                                                <th >Organization Name</th>
                                                <th >Owner Name</th>
                                                <th width="10%">Contact No</th>
                                                <th width="10%">Email</th>
                                                <th width="10%">address</th>
                                                <th >Registration Type</th>
                                                <th >Package</th>
                                                <th >Branch</th>
                                                <th >Registration Date & Time</th>
                                                <th >Status</th>
                                                <th >Subscriber Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                          </div> <!-- Card-body -->
                        </div>	<!-- Card -->

                    </div>   <!-- /.col-lg-6 -->
                </div><!-- /.row -->

                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Delete Subscriber?</h5>
                          <button type="button" class="close exit" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <input type="hidden" id="deleteValue" >
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="info_table" class="display table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Organization Name</th>
                                            <th>Owner Name</th>
                                            <th>Contact No</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="exit btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-danger deleteConfirmed">Delete</button>
                        </div>
                      </div>
                    </div>
                  </div>
            </div> <!-- container-fluid -->
        </div> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>
<script src="{{ asset('dataTable/datatables.min.js') }}"></script>
<script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('dataTable/JSZip-2.5.0/jszip.min.js') }}"></script>
<script src="{{ asset('dataTable/pdfmake-0.1.36/pdfmake.js') }}"></script>
<script src="{{ asset('dataTable/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script src="{{ asset('dataTable/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('dataTable/Responsive-2.2.9/js/dataTables.responsive.min.js') }}"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
<script type="text/javascript" src="{{ URL::asset('js/subs_active.js') }}"></script>


<script>
  $( document ).ready(function() {
    //console.log( "ready!" );
    showData()

});

</script>

</body>
</html>
