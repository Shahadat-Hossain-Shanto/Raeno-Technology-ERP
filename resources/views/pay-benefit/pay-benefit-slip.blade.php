@extends('layouts.master')
@section('title', 'Pay Benefit Slip')

@section('content')
<div class="content-wrapper" id="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <!-- Header -->
            </div>
        </div>
    </div>

    <div class="content" style="font-family: ;">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong> PAY BENEFIT SLIP</strong></h5>

                        </div>

                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-info float-right btn-lg rounded-pill"  type="button" title="Print" onclick="print()"><i class="fas fa-print"></i></button>
                                        
                                    </div>
                                </div>
                                <div id="print" class="pt-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-left">
                                                <h5 style="font-size: 22px; font-weight: bold;" class="">Benefit Pay Summary</h5>
                                                <h6 class=""><b>Employee: </b>{{ $data1->employee_name}}</h6>
                                                <h6 class=""><b>Designation: </b>{{ $data1->designation}}</h6>
                                                <h6 class=""><b>Department: </b>{{ $data1->department}}</h6>
                                                <h6 class=""><b>Pay Year: </b>{{ $data1->year}}</h6>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row pt-3">
                                        
                                        <div class="col-6">

                                            <table id="addition_table" class="table table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">SL</th>
                                                        <th colspan="2">Particular Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach($data2 as $item)
                                                    <tr>
                                                        <td>{{ $x = 1}}</td>
                                                        <td>{{ $item->benefit_name}}</td>
                                                        <td>{{ number_format($item->amount, 2, ".", "") }}</td>
                                                    </tr>

                                                    @php
                                                    $x++
                                                    @endphp
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    
                                                    
                                                    
                                                </tfoot>
                                            </table>
                                            
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-6">
                                            <div class="text-left">
                                                <h6 style="font-size: 15px; font-weight: ;" class="">Total Amount <b>{{ number_format($total, 2, ".", "") }}</b></h6>
                                               
                                                <div class="row">
                                                    <div class="col-6">
                                                        <hr style="color: ;" size="1" width="100%">
                                                    </div>
                                                </div>
                                                <h6 style="font-size: 15px; font-weight: ;" class="">Net Pay <b>{{ number_format($total, 2, ".", "") }}</b></h6>
                                                
                                                
                                                
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <hr size="1" width="100%">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6><b>Employee Signature</b></h6>
                                            <h6 class="pt-3"><b>Paid By</b></h6>
                                        </div>
                                    </div>
                                </div>
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

<script type="text/javascript" src=""></script>
<script type="text/javascript">
    function print(){
        $.print("#print");
    }

</script>

@endsection