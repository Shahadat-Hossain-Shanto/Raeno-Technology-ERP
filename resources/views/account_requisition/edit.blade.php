@extends('layouts.master')
@section('title', 'Edit Distributor Requisition')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid"></div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-user-plus"></i> Edit Distributor Requisition</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">

                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> Please fix the following issues:
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('distributor_requisition.update', $requisition->id) }}" method="POST" id='requisitionForm'>
                                    @csrf
                                    @method('put')

                                    <!-- Distributor dropdown -->
                                    <div class="col-sm-8 mb-3" id="distributorSection">
                                        <select class="selectpicker form-control" data-width="100%" data-live-search="true"
                                            name="distributorRequisition" id="distributorRequisition" required>
                                            
                                            <option value="" disabled {{ !$requisition->distributor_id ? 'selected' : '' }}>Select Distributor</option>

                                            @foreach($distributors as $distributor)
                                                <option value="{{ $distributor->id }}" {{ $distributor->id == $requisition->distributor_id ? 'selected' : '' }}>
                                                    {{ $distributor->distributor_name }} ({{ $distributor->contact_no }})
                                                </option>

                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Product and other fields (initially hidden) -->
                                    <div id="productSection" >
                                       <div class="form-group row">
                                            <div class="col-3">
                                                <label>Product</label>
                                                <select class="product selectpicker form-control" id="product" name='product' data-live-search="true">
                                                    <option value="" disabled selected>Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->productName }} - {{ $product->model }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label>Variant</label>
                                                <select class="selectpicker form-control" id="variant" name="variant" data-live-search="true">
                                                    <option value="" disabled selected>Select Variant</option>
                                                </select>

                                            </div>

                                            <div class="col-3">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity" min="1" >
                                            </div>
                                            
                                            {{-- <div class="col-3">
                                                <label for="unit_price">Rate</label>
                                                <input type="number" step="0.01"  name="unit_price" id="unit_price" class="form-control" placeholder="Enter rate" required>
                                            </div> --}}

                                            <div class="col-3">
                                                <label for="dealer_cost">Rate</label>
                                                <input type="number" step="0.01" name="dealer_cost" id="dealer_cost" class="form-control" placeholder="" readonly>

                                            </div>

                                             <div class="col-2">
                                                <label for="rate">Amount</label>
                                                <input type="number" step="0.01" name="rate" id="rate" class="form-control" placeholder="" readonly>

                                            </div>
                                            
                                            <div class="col-6">
                                                <label for="rebat">Rebat</label>
                                                <div class='d-flex justify-content-center'>
                                                       <select name="rebat" id="rebat" class="form-control selectpicker" data-live-search="false">
                                                       <option disabled selected>Select Rebat Type</option>
                                                       <option value="percentage">Percentage</option>
                                                       <option value="fixed">Fixed Amount</option>
                                                   </select>
                                                   <div class="col-6" id="rebatValueWrapper" style="display: none;">
                                                       <input type="number" step="0.01" name="rebat_value" id="rebat_value" class="form-control" placeholder="">
                                                   </div>
                                                </div>
                                                
                                            </div>
                                            

                                            <div class="col-2" >
                                                <label for="total_amount">Total Amount</label>
                                                <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                                            </div>
                                             
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" id="addProductBtn" class="btn btn-success">
                                                    <i class="fas fa-plus-circle"></i> Add Product
                                                </button>
                                            </div>



                                            <div class='pt-5 table-responsive'>
                                                <h4> List of Products</h4>
                                            <table id="requisition_table" class="table">
									          <thead>
									            <tr>
									              <th scope="col">Name of the Product</th>
									              <th scope="col">Quantity</th>
									              <th scope="col">Rate</th>
									              <th scope="col">Amount</th>
									              <th scope="col">Rebat</th>
									              <th scope="col">Amount</th>
									              <th scope="col">Action</th>
									            </tr>
									          </thead>
									          <tbody>
                                                                                        
        
									          </tbody>
                                               <tfoot>
                                                   <tr>
                                                       <th>Total</th>
                                                       <th id="totalQty">0</th>
                                                       <th></th>
                                                       <th id="totalAmount">0.00</th>
                                                       <th id="totalRebate">0.00</th>
                                                       <th id="grandTotal">0.00</th>
                                                       <th></th>
                                                   </tr>
                                               </tfoot>
									        </table>
                                            </div>
                                            
                                        </div>

                                        <div class="form-group mt-4">
                                            <label for="requisition_note"><strong>Requisition Note</strong></label>
                                            <textarea class="form-control" id="requisition_note" name="requisition_note" rows="2" placeholder="Write any note for this requisition...">{{ $requisition->requisition_note }}</textarea>

                                        </div>

                                        <div class="form-group mt-4 d-flex justify-content-end " id="btnSection">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Save Requisition
                                            </button>
                                            <a href="{{route('account-requisition.index')}}" class="btn btn-secondary ms-2">
                                                <i class="fas fa-arrow-left"></i> Cancel
                                            </a>
                                        </div>

                                    </div>
                                   
                                </form>
                            </div> <!-- /.container -->
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {  
    // $('#distributorRequisition').on('change', function () {
    // const selectedVal = $(this).val();   
    // if (selectedVal) {
    //     $('#distributorSection').hide();       
    //     $('#productSection').show();
    // }
    // });
   
   const distributorId = '{{ $requisition->distributor_id }}';
    $('#distributorRequisition')
        .val(distributorId)
        .selectpicker('render')
        .selectpicker('refresh');

  
    $('#product').on('change', function() {
        let productId = $(this).val();

        if (productId) {
            $.ajax({
                url: '/get-variants/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#variant').empty().append(
                        '<option disabled selected>Select Variant</option>');

                    $.each(data, function(index, variant) {
                        $('#variant').append(
                            '<option value="' + variant.id + '">' + variant.variant_name + '</option>'
                        );
                    });
                    $('#variant').selectpicker('refresh'); 
                },
                error: function() {
                    alert('Failed to load variants.');
                }
            });
        } else {
            $('#variant').empty().append('<option disabled selected>Select Variant</option>');
            $('#variant').selectpicker('refresh');
        }
    });

    $('#variant').on('change', function () {
    let variantId = $(this).val();

    if (variantId) {
        $.ajax({
            url: '/get-pricing/' + variantId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#dealer_cost').val(data.dealer_cost); 
                calculateTotalAmount(); 
            },
            error: function () {
                alert('Failed to fetch pricing info.');
            }
        });
    }
});


    $('#quantity, #rebat_value').on('input', function () {
    calculateTotalAmount();
    });


     $('#rebat').on('change', function () {
    // Existing label/placeholder update logic
    let selected = $(this).val();  
    let $input = $('#rebat_value');
    let $wrapper = $('#rebatValueWrapper');

    if (selected === 'percentage') {    
        $input.attr('placeholder', 'e.g. 10 for 10%');
        $wrapper.show();
    } else if (selected === 'fixed') {     
        $input.attr('placeholder', 'e.g. 100 for ৳100 discount');
        $wrapper.show();
    } else {
        $wrapper.hide();
    }

    calculateTotalAmount(); 
    });


    function getCalculationDetails() {
    let quantity = parseFloat($('#quantity').val());
    let dealerCost = parseFloat($('#dealer_cost').val());
    let rebatType = $('#rebat').val();
    let rebatValue = parseFloat($('#rebat_value').val());

    if (isNaN(quantity) || isNaN(dealerCost)) {
        return null;
    }

    let subtotal = quantity * dealerCost;
    let rebatAmount = 0;

    if (rebatType === 'percentage' && !isNaN(rebatValue)) {
        rebatAmount = (subtotal * rebatValue) / 100;
    } else if (rebatType === 'fixed' && !isNaN(rebatValue)) {
        rebatAmount = rebatValue;
    }

    let total = subtotal - rebatAmount;

    return {
        quantity,
        dealerCost,
        subtotal,
        rebatAmount,
        total
    };
}


