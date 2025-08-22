@extends('layouts.master')
@section('title', 'General Ledger')

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
                                <h5 class="m-0"><strong><i class="fas fa-file-contract"></i>General Ledger</strong></h5>
                            </div>
                            <div class="card-body">
                                <div id="form_div">
                                    <form id="generalLedgerForm" >
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="startdate" style="font-weight: normal;">Select</label>
                                                <select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="headcode" id="headcode">
									      	    <option value="select" disabled selected>Select</option>
									      	    @foreach($heads as $head)
							            	    <option value="{{ $head->head_code }}">{{ $head->head_name }}</option>
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

                                <div id="tablePart" class="table-responsive" >
                                    <hr>
                                    {{-- <h6 class='float-right'><strong>Balance : </strong> &nbsp;&nbsp;&nbsp;<span id='debit'></span>Dr &nbsp;&nbsp;&nbsp;<span id='credit'></span>Cr &nbsp;&nbsp;&nbsp;</h6> --}}
                                    <table id="general_ledger_table" class="display" style="100%!important">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Transaction Id</th>
                                                <th>Transaction Date</th>
                                                <th>Head Code</th>
                                                <th>Head Name</th>
                                                <th>Transaction Ref</th>
                                                <th>Particulars</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                            </tr>
                                        </thead>
                                       <!--  <tbody>

                                        </tbody> -->
                                        <tfoot>
                                            <th width="27%"></th>
                                            <th width="27%"></th>
                                            <th width="27%"></th>
                                            <th width="27%"></th>
                                            <th width="27%"></th>
                                            <th width="27%"></th>
                                            <th width="27%">Total</th>
											<th width="20%"><span id='debit'></span></th>
											<th width="20%"><span id='credit'></span></th>
                                        </tfoot>
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

    <script>
        var i=1;
        $('#tablePart').hide();
        $(document).ready(function() {
            $('#generalLedgerForm').on('submit', function(e) {
                var headcode = $("#headcode").find("option:selected").val()
                // alert(headcode)
                var startdate=$('#startdate').val();
                var enddate=$('#enddate').val();

               e.preventDefault();
               if (headcode != ''|| startdate.length != 0 && enddate.length != 0) {

                   if(headcode=='select'){headcode=0}
                   if(startdate==''){
                    startdate=0
                    enddate=0
                }
                $.ajax({
                    type: "get",
                    url: "/general-ledger/"+headcode+"/"+startdate+"/"+enddate,

                    success: function(response) {
                        // console.log(response.supplier_payments);
                        var totalDebit=0;
                        var totalCredit=0;
                        response.general_ledgers.forEach(general_ledger => {
                            totalCredit = totalCredit+general_ledger.credit;
                            totalDebit = totalDebit+general_ledger.debit;
                        });
                        $('#debit').text(totalDebit.toFixed(2));
                        $('#credit').text(totalCredit.toFixed(2));
                        i=1;
                        $('#tablePart').show();
                        onChangeDataTable(response.general_ledgers,i)

                    }
                });
               }

               else {
        $.notify("Please select Transaction Date or From date and To date.", {
            className: 'error',
            position: 'bottom right'
        });

    }

            });

    function onChangeDataTable(json,i){

$('#general_ledger_table').DataTable().clear().destroy()

var t = $('#general_ledger_table').DataTable({

    data : json,

    columns: [
        { "render": function ( data, type, row, meta ){

        return i++;
        }
        },
        { "render": function ( data, type, row, meta ){

            return row.transaction_id;
        }
        },
        { "render": function ( data, type, row, meta ){

            return row.transaction_date;
            }
        },
        { "render": function ( data, type, row, meta ){

            return row.head_code;
            }
        },
        { "render": function ( data, type, row, meta ){

            return row.head_name;
            }
        },
        { "render": function ( data, type, row, meta ){

            return row.transaction_type;
            }
        },
        { "render": function ( data, type, row, meta ){

            return row.reference_note;
            }
        },
        { "render": function ( data, type, row, meta ){
            var debit = parseFloat(row.debit);
            return debit.toFixed(2);
        }
    },
    { "render": function ( data, type, row, meta ){
            var credit = parseFloat(row.credit);
            return credit.toFixed(2);
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
    // order: [[1, 'asc']],
    pageLength : 10,
    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    dom: 'Bfrtip',
    buttons: [
        // 'copy', 'csv', 'excel', 'pdf', 'print'
        {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [ 1, 2,3,4, 5,6,7,8 ]
                }
            },
            {
            extend: 'csv',
            footer: true,
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6,7,8 ]
            }
        },
            {
            extend: 'excel',
            footer: true,
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6,7,8 ]
            }
        },
        {
            extend: 'pdf',
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6,7,8 ]
            }
        },
        {
            extend: 'print',
            footer: true,
            exportOptions: {
                columns: [ 1, 2,3,4, 5,6,7,8 ]
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
    $('#headcode').val("select");
    $('.selectpicker').selectpicker('refresh');
    $.notify("Selected Transaction Date and Dates are cleared", {
        className: 'success',
        position: 'bottom right'
    });
}
    </script>
@endsection
