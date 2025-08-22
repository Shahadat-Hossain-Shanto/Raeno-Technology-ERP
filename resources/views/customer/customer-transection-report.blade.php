@extends('layouts.master')
@section('title', 'Customer Transection Reports')

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
                    <div class="col-lg-8">

                        <div class="card card-primary">
                            <div class="card-header">
                                <h5 class="m-0"><strong><i class="fas fa-file-contract"></i> CUSTOMER TRANSECTION
                                        REPORT</strong></h5>
                            </div>
                            <div class="card-body">
                                <div id="form_div">
                                    <form id="customerTransectionReportForm" >
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="customer_head_code" style="font-weight: normal;">Customer</label><br>
                                                <select class="selectpicker" name="customer_head_code" id="customer_head_code"
                                                    data-live-search="true" data-width="100%">
                                                    <option value=""selected disabled> Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="startdate" style="font-weight: normal;">From</label>
                                                <input type="date" class="form-control" id="startdate" name="startdate">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="enddate" style="font-weight: normal;">To</label>
                                                <input type="date" class="form-control" id="enddate" name="enddate">
                                            </div>

                                            <div style="padding-top: 32px;" class="form-group col-md-3">
                                                <button type="submit" class="btn btn-primary"
                                                    id="gen_btn">Generate</button>
                                                <button id="reset_btn" type="button" class=" w-30 btn btn-outline-danger"
                                                    onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>



                                <div id="tablePart" >
                                    <hr>
                                     <div class="form-group row">
                                        <div class='col-2'>
                                            <label for="customerName" style="font-weight: normal;">Name : </label>
                                        </div>
                                        <div class='col-4'>
                                            <input type="text" class="form-control" id="customerName" name="customerName" disabled>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <div class='col-2'>
                                        <label for="mobile" style="font-weight: normal;">Mobile : </label>
                                        </div>
                                        <div class='col-4'>
                                        <input type="text" class="form-control" id="mobile" name="mobile" disabled>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <div class='col-2'>
                                        <label for="address" style="font-weight: normal;">Address : </label>
                                        </div>
                                        <div class='col-4'>
                                        <input type="text" class="form-control" id="address" name="address" disabled>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="pb-3"><strong><u>Transection History</u></strong></h5>
                                    <table id="customer_table" class="display" style="100%!important">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Transaction Date</th>
                                                <th>Paid Amount</th>
                                                <th>Due Amount</th>
                                                <th>Payment Type</th>
                                                <th>Referance</th>

<!-- 										    <th>Note</th>
                                                <th>Store</th> -->
                                                <th>Balance</th>

                                            </tr>
                                        </thead>
                                       <!--  <tbody>

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

@endsection

@section('script')
    {{-- <script type="text/javascript" src="js/product-in-report.js"></script> --}}
    <script>
        var i=1;
        $('#tablePart').hide();
        $(document).ready(function() {
            $('#customerTransectionReportForm').on('submit', function(e) {


            var customer_head_code = $('#customer_head_code').find(":selected").val();
               var startdate=$('#startdate').val();
               var enddate=$('#enddate').val();
               e.preventDefault();
               if (customer_head_code !=''&& startdate.length != 0 && enddate.length != 0) {

                         $.ajax({
                    type: "get",
                    url: "/customer-transection-report-data/"+customer_head_code+"/"+startdate+"/"+enddate,

                    success: function(response) {
                        console.log(response);
                        $('#customerName').val(response.customer.name);
                        $('#mobile').val(response.customer.mobile);
                        $('#address').val(response.customer.address);
                        i=1;
                        $('#tablePart').show();
                        onChangeDataTable(response.data,i)

                    }
                });
               }

               else {
        $.notify("Please select Supplier Name, From date and To date.", {
            className: 'error',
            position: 'bottom right'
        });

    }

            });

    function onChangeDataTable(json,i){

$('#customer_table').DataTable().clear().destroy()

var t = $('#customer_table').DataTable({

    data : json,

    columns: [
        { "render": function ( data, type, row, meta ){

        return i++;
        }
        },
        { "render": function ( data, type, row, meta ){

            return row.deposit_date;
            }
        },
        { "render": function ( data, type, row, meta ){
            var deposit = parseFloat(row.deposit);
            return deposit.toFixed(2);
            }
        },
        { "render": function ( data, type, row, meta ){
            var due = parseFloat(row.due);
            return due.toFixed(2);
        }
},
        { "render": function ( data, type, row, meta ){

            return row.deposit_type;
        }
    },
    { "render": function ( data, type, row, meta ){

        if(row.order_id==0){
            return 'Customer Deposit';
        }
        else{
            return row.order_id;
        }
        }
    },
    { "render": function ( data, type, row, meta ){
            var balance = parseFloat(row.balance);
            if(balance < 0){
                return -1*balance.toFixed(2) + ' Cr';
            }
            else{
                return balance.toFixed(2) + ' Dr';
            }
        }
    },
    ],
    columnDefs: [
        {
            searchable: true,
            orderable: true,
            targets: 0,
        },
    ],
    bAutoWidth: false,
    order: [[1, 'asc']],
    pageLength : 10,
    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    dom: 'Bfrtip',
    buttons: [
        // 'copy', 'csv', 'excel', 'pdf', 'print'
        {
                extend: 'copy',
                exportOptions: {
                    columns: [ 1, 2,3,4, 5,6 ]
                }
            },
            {
            extend: 'csv',
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6 ]
            }
        },
            {
            extend: 'excel',
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6 ]
            }
        },
        {
            extend: 'pdf',
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6 ]
            }
        },
        {
            extend: 'print',
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6 ]
            }
        },


    ]

});


}

        });

function resetButton(){

    $('#tablePart').hide();

    // $("input[type=date][name$=TerminationDate]").val('');
    $("#startdate").val("");
    $("#enddate").val("");
    $('#customer_head_code').val("");
    $('.selectpicker').selectpicker('refresh');
    $.notify("Selected Customer and Dates are cleared", {
        className: 'success',
        position: 'bottom right'
    });
}
    </script>
@endsection