function calculateTotalAmount() {
    const result = getCalculationDetails();

    if (!result) {
        $('#rate').val('');
        $('#total_amount').val('');
        return;
    }

    $('#rate').val(result.subtotal.toFixed(2));
    $('#total_amount').val(result.total.toFixed(2));
}



const requisitionDetails = @json($requisitionDetails);
requisitionDetails.forEach(item => {
    $('#requisition_table tbody').append(`
        <tr data-product="${item.product_id}" data-variant="${item.variant_id}">
            <td>${item.product_details}</td>
            <td>${item.quantity}</td>
            <td>${parseFloat(item.rate).toFixed(2)}</td>
            <td>${parseFloat(item.amount).toFixed(2)}</td>
            <td>${parseFloat(item.rebate).toFixed(2)}</td>
            <td>${parseFloat(item.total_amount).toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete-row">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
            <td style="display:none">
                <input type="hidden" name="productDetails[]" value="${item.product_details}">
                <input type="hidden" name="products[]" value="${item.product_id}">
                <input type="hidden" name="variants[]" value="${item.variant_id}">
                <input type="hidden" name="quantities[]" value="${item.quantity}">
                <input type="hidden" name="rates[]" value="${parseFloat(item.rate).toFixed(2)}">
                <input type="hidden" name="subtotals[]" value="${parseFloat(item.amount).toFixed(2)}">
                <input type="hidden" name="rebates[]" value="${parseFloat(item.rebate).toFixed(2)}">
                <input type="hidden" name="rebat_types[]" value="${item.rebat_type ?? ''}">
                <input type="hidden" name="totals[]" value="${parseFloat(item.total_amount).toFixed(2)}">
            </td>
        </tr>
    `);
});

