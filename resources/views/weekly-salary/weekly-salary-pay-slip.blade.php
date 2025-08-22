@extends('layouts.master')
@section('title', 'Weekly Salary Pay Slip')

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
                            <h5 class="m-0"><strong> WEEKLY SALARY PAY SLIP</strong></h5>
                            

                        </div>

                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-info float-right btn-lg rounded-pill"  type="button" title="Print" onclick="print()"><i class="fas fa-print"></i></button>
                                        
                                    </div>
                                </div>
                                <div id="print" class="pt-4">
                                            <div class="text-center">
                                               
                                                
                                                <h6 class=""><b>{{ $sub_name}}</b></h6>
                                                <h6 class=""><b>emial: {{ $sub_email}}</b></h6>
                                                <h6 class=""><b>phone: {{ $sub_no}}</b></h6>
                                                
                                                
                                            </div>
                                    <div class="row">
                                        <div class="col-6">
                                        
                                            <div class="text-left">
                                                <h5 style="font-size: 22px; font-weight: bold;" class="">Employee Pay Summary</h5>
                                                <h6 class=""><b>Employee: </b>{{ $employee_name}}</h6>
                                                <h6 class=""><b>Designation: </b>{{ $designation}}</h6>
                                                <h6 class=""><b>Department: </b>{{ $department}}</h6>
                                                <h6 class=""><b>Pay Week: </b>{{ $payWeek}}</h6>
                                                
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                
                                              
                                                <h6 style="font-size: 20px; font-weight: ;" class=""><b>EMPLOYEE NET PAY:</b> {{$net_payment}}</h6>
                                                
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <h6 style="font-size: 18px; font-weight: ;" class=""><b>Basic Salary:</b>{{$basic_pay}}</h6>
                                        <div class="col-6">

                                            <table id="addition_table" class="table table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th colspan="1" width="5%">ADDITION</th>
                                                        <th colspan="1" width="5%">AMOUNT</th>
                                                    </tr>
                                                    
                                                </thead>
                                                <tbody>
                                                   @foreach ($benefits_add as $benefits_add)
                                                    <tr>
                                                        <td>{{ $benefits_add->name }} : </td>
                                                        <td>{{$benefits_add->amount}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                        <td></td>
                                                        
                                                    </tr>
                                                    <tr>
                                                    <td>Total : </td>
                                                    <td>{{$addition}}</td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        
                                                
                                                    </tr>
                                                    
                                                    
                                                </tfoot>
                                            </table>
                                            
                                        </div>
                                        <div class="col-6">
                                            <table id="deduction_table" class="table table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th colspan="1" width="5%">DEDUCTION</th>
                                                        <th colspan="1" width="5%">AMOUNT</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                   
                                                    <tr>
                                                        <td>Absent dedeuction : </td>
                                                        <td>{{$absentDeduction}}</td>
                                                    </tr>
                                                    @foreach ($benefits_deduct as $benefits_deduct)
                                                    <tr>
                                                        <td>{{ $benefits_deduct->name }} :</td>
                                                        <td>{{$benefits_deduct->amount}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td>Total after deduction: </td>
                                                        <td>{{$total_deductions}}</td>
                                                       
                                                    </tr>
                                                   
                                                </tfoot>
                                            </table>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h6 style="font-size: 15px; font-weight: ;" class="">Total Earning <b></b></h6>
                                                <h6 style="font-size: 15px; font-weight: ;" class="">{{$total_earning}}</h6>
                                                
                                                <h6 style="font-size: 15px; font-weight: ;" class="pt-2">Total Deduction <b></b></h6>
                                                <h6 style="font-size: 15px; font-weight: ;" class="">{{$total_deductions}}</h6>
                                                <div class="row">
                                                    <div class="col-6">

                                                    </div>
                                                    <div class="col-6">
                                                        <hr style="color: ;" size="1" width="100%">
                                                    </div>
                                                </div>
                                                <h6 style="font-size: 15px; font-weight: ;" class="">Net Pay <b></b></h6>
                                                <h6 style="font-size: 15px; font-weight: ;" class="">{{$net_payment}}</h6>
                                                
                                                
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <hr size="1" width="100%">
                                   
                                    <h6><b>Employee Signature   &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;Paid By</b></h6> 
                                           
                                      
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