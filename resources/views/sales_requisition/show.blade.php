@extends('layouts.master')
@section('title', 'Requisition Details')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
           
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    @if(session('success'))
                      <div class="alert alert-success">
                          {{ session('success') }}
                      </div>
                      @endif
                      
                      @if(session('error'))
                          <div class="alert alert-danger">
                              {{ session('error') }}
                          </div>
                      @endif

                    <div class="row">
                        <!-- Left Side -->
                        <div class="col-md-6">
                            <p><strong>Distributor Name:</strong> {{ $requisition->name }}</p>
                            <p><strong>Mobile:</strong> {{ $requisition->mobile }}</p>
                            <p><strong>Address:</strong> {{ $requisition->address }}</p>
                           
                            <p><strong>Status:</strong> {{ ucfirst($requisition->sales_approved_status == 0 ? 'Pending' : ($requisition->sales_approved_status == 1 ? 'Approved' : 'Canceled')) }}</p>
                        </div>

                        
                        <!-- Right Side -->
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Requisition ID:</strong> {{ $requisition->requisition_id }}</p>
                            <p class="mb-1"><strong>Requisition Date:</strong> {{ $requisition->requisition_date }}</p>
                            <p class="mb-1"><strong>ACC Approval:</strong> {{ ucfirst($requisition->accounts_approved_status == 0 ? 'Pending' : ($requisition->accounts_approved_status== 1 ? 'Approved' : 'Canceled')) }}
                                 @if($requisition->accounts_approved_status == 1 && $requisition->accounts_approved_date)
                                    <span class="badge bg-success ms-2">
                                        {{ \Carbon\Carbon::parse($requisition->accounts_approved_date)->format('d M Y, h:i A') }}
                                    </span>
                                @endif
                            </p>
                            
                            <p class="mb-1"><strong>HOS Approval:</strong> {{ ucfirst($requisition->sales_approved_status == 0 ? 'Pending' : ($requisition->sales_approved_status== 1 ? 'Approved' : 'Canceled')) }}
                                @if($requisition->sales_approved_status == 1 && $requisition->sales_approved_date)
                                    <span class="badge bg-success ms-2">
                                        {{ \Carbon\Carbon::parse($requisition->sales_approved_date)->format('d M Y, h:i A') }}
                                    </span>
                                 @elseif($requisition->sales_approved_status == 2 && $requisition->sales_approved_date)
                                     <span class="badge bg-danger ms-2">
                                         {{ \Carbon\Carbon::parse($requisition->sales_approved_date)->format('d M Y, h:i A') }}
                                     </span>
                                @endif
                            </p>
                            
                          


                            
                        </div>

                    </div>

                    <hr>

                    <h5>Product Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>SL#</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Rebate</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->product_details }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->rate, 2) }}</td>
                                    <td>{{ number_format($detail->amount, 2) }}</td>
                                    <td>{{ number_format($detail->rebate, 2) }}</td>
                                    <td>{{ number_format($detail->total_amount, 2) }}</td>
                                </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ $requisition->quantity }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ number_format($requisition->amount, 2) }}</strong></td>
                                    <td><strong>{{ number_format($requisition->rebate, 2) }}</strong></td>
                                    <td><strong>{{ number_format($requisition->total_amount, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="mb-1"><strong>Distributor Note:</strong> {{ $requisition->requisition_note }}</p>
                    <p class="mb-1"><strong>ACC Note:</strong> {{ $requisition->accounts_approved_note }}</p>

                    @if ($requisition->sales_approved_status == 1 || $requisition->sales_approved_status == 2)                           
                    <p class="mb-1"><strong>Note:</strong> {{ $requisition->sales_approved_note }}</p>
                    @endif

                   




                    <form action="{{ route('sales-requisition.action', $requisition->id) }}" method="POST">
                        @csrf

                        @if ($requisition->sales_approved_status == 0)                           
                        <div class="form-group mt-4">
                            <label for="sales_requisition_note"><strong> Note</strong></label>
                            <textarea class="form-control" id="sales_requisition_note" name="sales_requisition_note" rows="2" placeholder="Write any note for this requisition..." required>{{ $requisition->sales_approved_note ?? '' }}</textarea>
                        </div>
                        @endif
                    
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <a href="{{ route('sales-requisition.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            
                        @if ($requisition->sales_approved_status == 0)                           
                            <div>
                                <button type="submit" name="action" value="approve" class="btn btn-success me-2">
                                    <i class="fas fa-check-circle"></i> Approve Requisition
                                </button>
                               <button type="submit" name="action" value="cancel" class="btn btn-danger" id="cancelRequisitionBtn">
                                   <i class="fas fa-times-circle"></i> Cancel Requisition
                               </button>
                            </div>
                        @endif
                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
$(document).ready(function () {
    $('#cancelRequisitionBtn').on('click', function (e) {
        if (!confirm('Are you sure you want to cancel this requisition?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