updateTableTotals();

$(document).on('click', '.delete-row', function () {
    $(this).closest('tr').remove();
    updateTableTotals();
});

$('#addProductBtn').on('click', function () {
    let productId = $('#product').val();
    let productName = $('#product option:selected').text();
    let variantId = $('#variant').val();
    let variantName = $('#variant option:selected').text();
    let rebatType = $('#rebat').val();
    let rebatValue = parseFloat($('#rebat_value').val()) || 0;

    const result = getCalculationDetails();

    if (!productId || !variantId || !result || isNaN(result.rebatAmount)) {
        alert('Please fill all required fields.');
        return;
    }

    let found = false;

    $('#requisition_table tbody tr').each(function () {
        const rowProductId = $(this).data('product');
        const rowVariantId = $(this).data('variant');

        console.log(rowProductId,rowVariantId);
    
        if (rowProductId == productId && rowVariantId == variantId) {
            // Merge logic here
            let existingQty = parseFloat($(this).find('td:eq(1)').text());
            let newQty = existingQty + result.quantity;
            let newSubtotal = newQty * result.dealerCost;
    
            let newRebateAmount = 0;
            if (rebatType === 'percentage') {
                newRebateAmount = (newSubtotal * rebatValue) / 100;
            } else if (rebatType === 'fixed') {
                newRebateAmount = rebatValue;
            }
    
            let newTotal = newSubtotal - newRebateAmount;
    
            $(this).find('td:eq(1)').text(newQty);
            $(this).find('td:eq(3)').text(newSubtotal.toFixed(2));
            $(this).find('td:eq(4)').text(newRebateAmount.toFixed(2));
            $(this).find('td:eq(5)').text(newTotal.toFixed(2));
    
            $(this).find('input[name="quantities[]"]').val(newQty);
            $(this).find('input[name="subtotals[]"]').val(newSubtotal.toFixed(2));
            $(this).find('input[name="rebates[]"]').val(newRebateAmount.toFixed(2));
            $(this).find('input[name="totals[]"]').val(newTotal.toFixed(2));
            $(this).find('input[name="rebat_types[]"]').val(rebatType);
    
            found = true;
            return false; // Break loop
        }
    });


    if (!found) {
        let newRow = `
        <tr data-product="${productId}" data-variant="${variantId}">
            <td>${productName} (${variantName})</td>
            <td>${result.quantity}</td>
            <td>${result.dealerCost.toFixed(2)}</td>
            <td>${result.subtotal.toFixed(2)}</td>
            <td>${result.rebatAmount.toFixed(2)}</td>
            <td>${result.total.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete-row">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
            <td style="display:none">
                <input type="hidden" name="productDetails[]" value="${productName} (${variantName})">
                <input type="hidden" name="products[]" value="${productId}">
                <input type="hidden" name="variants[]" value="${variantId}">
                <input type="hidden" name="quantities[]" value="${result.quantity}">
                <input type="hidden" name="rates[]" value="${result.dealerCost.toFixed(2)}">
                <input type="hidden" name="subtotals[]" value="${result.subtotal.toFixed(2)}">
                <input type="hidden" name="rebates[]" value="${result.rebatAmount.toFixed(2)}">
                <input type="hidden" name="rebat_types[]" value="${rebatType}">
                <input type="hidden" name="totals[]" value="${result.total.toFixed(2)}">
            </td>
        </tr>
        `;
        $('#requisition_table tbody').append(newRow);
    }

    updateTableTotals();

    // Reset form inputs
    $('#quantity').val('');
    $('#rate').val('');
    $('#dealer_cost').val('');
    $('#product').val('').selectpicker('refresh');
    $('#variant').val('').selectpicker('refresh');
    $('#rebat').val('').selectpicker('refresh');
    $('#rebat_value').val('');
    $('#total_amount').val('');
    $('#rebatValueWrapper').hide();
});


// function updateTableTotals() {
//     let totalQty = 0;
//     let totalAmount = 0;
//     let totalRebate = 0;
//     let grandTotal = 0;

//     $('#requisition_table tbody tr').each(function () {
//         totalQty += parseFloat($(this).find('td:eq(1)').text()) || 0;
        
//         totalAmount += parseFloat($(this).find('td:eq(3)').text()) || 0;
//         totalRebate += parseFloat($(this).find('td:eq(4)').text()) || 0;
//         grandTotal += parseFloat($(this).find('td:eq(5)').text()) || 0;
//     });

//     $('#totalQty').text(totalQty);
    
//     $('#totalAmount').text(totalAmount.toFixed(2));
//     $('#totalRebate').text(totalRebate.toFixed(2));
//     $('#grandTotal').text(grandTotal.toFixed(2));
// }
// function updateTableTotals() {
//     let totalQty = 0;
//     let totalAmount = 0;
//     let totalRebate = 0;
//     let grandTotal = 0;

//     $('#requisition_table tbody tr').each(function () {
//         let qty = parseFloat($(this).find('td:eq(1)').text()) || 0;
//         let amount = parseFloat($(this).find('td:eq(3)').text()) || 0;
//         let rebate = parseFloat($(this).find('td:eq(4)').text()) || 0;
//         let total = parseFloat($(this).find('td:eq(5)').text()) || 0;

//         totalQty += qty;
//         totalAmount += amount;
//         totalRebate += rebate;
//         grandTotal += total;
//     });

//     // Update totals in footer
//     $('#totalQty').text(totalQty.toFixed(0));
//     $('#totalAmount').text(totalAmount.toFixed(2));
//     $('#totalRebate').text(totalRebate.toFixed(2));
//     $('#grandTotal').text(grandTotal.toFixed(2));
// }
function updateTableTotals() {
    let totalQty = 0;
    let totalAmount = 0;  
    let totalRebate = 0;
    let grandTotal = 0;

    $('#requisition_table tbody tr').each(function () {
        const qty = parseFloat($(this).find('td:eq(1)').text()) || 0;
        const amount = parseFloat($(this).find('td:eq(3)').text()) || 0;
        const rebate = parseFloat($(this).find('td:eq(4)').text()) || 0;
        const total = parseFloat($(this).find('td:eq(5)').text()) || 0;

        totalQty += qty;
        totalAmount += amount;
        totalRebate += rebate;
        grandTotal += total;
    });

    $('#totalQty').text(totalQty.toFixed(0));
    $('#totalAmount').text(totalAmount.toFixed(2));
    $('#totalRebate').text(totalRebate.toFixed(2));
    $('#grandTotal').text(grandTotal.toFixed(2));
}

});
</script>
