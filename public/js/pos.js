$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.ajax({

		type: "GET",
		url: "/product-categories",
		dataType: "json",
		success: function(response){
            // console.log(response);
			$("#next").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)+1))
			$("#prev").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)))


			categories(response.categories);

			$.each(response.products.data, function(key, item) {

				if(item.productImage == null){
					productImage = "default.jpg"
				}else{
					productImage = item.productImage
				}

				if(item.category_image == null){
					category_image = "default.jpg"
				}else{
					category_image = item.category_image
				}
                if(item.variant_name == 'default'){
					variant_name = "Default"
				}else{
					variant_name = item.variant_name
				}

                if (item.variant_image == null){
                    var src = "uploads/categories/" +category_image
				}else{
                    var src = "uploads/variants/" + item.variant_image
				}
				if(item.onHand < 1){
					$("#products").append('\
					<div class="col-md-2 clearfix d-none d-sm-block">\
		              	<div class="card card-primary mb-2">\
			              	<button class="add-btn" data-value="'+item.variant_id+'"  value="'+item.id+'" disabled>\
								<center style="margin-top:1rem; overflow: hidden;">\
				                <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover; opacity: 0.5;" src="'+src+'" alt="'+item.productName+'">\
								</center>\
				                <div class="card-body" style="padding: 0.25rem;" >\
					                <h5 style="font-size: small;" class="pos-card-text-title wordWrap" data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
					                <p style="font-size: small;" class="pos-card-text-body text-danger"><strong>Stock out</strong></p>\
				                </div>\
				            </button>\
				        </div>\
				    </div>');
				}else{
					$("#products").append('\
					<div class="col-md-2 clearfix d-none d-sm-block">\
		              	<div class="card card-primary mb-2">\
			              	<button class="add-btn"  data-value="'+item.variant_id+'" value="'+item.id+'">\
                                <center style="margin-top:1rem; overflow: hidden;">\
				                <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover;" src="'+src+'" alt="'+item.productName+'">\
                                </center>\
				                <div class="card-body" style="padding: 0.25rem;" >\
					                <h5 style="font-size: small;" class="pos-card-text-title wordWrap"  data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
					                <p style="font-size: small;" class="pos-card-text-body">In Stock: <span>'+item.onHand+'</span></p>\
				                </div>\
				            </button>\
				        </div>\
				    </div>');
				}


			})
		}
	});

})


// ---------------------------------------------------------------   CATEGORIES   -------------------------------------------------------------------
function categories(data){

	count = data.length;
	divs = Math.ceil(count/6);

	for(i=0; i<divs-1; i++){
		$("#slides").append('\
			<div class="carousel-item" >\
	          <div class="row" style="text-align:center;" id="categories'+i+'">\
	          </div>\
	        </div>');
	}


	$.each(data, function(key, item) {
		if(key < 6){
			$("#categories").append('\
			<div class="col-md-2">\
          		<div class="category-card card card-primary card-outline mb-2">\
                	<div class="card-body" style="padding: 1rem 0rem 1rem 0rem;">\
						<center>\
							<p style="font-size: medium; margin-bottom: auto;" class="pos-card-text-body"><span><b>'+item.category_name+'</b></span></p>\
						</center>\
	                	<a href="#" class="card-link stretched-link" onclick="getProductByCategory('+item.id+')"></a>\
	                </div>\
              	</div>\
            </div>');
        }
	})

	m = 5;
	n = 12;

	for(j=0; j<divs-1; j++){
		x = 0;
		$.each(data, function(key, item) {
			if(key > m && key < n){
				$("#categories"+j).append('\
					<div class="col-md-2">\
			      		<div class="category-card card card-primary card-outline mb-2">\
			            	<div class="card-body" style="padding: 1rem 0rem 1rem 0rem;">\
								<center>\
									<p style="font-size: medium; margin-bottom: auto;" class="pos-card-text-body"><span><b>'+item.category_name+'</b></span></p>\
								</center>\
			                	<a href="#" class="card-link stretched-link" onclick="getProductByCategory('+item.id+')"></a>\
			                </div>\
			          	</div>\
			        </div>');
			}
		})
		m = m+6;
		n = n+6;
	}
}




// ---------------------------------------------------------    SEARCH   -----------------------------------------------------------------
//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 500;  //time in ms, .5 seconds for example
var $input = $('#search');


//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  $("#products").empty();

	var keyword = $('#search').val();
	$('#hiddensearchkeyword').val(keyword);


	if( $('#search').val() ){
	 	$.ajax({

			type: "GET",
			url: "/product-search/"+keyword,
			dataType: "json",
			success: function(response){
                // console.log(response);

				if(response.products.data.next_page_url == null){
	        $("#next").attr("href", '/product-search/'+keyword+'?page=' + parseInt(parseInt(response.products.current_page)))
	      }else{
	        $("#next").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)+1))
	      }

	      if(response.products.data.prev_page_url == null){
	        $("#prev").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)))
	      }else{
	        $("#prev").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)-1))
	      }

				if(response.products.data.length != 0){
					$.each(response.products.data, function(key, item) {

                        if(item.productImage == null){
                            productImage = "default.jpg"
						}else{
                            productImage = item.productImage
						}

						if(item.category_image == null){
                            category_image = "default.jpg"
						}else{
                            category_image = item.category_image
						}
                        if(item.variant_name == 'default'){
                            variant_name = "Default"
                        }else{
                            variant_name = item.variant_name
                        }


                        if (item.variant_image == null){
                            var src = "uploads/categories/" +category_image
                        }else{
                            var src = "uploads/variants/" + item.variant_image
                        }

						if(item.onHand < 1){
							$("#products").append('\
							<div class="col-md-2 clearfix d-none d-sm-block">\
				              	<div class="card card-primary mb-2">\
					              	<button class="add-btn" data-value="'+item.variant_id+'" value="'+item.id+'" disabled>\
										<center style="margin-top:1rem; overflow: hidden;">\
						                <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover; opacity: 0.5;" src="'+src+'" alt="'+item.productName+'">\
										</center>\
						                <div class="card-body" style="padding: 0.25rem;"  >\
							                <h5 style="font-size: small;" class="pos-card-tesubmitOrderxt-title wordWrap" data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
							                <p style="font-size: small;" class="pos-card-text-body text-danger"><strong>Stock out</strong></p>\
						                </div>\
						            </button>\
						        </div>\
						    </div>');
						}else{
							$("#products").append('\
							<div class="col-md-2 clearfix d-none d-sm-block">\
				              	<div class="card card-primary mb-2">\
					              	<button class="add-btn"  data-value="'+item.variant_id+'" value="'+item.id+'">\
									  	<center style="margin-top:1rem; overflow: hidden;">\
                                          <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover;" src="'+src+'" alt="'+item.productName+'">\
								  		</center>\
						                <div class="card-body" style="padding: 0.25rem;"  >\
							                <h5 style="font-size: small;" class="pos-card-text-title wordWrap" data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
							                <p style="font-size: small;" class="pos-card-text-body">In Stock: <span>'+item.onHand+'</span></p>\
						                </div>\
						            </button>\
						        </div>\
						    </div>');
						}
					})
				}else{
					$("#products").append('\
						<div class="" style="text-align: center;">\
			    			<h3>No Products Found.</h3>\
			    		</div>');
				}
			}


		});
	 }else{
	 	$.ajax({

			type: "GET",
			url: "/product-categories",
			dataType: "json",
			success: function(response){
				// categories(response.categories);
				$.each(response.products.data, function(key, item) {

					$("#next").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)+1))
					$("#prev").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)))

                    if(item.productImage == null){
                        productImage = "default.jpg"
                    }else{
                        productImage = item.productImage
                    }

                    if(item.category_image == null){
                        category_image = "default.jpg"
                    }else{
                        category_image = item.category_image
                    }
                    if(item.variant_name == 'default'){
                        variant_name = "Default"
                    }else{
                        variant_name = item.variant_name
                    }


                if (item.variant_image == null){
                        var src = "uploads/categories/" +category_image
                    }else{
                        var src = "uploads/variants/" + item.variant_image
                    }

						if(item.onHand < 1){
							$("#products").append('\
							<div class="col-md-2 clearfix d-none d-sm-block">\
				              	<div class="card card-primary mb-2">\
					              	<button class="add-btn" data-value="'+item.variant_id+'"  value="'+item.id+'" disabled>\
									  	<center style="margin-top:1rem; overflow: hidden;">\
                                          <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover; opacity: 0.5;" src="'+src+'" alt="'+item.productName+'">\
								  		</center>\
						                <div class="card-body" style="padding: 0.25rem;" >\
							                <h5 style="font-size: small;" class="pos-card-text-title wordWrap" data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
							                <p style="font-size: small;" class="pos-card-text-body text-danger"><strong>Stock out</strong></p>\
						                </div>\
						            </button>\
						        </div>\
						    </div>');
						}else{
							$("#products").append('\
							<div class="col-md-2 clearfix d-none d-sm-block">\
				              	<div class="card card-primary mb-2">\
					              	<button class="add-btn" data-value="'+item.variant_id+'" value="'+item.id+'">\
									  	<center style="margin-top:1rem; overflow: hidden;">\
                                          <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover;" src="'+src+'" alt="'+item.productName+'">\
								  		</center>\
						                <div class="card-body" style="padding: 0.25rem;"  >\
							                <h5 style="font-size: small;" class="pos-card-text-title wordWrap"  data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
							                <p style="font-size: small;" class="pos-card-text-body">In Stock: <span>'+item.onHand+'</span></p>\
						                </div>\
						            </button>\
						        </div>\
						    </div>');
						}
				  })
			}
		});
	}
}


// ----------------------------------------------------------------   GET PRODUCTS BY CATEGORY --------------------------------------------------


function getProductByCategory(data){

	$("#products").empty();
	$("#hiddencategorysearchkeyword").val(data);

	$.ajax({
		type: "GET",
		url: "/category-search/"+data,
		dataType: "json",
		success: function(response){
            // console.log(response);

			if(response.products.next_page_url == null){
	        $("#next").attr("href", '/pos/catogory?page=' + parseInt(parseInt(response.products.current_page)))
	      }else{
	        $("#next").attr("href", '/pos/catogory?page=' + parseInt(parseInt(response.products.current_page)+1))
	      }

	      if(response.products.prev_page_url == null){
	        $("#prev").attr("href", '/pos/catogory?page=' + parseInt(parseInt(response.products.current_page)))
	      }else{
	        $("#prev").attr("href", '/pos/catogory?page=' + parseInt(parseInt(response.products.current_page)-1))
	      }

			$.each(response.products.data, function(key, item) {
                if(item.productImage == null){
                    productImage = "default.jpg"
				}else{
                    productImage = item.productImage
				}

				if(item.category_image == null){
                    category_image = "default.jpg"
				}else{
                    category_image = item.category_image
				}
                if(item.variant_name == 'default'){
                    variant_name = "Default"
                }else{
                    variant_name = item.variant_name
                }


                if (item.variant_image == null){
                    var src = "uploads/categories/" +category_image
                }else{
                    var src = "uploads/variants/" + item.variant_image
                }

				if(item.onHand < 1){
					$("#products").append('\
					<div class="col-md-2 clearfix d-none d-sm-block">\
		              	<div class="card card-primary mb-2">\
			              	<button class="add-btn" data-value="'+item.variant_id+'" value="'+item.id+'" disabled>\
							  	<center style="margin-top:1rem; overflow: hidden;">\
                                  <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover; opacity: 0.5;" src="'+src+'" alt="'+item.productName+'">\
						  		</center>\
				                <div class="card-body" style="padding: 0.25rem;" >\
					                <h5 style="font-size: small;" class="pos-card-text-title wordWrap" data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
					                <p style="font-size: small;" class="pos-card-text-body text-danger"><strong>Stock out</strong></p>\
				                </div>\
				            </button>\
				        </div>\
				    </div>');
				}else{
					$("#products").append('\
						<div class="col-md-2 clearfix d-none d-sm-block">\
		              	<div class="card card-primary mb-2">\
			              	<button class="add-btn" data-value="'+item.variant_id+'" value="'+item.id+'">\
							  	<center style="margin-top:1rem; overflow: hidden;">\
                                  <img class="card-img-top img-thumbnail" style="width: 100%; height: 7vw; object-fit: cover;" src="'+src+'" alt="'+item.productName+'">\
						  		</center>\
				                <div class="card-body" style="padding: 0.25rem;" >\
					                <h5 style="font-size: small;" class="pos-card-text-title wordWrap" data-toggle="tooltip" data-placement="top" title="'+item.productName+'('+variant_name+')">'+item.productName+'('+variant_name+')</h5>\
					                <p style="font-size: small; " class="pos-card-text-body">In Stock: <span>'+item.onHand+'</span></p>\
				                </div>\
				            </button>\
				        </div>\
				    </div>');
				}
			})
		}
	})
}
var data = {};
var quantity = 0;
$(document).on('click', '.add-btn', function (e){
	e.preventDefault();

	var productId = $(this).val();
    var variantId = (this.getAttribute('data-value'))

	var productQty = $(this).find('span:first').text()  //getting product total quantity


    // alert(productId);
	$.ajax({
		type: "GET",
			url: "/product-add/"+productId+"/"+variantId,
			dataType: "json",
			success: function(response){

                // console.log(response);
                // alert(response.products[0].type);
                if(response.products[0].type=='Serialize')
                {
                    $('#serialModal').modal('show')
                    data = response;
                    quantity = productQty;
                }
                else
                {
                    var serial = response.products[0].barcode;
                    addproduct(response, productQty, serial);
                }
			}
	})
})

$('.serial_submit').on('click', function (e){
    if ($("#serial").val() != '') {
        var storeId		    =	$("#storeid").val();

        var productId		=	data.products[0].id;

        var variantId		=	data.products[0].variant_id;

        var serial = $('#serial').val();

        // console.log(data);
        $.ajax({
			type: "get",
			url: "/product-serial-check/"+productId+"/"+variantId+"/"+storeId+"/"+serial,
			dataType: "json",
			success: function(response){
                if(response.status==200){
                    var serial = response.serialNumber;
                    addproduct(data, quantity, serial);
                    $('#serial').val('');
                    $('#serialModal').modal('hide');
                }
                else{
                    $.notify(response.message, "error");
                }
            }
        });
    }
    else {
        $.notify("Enter Serial Number!", "error");
    }
})

function addproduct(response, productQty, serial){
    $.each(response.products, function(key, item) {

        if($('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').length == 0){

          var m=10;

          var discountChecked=0;
                var qty_o= $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(2) input[type='number']").val()
                if(isNaN(qty_o) ){
                    qty_o=0;
                }

                var count_qty=1+parseFloat(qty_o);
                if(item.isExcludedTax == "true"){
                    var tax = parseFloat((item.mrp/100) * item.tax);
                }else{
                    var tax = 0;
                }

                if(item.discount_type == "Percentage"){

                    var  dcnt= parseFloat((item.mrp/100) * item.discount).toFixed(2);
                    var discountChecked=parseFloat(dcnt*count_qty).toFixed(2);

                }else{
                    discountChecked = parseFloat((count_qty * item.discount)).toFixed(2);
                }

                if(isNaN(discountChecked)){
                    discountChecked = 0;
                }


            $('#product_table_body').append('<tr>\
                <td>'+item.productName+'('+item.variant_name+'-'+serial+')</td>\
                <td>'+item.mrp+'</td>\
                <td><input type="number" class="form-control w-100 border-left-0 border-right-0 border-top-0 rounded-0 pb-4" name="count" id="count" value="'+1+'"></td>\
                <td>'+item.mrp * 1+'</td>\
                <td style="display:none;">'+discountChecked+'</td>\
                <td style="display:none;">'+tax+'</td>\
                <td style="display:none;">'+discountChecked+'</td>\
                <td style="display:none;">'+tax+'</td>\
                <td style="display:none;">'+item.id+'</td>\
                <td style="display:none;">'+item.available_offer+'</td>\
                <td style="display:none;">'+item.requiredQuantity+'</td>\
                <td style="display:none;">'+item.freeQuantity+'</td>\
                <td style="display:none;">'+item.freeItemName+'</td>\
                <td style="display:none;">'+item.offerItemId+'</td>\
                <td style="display:none;">'+productQty+'</td>\
                <td style="display:none;">'+item.variant_id+'</td>\
                <td style="display:none;">'+item.variant_name+'</td>\
                <td><button class="btn-remove" style="background: transparent;" value="'+item.id+'"><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
                <td style="display:none;">'+item.id+'</td>\
                <td style="display:none;">'+item.type+'</td>\
                <td style="display:none;">'+serial+'</td>\
            </tr>');
        }
        else if(item.type=='Non-Serialize'){

            var trid = $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").index();
            var qty = $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(2) input[type='number']").val()
            var discount = parseFloat($('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(4)").text());
            // var discount=  discountChecked;
            var vat = $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(5)").text()
            var totalProductQty = parseInt($('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(14)").text())

            var add = parseInt(qty) + 1

            if(add > totalProductQty){

                $.notify("Stock limited!", {className: 'error', position: 'bottom right'});

                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(2) input[type='number']").val(totalProductQty)

                var subTotal = totalProductQty * item.mrp
                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(3)").text(subTotal.toFixed(2))


                var totalDiscount = parseFloat(discount)
                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(4)").text(discountChecked)

                var totalVat = parseFloat(vat)
                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(5)").text(totalVat)

            }else{

                var qty_o= $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(2) input[type='number']").val()
                if(isNaN(qty_o) ){
                    qty_o=0;
                }

                var count_qty=1+parseFloat(qty_o);
                if(item.isExcludedTax == "true"){
                    var tax = parseFloat((item.mrp/100) * item.tax);
                }else{
                    var tax = 0;
                }

                if(item.discount_type == "Percentage"){

                    var  dcnt= parseFloat((item.mrp/100) * item.discount).toFixed(2);
                    var discountChecked=parseFloat(dcnt*count_qty).toFixed(2);

                }else{
                    discountChecked = parseFloat((count_qty * item.discount)).toFixed(2);
                }

                if(isNaN(discountChecked)){
                    discountChecked = 0;
                }



                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(2) input[type='number']").val(add)

                var subTotal = add * item.mrp
                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(3)").text(subTotal.toFixed(2))


                var totalDiscount = parseFloat(discount) + item.discount
                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(4)").text(discountChecked)

                var totalVat = parseFloat(vat) + parseFloat(tax)
                $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(5)").text(totalVat)


            }

        }
        else{
            $.notify("Serial Number already added!", "error");
        }


        var qty = $('#product_table tr > td:first-child:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(2) input[type='number']").val()
        var res = Math.floor(qty/item.requiredQuantity)
        var remainder =  (qty%item.requiredQuantity)

        if(item.available_offer == 'true' && remainder == 0){

            var freeQty = Math.floor(qty/item.requiredQuantity)

            if(freeQty > 0){
                if($('#free_product_table tr > td:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').length == 0){
                    alert('Offer Avaialble! Buy '+item.requiredQuantity+ ' ' +item.productName +' & Get '+item.freeQuantity+ ' '+ item.freeItemName)
                    $('#free_product_table').show();
                    $('#free_product_table_body').append('<tr>\
                        <td>'+item.freeItemName+'</td>\
                        <td>'+item.freeQuantity+'</td>\
                        <td>'+item.productName+'('+item.variant_name+')</td>\
                        <td style="display:none;">'+item.offerItemId+'</td>\
                    </tr>');
                }else{
                    alert('Offer Item Added for '+item.productName+'('+item.variant_name+'-'+serial+')!')
                    var add =  parseInt($('#free_product_table tr > td:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(1)").text()) + item.freeQuantity
                    $('#free_product_table tr > td:contains('+item.productName+'('+item.variant_name+'-'+serial+'))').closest("tr").find("td:eq(1)").text(add)
                }
            }
        }

        var total = 0;
        $('#product_table').find('> tbody > tr').each(function () {
            var price = parseFloat($(this).find("td:eq(3)").text());
                total = parseFloat(total + price);
                $('#total').text(total.toFixed(2));
    });

        var discountTotal = 0;
        $('#product_table').find('> tbody > tr').each(function () {
            var d = parseFloat($(this).find("td:eq(4)").text());
                discountTotal = parseFloat(discountTotal + d);
                $('#discount').text(discountTotal.toFixed(2));
    });

    var vatTotal = 0;
        $('#product_table').find('> tbody > tr').each(function () {
            var d = parseFloat($(this).find("td:eq(5)").text());
                vatTotal = parseFloat(vatTotal + d);
                $('#vatS').text(vatTotal.toFixed(2));
    });
        if (!$('.specialdiscount').val()) {
            $('.specialdiscount').val(0)
        }

        $('#grandTotal').text(((parseFloat($('#total').text()) - parseFloat($('#discount').text())) + parseFloat($('#vatS').text()) - parseFloat($('#specialdiscount').val())).toFixed(2) );

    })
}

$("#product_table").on('click', '.btn-remove', function () {
	    $(this).closest('tr').remove();

	    var total = 0;
		$('#product_table').find('> tbody > tr').each(function () {
	    	var price = parseFloat($(this).find("td:eq(3)").text());
				total = total + price;
				$('#total').text(total.toFixed(2));
	    });

		var discountTotal = 0;
		$('#product_table').find('> tbody > tr').each(function () {
	    	var d = parseFloat($(this).find("td:eq(4)").text());
				discountTotal = discountTotal + d;
				$('#discount').text(discountTotal.toFixed(2));
	    });

	    var vatTotal = 0;
		$('#product_table').find('> tbody > tr').each(function () {
	    	var d = parseFloat($(this).find("td:eq(5)").text());
				vatTotal = vatTotal + d;
				$('#vatS').text(vatTotal.toFixed(2));
	    });
    if (!$('.specialdiscount').val()) {
        $('.specialdiscount').val(0)
    }
    $('#grandTotal').text((parseFloat($('#total').text()) - parseFloat($('#discount').text()) + parseFloat($('#vatS').text()) - parseFloat($('#specialdiscount').val())).toFixed(2));

	    var rowCount = $('#product_table tr').length;

	    if(rowCount == 1){
	    	$('#total').text(0);
	    	$('#discount').text(0);
	    	$('#vatS').text(0);
	    	$('#grandTotal').text(0);
	    }

	    var availableOffer   = $(this).closest("tr").find("td:eq(9)").text()
	    var requiredQuantity = parseInt($(this).closest("tr").find("td:eq(10)").text())
	    var freeQuantity 	 = parseInt($(this).closest("tr").find("td:eq(11)").text())
	    var freeItemName 	 = $(this).closest("tr").find("td:eq(12)").text()
	    var productName 	 = $(this).closest("tr").find("td:eq(0)").text()
	    var variantName 	 = $(this).closest("tr").find("td:eq(16)").text()

	    var qty = $(this).closest("tr").find("td:eq(2) input[type='number']").val()

	    if(availableOffer == "true"){

	    	$('#free_product_table tr > td:contains('+productName+')').closest("tr").remove()
	    	if($('#free_product_table tr').length == 1){
    			$('#free_product_table').hide()
    		}
	    }




	});


$(document).on('submit', '#QuickSellForm', function (e){
	e.preventDefault();

	// alert('Hello')
	if( $('#product').val() && $('#price').val() && $('#quantity').val()){
		if($('#product_table tr > td:contains('+$('#product').val()+')').length == 0){
			$('#product_table_body').append('\
			<tr>\
				<td>'+$("#product").val()+'</td>\
				<td>'+$("#price").val()+'</td>\
				<td><input type="number" class="form-control w-75 border-left-0 border-right-0 border-top-0 rounded-0 pb-4"  name="count" id="count" value="'+$("#quantity").val()+'"></td>\
				<td>'+$("#price").val() * $("#quantity").val()+'</td>\
				<td style="display:none;">'+$("#discountP").val()+'</td>\
				<td style="display:none;">'+$("#vat").val()+'</td>\
				<td style="display:none;">'+$("#discountP").val()+'</td>\
				<td style="display:none;">'+$("#vat").val()+'</td>\
				<td style="display:none;">'+0+'</td>\
				<td><button class="btn-remove" style="background: transparent;" value=""><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
			</tr>');

			$('#QuickSellModal').modal('hide');
		}else{
			var trid = $('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").index();
			var qty = $('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(2) input[type='number']").val()
			var discount = $('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(4)").text()
			var vat = $('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(5)").text()

			var add = parseInt(qty) + parseInt($("#quantity").val())
			$('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(2) input[type='number']").val(add)

			var subTotal = parseInt(add) * parseInt($("#price").val())
			$('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(3)").text(subTotal.toFixed(2))


			var totalDiscount = parseFloat(discount) + parseFloat($("#discountP").val())
			$('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(4)").text(totalDiscount.toFixed(2))

			var totalVat = parseFloat(vat) + parseFloat($("#vat").val())
			$('#product_table tr > td:contains('+$("#product").val()+')').closest("tr").find("td:eq(5)").text(totalVat.toFixed(2))
		}

		var total = 0;
		$('#product_table').find('> tbody > tr').each(function () {
	    	var price = parseFloat($(this).find("td:eq(3)").text());
			total = total + price;
			$('#total').text(total.toFixed(2));
	    });

		var discountTotal = 0;
		$('#product_table').find('> tbody > tr').each(function () {
	    	var d = parseFloat($(this).find("td:eq(4)").text());
			discountTotal = discountTotal + d;
			$('#discount').text(discountTotal.toFixed(2));
	    });

	    var vatTotal = 0;
		$('#product_table').find('> tbody > tr').each(function () {
	    	var d = parseFloat($(this).find("td:eq(5)").text());
			vatTotal = vatTotal + d;
			$('#vatS').text(vatTotal.toFixed(2));
	    });

	    $('#grandTotal').text(((parseFloat($('#total').text()) - parseFloat($('#discount').text())) + parseFloat($('#vatS').text())).toFixed(2) );

	}else{
		alert('Please fill the Name, Price & Quantity fields.')
	}

});

$(document).on('click', '#cancel_table', function (e){
	$("#product_table").find("tr:gt(0)").remove();
	$("#free_product_table").find("tr:gt(0)").remove();
	$("#free_product_table").hide();
	$("#referenceid").val(null)

	var rowCount = $('#product_table tr').length;

	if(rowCount == 1){
    	$('#total').text(0);
    	$('#discount').text(0);
    	$('#vatS').text(0);
    	$('#grandTotal').text(0);
    }
    $('#customeridhidden').val(null);
    $('#customername').text('');
	$('#customercontact').text('');
})

$(document).on('click', '#walkin', function (e){

	$('#customeridhidden').val(0);
    $('#customername').text('Walking Customer');
    $('#customercontact').text('N/A');

	$('#AddCustomerModal').modal('hide');
})


$(document).on('click', '#newcustomer', function (e){

    $("#customerid").val('').trigger('change');
	$("#newcustomerdiv").empty();
	$("#newcustomer").hide();

	$("#newcustomerdiv").append('<hr>\
							<div id="" class="form-text">Add a new customer with name and contact or add with all details.</div>\
							<div class="form-group mb-3 pt-1">\
								<label>Name</label>\
								<input type="text" id="clientname" name="customername" class="form-control" placeholder="Enter customer name">\
						    </div>\
						    <div class="form-group mb-3">\
								<label>Contact</label>\
								<input type="text" id="clientcontact" name="mobile" class="form-control" value="" placeholder="Enter customer contact">\
						    </div>\
						    <div class="float-left pt-1" role="group" aria-label="Basic outlined example">\
								<button id="newcustomerinfo" type="button" class="w-100 btn btn-info pe-3"><i class="fas fa-plus"></i> Detail Info</button>\
							</div>');
})

$(document).on('click', '#newcustomerinfo', function (e){

	$("#newcustomerdiv1").empty();

	$("#newcustomerinfo").hide();

	$("#newcustomerdiv1").append('<div class="form-group mb-3">\
								<label>Email</label>\
								<input type="email" id="email" name="customeremail" class="form-control" placeholder="Enter customer email">\
						    </div>\
						    <div class="form-group mb-3">\
								<label>Address</label>\
								<input type="text" id="address" name="customeraddress" class="form-control" value="" placeholder="Enter customer address">\
						    </div>\
						    <div class="form-group mb-3 pt-1">\
								<label>Note</label>\
								 <textarea class="form-control" name="note" id="note" rows="2" placeholder="If any notes"></textarea>\
						    </div>\
						    <div class="form-group mb-3">\
								<label>Image</label>\
								<input type="file" class="form-control" name="customerimage" id="customerimage">\
						    </div>');
})

$(document).on('submit', '#AddCustomerForm', function (e){
	e.preventDefault();

	customerId = $("#customerid").val();

    let formData = new FormData($('#AddCustomerForm')[0]);

    if ($("#customerid").val() && $("#customerid").val() != 'option_select'){
		$.ajax({
			type: "GET",
			url: "/customer-search/"+customerId,
			dataType: "json",
			success: function(response){
				$.each(response.customer, function(key, item) {
					$('#customeridhidden').val(customerId);
					$('#customername').text(item.name);
					$('#customercontact').text(item.mobile);
				})
			}
		})

		$("#newcustomerdiv").empty();
		$("#newcustomerdiv1").empty();
		$('#AddCustomerModal').modal('hide');

    } else if ($("#clientname").val() && $("#clientcontact").val()){

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "POST",
			url: "/customer-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
                $('#customeridhidden').val(response.customerId);
			}
		})

		$('#customername').text($("#clientname").val());
	    $('#customercontact').text($("#clientcontact").val());
		$("#newcustomerdiv").empty();
		$("#newcustomerdiv1").empty();
		$('#AddCustomerModal').modal('hide');

	}

})


$('#AddCustomerModal').on('hide.bs.modal', function(){
	$("#newcustomerdiv").empty();
	$("#newcustomerdiv1").empty();
});


$(document).on('keypress', '#count', function(e) {

  var key = e.which;

	if(key == 13){  // the enter key code

		if($(this).closest("tr").find("td:eq(2) input[type='number']").val() > 0){

		  	var price = $(this).closest("tr").find("td:eq(1)").text()
			var qty = parseFloat($(this).closest("tr").find("td:eq(2) input[type='number']").val())
			var discount = $(this).closest("tr").find("td:eq(6)").text()
			//alert(discount);
			var tax = $(this).closest("tr").find("td:eq(7)").text()
			var totalProductQty = parseFloat($(this).closest("tr").find("td:eq(14)").text())

			if(qty > totalProductQty){
				$.notify("Stock limited!", "error");

				$(this).closest("tr").find("td:eq(2) input[type='number']").val(totalProductQty)

			}else{

				var subTotal = parseFloat(price) * parseFloat(qty)
				$(this).closest("tr").find("td:eq(3)").text(subTotal.toFixed(2))

				var subTotalDiscount = parseFloat(discount) * parseFloat(qty)
				$(this).closest("tr").find("td:eq(4)").text(subTotalDiscount.toFixed(2))

				var subTotalTax = parseFloat(tax) * parseFloat(qty)
				$(this).closest("tr").find("td:eq(5)").text(subTotalTax.toFixed(2))

				var total = 0;
					$('#product_table').find('> tbody > tr').each(function () {
				    	var price = parseFloat($(this).find("td:eq(3)").text());
						total = total + price;
						$('#total').text(total.toFixed(2));
				    });

					var discountTotal = 0;
					$('#product_table').find('> tbody > tr').each(function () {
				    	var d = parseFloat($(this).find("td:eq(4)").text());
						discountTotal = discountTotal + d;
						$('#discount').text(discountTotal.toFixed(2));
				    });

				    var vatTotal = 0;
					$('#product_table').find('> tbody > tr').each(function () {
				    	var d = parseFloat($(this).find("td:eq(5)").text());
						vatTotal = vatTotal + d;
						$('#vatS').text(vatTotal.toFixed(2));
				    });
                    if($('#specialdiscount').val()==''||$('#specialdiscount').val()==0)
                    {
                        var specialdiscount = 0;
                    }
                    else{
                        var specialdiscount = $('#specialdiscount').val();
                    }

                    $('#grandTotal').text(((parseFloat($('#total').text()) - parseFloat($('#discount').text())) - specialdiscount + parseFloat($('#vatS').text()) ).toFixed(2));

				    var availableOffer   = $(this).closest("tr").find("td:eq(9)").text()
				    var requiredQuantity = parseInt($(this).closest("tr").find("td:eq(10)").text())
				    var freeQuantity 	 = parseInt($(this).closest("tr").find("td:eq(11)").text())
				    var freeItemName 	 = $(this).closest("tr").find("td:eq(12)").text()
				    var productName		 = $(this).closest("tr").find("td:eq(0)").text()
				    var offerItemId		 = $(this).closest("tr").find("td:eq(13)").text()
				    var variantName		 = $(this).closest("tr").find("td:eq(16)").text()

				    var qty = $(this).closest("tr").find("td:eq(2) input[type='number']").val()

				    var res = Math.floor(qty/requiredQuantity)
						var remainder =  (qty%requiredQuantity)

					if(availableOffer == 'true'){

						var freeQty = Math.floor(qty/requiredQuantity)

						if(freeQty > 0){
							if($('#free_product_table tr > td:contains('+productName+')').length == 0){
								alert('Offer Avaialble! Buy '+requiredQuantity+ ' ' +productName +' & Get '+freeQuantity+ ' '+ freeItemName)
								$('#free_product_table').show();
								$('#free_product_table_body').append('<tr>\
									<td>'+freeItemName+'</td>\
									<td>'+freeQty*freeQuantity+'</td>\
									<td>'+productName+'</td>\
									<td style="display:none;">'+offerItemId+'</td>\
				        		</tr>');
							}else{
								alert('Offer Item Added!')
								var add =  freeQty * freeQuantity
								$('#free_product_table tr > td:contains('+productName+')').closest("tr").find("td:eq(1)").text(add)
							}
						}else{
							$('#free_product_table tr > td:contains('+productName+'('+variantName+'))').closest("tr").remove();
							if($('#free_product_table tr').length == 1){
				    			$('#free_product_table').hide()
				    		}
						}
					}
			}



		}else{
			$.notify("WARNING: Invalid quantity!", "warn");
			$(this).closest("tr").find("td:eq(2) input[type='number']").val(1)


			var price = $(this).closest("tr").find("td:eq(1)").text()
			var qty = $(this).closest("tr").find("td:eq(2) input[type='number']").val()
			var discount = $(this).closest("tr").find("td:eq(6)").text()
			var tax = $(this).closest("tr").find("td:eq(7)").text()

			var subTotal = parseFloat(price) * parseFloat(qty)
			$(this).closest("tr").find("td:eq(3)").text(subTotal.toFixed(2))

			var subTotalDiscount = parseFloat(discount) * parseFloat(qty)
			$(this).closest("tr").find("td:eq(4)").text(subTotalDiscount.toFixed(2))

			var subTotalTax = parseFloat(tax) * parseFloat(qty)
			$(this).closest("tr").find("td:eq(5)").text(subTotalTax.toFixed(2))

			var total = 0;
			$('#product_table').find('> tbody > tr').each(function () {
		    	var price = parseFloat($(this).find("td:eq(3)").text());
					total = total + price;
					$('#total').text(total.toFixed(2));
		    });

			var discountTotal = 0;
			$('#product_table').find('> tbody > tr').each(function () {
		    	var d = parseFloat($(this).find("td:eq(4)").text());
					discountTotal = discountTotal + d;
					$('#discount').text(discountTotal.toFixed(2));
		    });

		    var vatTotal = 0;
			$('#product_table').find('> tbody > tr').each(function () {
		    	var d = parseFloat($(this).find("td:eq(5)").text());
					vatTotal = vatTotal + d;
					$('#vatS').text(vatTotal.toFixed(2));
		    });

            if($('#specialdiscount').val()==''||$('#specialdiscount').val()==0)
            {
                var specialdiscount = 0;
            }
            else{
                var specialdiscount = $('#specialdiscount').val();
            }

		    $('#grandTotal').text(((parseFloat($('#total').text()) - parseFloat($('#discount').text())) - specialdiscount + parseFloat($('#vatS').text()) ).toFixed(2));

	    }


	}



});

$(document).on('click', '#payprint_btn', function (e){
    if (!$('.specialdiscount').val()) {
        $('.specialdiscount').val(0)
    }
    // $("#discount").text(parseFloat($("#discount").text()) + parseFloat($('.specialdiscount').val()));
    // $("#paidp").text($("#grandTotal").text())
    $('#duep').text(0);
    $('#paidp').text($("#grandTotal").text());
    $("#cashamount").val($("#grandTotal").text())
    $('#mobilebankingamount').val(0)
    $('#bankamount').val(0)
	OrderSubmitToServer();
})


var time = new Date().getTime();
var orderId = time.toString();

function OrderSubmitToServer() {
	this.event.preventDefault();

	var d = new Date();
	var date = d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ d.getDate();

	let orders= {};

	orders["orderId"] 		= orderId;
	orders["clientId"] 		= $("#customeridhidden").val();
	orders["clientName"] 	= $("#customername").text();


	orders["total"] 		= $("#total").text();
    orders["totalDiscount"] = $("#discount").text();
    if ($("#specialdiscount").val()=='')
    {
        orders["specialDiscount"] =0;
        // alert(orders["specialDiscount"])
    }
    else{
        orders["specialDiscount"] 	= $("#specialdiscount").val();
        // alert(orders["specialDiscount"])
    }
	orders["totalTax"] 		= $("#vatS").text();
	orders["grandTotal"] 	= $("#grandTotal").text();
	orders["subscriberId"]	= $("#subscriberid").val();
	orders["storeId"] 		= $("#storeid").val();
	orders["posId"] 		= $("#posid").val();
	orders["salesBy"] 		= $("#salesby").val();
	orders["orderDate"] 	= date;
	orders["due"] 			= $('#duep').text();
    orders["deposit"]          = $('#paidp').text();

	orders["referenceId"] 	= $('#referenceid').val();


	var productTable = $('#product_table');
	var freeProductTable = $('#free_product_table');
	var productList = [];
	var paymentDetails=[];
	let cashObj= {};
	let mobileBankingObj= {};
	let bankObj= {};
	let paymentObj= {};

	//---------------------PAYMENT DETAILS--------------------------------------------------------------------

	paymentObj["amount"] = $("#grandTotal").text()
	paymentObj["id"]	 = "0000"
    paymentObj["payment_type"]	 = "Cash"
	paymentDetails.push(paymentObj);


	if( $('#cashamount').val() && $('#cashamount').val() > 0){

		cashObj["amount"]	= $('#cashamount').val();
		cashObj["id"]		= "0000"
        cashObj["payment_type"]		= "Cash"

		paymentDetails.push(cashObj);
	}

	if( $('#mobilebankingamount').val() && $('#mobilebankingamount').val() > 0 && $('#mobilebanking').val() != "default"){

		mobileBankingObj["amount"]	= $('#mobilebankingamount').val();
		mobileBankingObj["id"]		= $('#mobilebanking').val();
        mobileBankingObj["payment_type"]	= $("#mobilebanking").find("option:selected").text();

		paymentDetails.push(mobileBankingObj);
	}

	if( $('#bankamount').val() && $('#bankamount').val() > 0){

		bankObj["amount"]	= $('#bankamount').val();
		bankObj["id"]		= $('#bank').val();
        bankObj["payment_type"]		= "Card"

		paymentDetails.push(bankObj);
	}

    $(productTable).find('> tbody > tr').each(function () {

    	//----------------------------------------------------------------------Product Object
    	let product= {};


    	product["productId"]		= $(this).find("td:eq(8)").text();
        product["variantId"]		= $(this).find("td:eq(15)").text();
    	product["productName"]		= $(this).find("td:eq(0)").text();
    	product["type"]		        = $(this).find("td:eq(19)").text();
    	product["serialNumber"]	    = $(this).find("td:eq(20)").text();
    	product["quantity"]			= $(this).find("td:eq(2) input[type='number']").val();

    	if( ($(this).find("td:eq(9)").text() == "true") && ( product["quantity"] >= parseInt( $(this).find("td:eq(10)").text()) ) ){

    		$(freeProductTable).find('> tbody > tr').each(function () {
	    		if( product["productName"] == $(this).find("td:eq(2)").text() ){
	    			product["offerItemId"]		= $(this).find("td:eq(3)").text();
			    	product["offerName"]		= $(this).find("td:eq(0)").text();
			    	product["offerQuantity"]	= $(this).find("td:eq(1)").text();


	    		}
	    	 })
    	}else{
    		if( ($(this).find("td:eq(9)").text() == "true") ){

				product["offerItemId"]		= $(this).find("td:eq(13)").text()
		    	product["offerName"]		= $(this).find("td:eq(12)").text()
		    	product["offerQuantity"]	= 0;

    		}else{
    			product["offerItemId"]		= "null";
		    	product["offerName"]		= "null";
		    	product["offerQuantity"]	= 0;
    		}

    	}

    	product["price"]		= $(this).find("td:eq(1)").text();
    	product["totalPrice"]		= $(this).find("td:eq(3)").text();
    	product["totalDiscount"]	= $(this).find("td:eq(4)").text();
    	product["totalTax"]			= $(this).find("td:eq(5)").text();
        product["specialDiscount"]	= $("#specialdiscount").val();;
        var pprice = parseFloat($(this).find("td:eq(3)").text());
        var pdiscount = parseFloat($(this).find("td:eq(4)").text());
        var ptax = parseFloat($(this).find("td:eq(5)").text());
        product["grandTotal"]       = parseFloat(pprice + ptax - pdiscount).toFixed(2)
    	product["discount"]			= $(this).find("td:eq(6)").text()

    	var price = $(this).find("td:eq(1)").text();
		var tax = parseFloat($(this).find("td:eq(7)").text());
		var taxAmount = parseFloat((tax*100)/price);
		product["tax"]			= taxAmount;


    	productList.push(product);

    });

    orders["orderDetails"]=productList;
    orders["paymentDetails"]=paymentDetails;

    if(productList.length>0){
    	submitOrder(orders);
        // console.log(orders);
    }else{
    	alert("Please Enter at least one product!");
    }

}

$(document).on('click','#poslogout',function(){

    $.ajax({
		type: "POST",
		url: "/pos-logout",
		headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
		success: function(response){
			if(response.url != null){
	          	window.location=response.url;
	        }
		}
	})
});

$(document).on('click', '#pay_btn', function (e){

	$('#PaymentModal').modal('show');

	var grandTotal = parseFloat($('#grandTotal').text())
	var zero = 0

	$('#totalp').text(grandTotal.toFixed(2))
	$('#paidp').text(zero.toFixed(2))
	$('#duep').text(grandTotal.toFixed(2))

})

$("#cashamount").change(function(){

  if( !$('#cashamount').val() ){
		$('#cashamount').val(0)
	}

  var cashAmount = parseFloat($('#cashamount').val())
  var grandTotal = parseFloat($('#grandTotal').text())

  if($('#mobilebankingamount').val() > 0 && $('#bankamount').val() > 0){
  	var totalPaid = parseFloat( $('#mobilebankingamount').val()) + parseFloat($('#bankamount').val()) + cashAmount
  }else if($('#mobilebankingamount').val() > 0){
  	var totalPaid = parseFloat( $('#mobilebankingamount').val()) + cashAmount
  }else if($('#bankamount').val() > 0){
  	var totalPaid = parseFloat( $('#bankamount').val()) + cashAmount
  }else{
  	var totalPaid = cashAmount
  }

  var totalDue = parseFloat(grandTotal) - parseFloat(totalPaid)

	$('#paidp').text(totalPaid.toFixed(2))
	$('#duep').text(totalDue.toFixed(2))

});

$("#mobilebankingamount").change(function(){

	if( !$('#mobilebankingamount').val() ){
		$('#mobilebankingamount').val(0)
	}

	var mobileBankingAmount = parseFloat($('#mobilebankingamount').val())
	var grandTotal = parseFloat($('#grandTotal').text())

	if($('#cashamount').val() > 0 && $('#bankamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + parseFloat($('#bankamount').val()) + mobileBankingAmount
	}else if($('#cashamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + mobileBankingAmount
	}else if($('#bankamount').val() > 0){
		var totalPaid = parseFloat( $('#bankamount').val()) + mobileBankingAmount
	}else{
		var totalPaid = mobileBankingAmount
	}

	var totalDue = parseFloat(grandTotal) - parseFloat(totalPaid)

	$('#paidp').text(totalPaid.toFixed(2))
	$('#duep').text(totalDue.toFixed(2))

})

$("#bankamount").change(function(){

	if( !$('#bankamount').val() ){
		$('#bankamount').val(0)
	}

	var bankAmount = parseFloat($('#bankamount').val())
	var grandTotal = parseFloat($('#grandTotal').text())

	if($('#cashamount').val() > 0 && $('#mobilebankingamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + parseFloat($('#mobilebankingamount').val()) + bankAmount
	}else if($('#cashamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + bankAmount
	}else if($('#mobilebankingamount').val() > 0){
		var totalPaid = parseFloat( $('#mobilebankingamount').val()) + bankAmount
	}else{
		var totalPaid = bankAmount
	}

	var totalDue = parseFloat(grandTotal) - parseFloat(totalPaid)

	$('#paidp').text(totalPaid.toFixed(2))
	$('#duep').text(totalDue.toFixed(2))
})


// Special Discount

$(".specialdiscount").change(function(){

	if( !$('.specialdiscount').val() ){
		$('.specialdiscount').val(0)
	}

	var specialDiscountAmount = parseFloat($('.specialdiscount').val())
	var total = parseFloat($('#total').text())
	var vat = parseFloat($('#vatS').text())
	var prevDiscount = parseFloat($('#discount').text())
	var zero = 0;

	if(specialDiscountAmount > total){
		$.notify("Invalid Discount!", {className:"error", position:"top-right"})
	}else{
		var grantTotalAfterDiscount = parseFloat(total + vat - (specialDiscountAmount+prevDiscount))

		$('#totalp').text(grantTotalAfterDiscount.toFixed(2))
		$('#grandTotal').text(grantTotalAfterDiscount.toFixed(2))
		// $('#discount').text((prevDiscount+specialDiscountAmount).toFixed(2))


		var cash = parseFloat($('#cashamount').val())
		var bank = parseFloat($('#bankamount').val())
		var mobileBank = parseFloat($('#mobilebankingamount').val())


		var totalPaid = parseFloat(cash + bank + mobileBank)

		if(isNaN(totalPaid)){
			totalPaid = 0;
		}


		var totalDue = parseFloat(grantTotalAfterDiscount) - parseFloat(totalPaid)



		if(totalDue < 0){
			$.notify("Invalid Amount!", {className:"error", position:"top-right"})
		}else{
			$('#duep').text(totalDue.toFixed(2))
		}


	}

})



$(document).on('click', '#pay_btnP', function (e){

	e.preventDefault();

    if (!$('.specialdiscount').val()) {
        $('.specialdiscount').val(0)
    }

    // $("#discount").text(parseFloat($("#discount").text()) + parseFloat($('.specialdiscount').val()));

    var mfs = parseFloat($('#mobilebankingamount').val());
    var mfsType = $("#mobilebanking").find("option:selected").text()

	if(mfs > 0 && mfsType == 'Select'){
		$.notify("Please select mobile banking type!", {className:"error", position:"top-right"})
	}else{
		let due = {}

		var d = new Date();
		var date = d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ d.getDate();

		due["card"] 			= $('#bankamount').val();
		due["cash"] 			= $('#cashamount').val();
		due["clientId"] 		= $("#customeridhidden").val();
		due["due_amount"] 		= parseFloat($('#duep').text());
		due["mobile_bank"] 		= $('#mobilebankingamount').val();
		due["mobile_bank_type"] = $("#mobilebanking").find("option:selected").text()
		due["paid_amount"] 		= $("#paidp").text();


		due["storeId"] 		= $("#storeid").val();
		due["subscriberId"] = $("#subscriberid").val();
		due["total"] 		= parseFloat($("#totalp").text());
		due["userId"] 		= $("#salesby").val();
		due["depositDate"] 	= date;
		due["orderId"] 		= orderId;

		var dueAmount = parseFloat($('#duep').text());
		var customerId = $("#customeridhidden").val();
		var grandTotal = parseFloat($('#grandTotal').text())

		if( (dueAmount > 0 && customerId == '') || (dueAmount > 0 && customerId == '0')){
			$('#form_div').find('form')[0].reset();

			$.notify("Please add customer for due sale", {className:"error", position:"top-right"})

		}else if(grandTotal == 0){
			alert("Please Enter at least one product!");
        }else if(dueAmount > 0){
			submitDue(due);
			OrderSubmitToServer();
		}else{
			OrderSubmitToServer();
		}
	}




})

function submitOrder(jsonData){
	$.ajax({
        type: "POST",
        url: "/check-offer-quantity",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
        success: function (response) {

        	if(response.check == "True"){
        		$.ajax({
			        type: "POST",
			        url: "/get-order",
			        data: JSON.stringify(jsonData),
			        dataType : "json",
			        contentType: "application/json",
			        success: function (response) {
			        	invoice(response.orderId)

			        }
			    });
        	}else{
        		$.notify('Offer Item low stock!!!', {className: 'error', position: 'bottom right'})
        	}

        }
    });



}

function submitDue(jsonData){

	$.ajax({
        type: "POST",
        url: "/due-payment",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
        success: function (response) {

        }
    });
}

function invoice(data){

	$('#invoice_table_body').empty()
	var storeName = $('#storename').val();
	var orgName = $('#orgname').val();
	var storeAddress = $('#storeaddress').val();
	var binNumber = $('#binnumber').val();

	//var cashier = $("#userid").val();
	var cashier = $("#username").val();
	var posId = $("#posid").val();

	var time = new Date().getTime();
	var orderId = data

	var d = new Date();
	var date = d.getDate() +"-"+ (d.getMonth()+1) +"-"+ d.getFullYear()

	var d = new Date(); // for now
	var hours = d.getHours(); // => 9
	var miutes = d.getMinutes(); // =>  30
	var seconds = d.getSeconds(); // => 51
	var iTime = d.getHours()+":"+d.getMinutes()

	//var customerId = $("#customeridhidden").val();
	var customerId = $("#customerid option:selected").text();
    // alert(customerId)

	$('#iStoreName').text(storeName)
	$('#iOrgName').text(orgName)
	$('#iStoreAddress').text(storeAddress)
	$('#iBin').text(binNumber)

	$('#iCashier').text(cashier)
	$('#iPosId').text(posId)
	$('#iInvoice').text(orderId)
	$('#iDate').text(date+" "+iTime)
    if (customerId == 'Select Customer'){
        $('#iCustomerId').text('Walking Customer')
	}
    else if($("#customerid").val() == null)
    {
        var name = $('#customername').text();
        var number = $('#customercontact').text();
        $('#iCustomerId').text(name + '(' + number +')')
    }
    else{
		$('#iCustomerId').text(customerId)
	}

	var productTable = $('#product_table');
	var index = 0

	$(productTable).find('> tbody > tr').each(function () {
		var itemDescription = $(this).find("td:eq(0)").text()
		var unitPrice = parseFloat($(this).find("td:eq(1)").text())
		var qty = parseFloat($(this).find("td:eq(2) input[type='number']").val())
		var total = parseFloat($(this).find("td:eq(3)").text())
		index = index + 1
		$('#invoice_table_body').append('\
			<tr>\
				<td>'+index+'</td>\
				<td>'+ itemDescription+'</td>\
				<td>'+ unitPrice.toFixed(2) +'</td>\
				<td>'+ qty.toFixed(2) +'</td>\
				<td>'+ total.toFixed(2) +'</td>\
    		</tr>')


	})

	var subTotal = parseFloat($("#total").text());
	var discount = parseFloat($("#discount").text());
	var afterDiscount = subTotal - discount

    var specialDiscount = parseFloat($("#specialdiscount").val());
    var afterSpecialDiscount = afterDiscount - specialDiscount

	var vat = parseFloat($("#vatS").text());
    var afterVat = afterSpecialDiscount + vat
	var rounding = afterVat;
	var due = parseFloat($("#duep").text());
	// alert(due);
	// alert(parseFloat(rounding));
	var netPayable = parseFloat(rounding) - due;

	$('#iSubTotal').text(subTotal.toFixed(2))
	$('#iDiscount').text(discount.toFixed(2))
	$('#iAfterDiscount').text(afterDiscount.toFixed(2))
    $('#iSpecialDiscount').text(specialDiscount.toFixed(2))
    $('#iAfterSpecialDiscount').text(afterSpecialDiscount.toFixed(2))
	$('#iVat').text(vat.toFixed(2))
	$('#iAfterVat').text(afterVat.toFixed(2))
	$('#iRounding').text(rounding.toFixed(2))

	$('#iAfterRounding').text(rounding.toFixed(2))
	$('#iDue').text(due.toFixed(2))

	$('#iNetPayable').text(rounding.toFixed(2))
	$('#iPaid').text(netPayable.toFixed(2))



	$('#ReceiptModal').modal('show');
    $.print("#invoice");
    $('#ReceiptModal').on('hidden.bs.modal', function () {
        $('#specialdiscount').val();
	 location.reload();
	})
}


function payFieldsValidation(){


	var cash = parseFloat($('#cashamount').val())
	var mobileBank = parseFloat($('#mobilebankingamount').val())
	var bank = parseFloat($('#bankamount').val())

	if(isNaN(cash)){
		cash = 0
	}

	if(isNaN(mobileBank)){
		mobileBank = 0
	}

	if(isNaN(bank)){
		bank = 0
	}

	var sum = parseFloat(cash + mobileBank + bank)

	var grandTotal = parseFloat($('#grandTotal').text())
	var paid = parseFloat($('#paidp').val())


	if(sum > grandTotal){
		$.notify("Invalid Amount!", {className:"error", position:"top-right"})
		$('#form_div').find('form')[0].reset();

		var grandTotal = parseFloat($('#grandTotal').text())
		var zero = 0

		$('#totalp').text(grandTotal.toFixed(2))
		$('#paidp').text(zero.toFixed(2))
		$('#duep').text(grandTotal.toFixed(2))
		$('#grandTotal').text(grandTotal.toFixed(2))

		$('#cashamount').val()
		$('#mobilebankingamount').val()
		$('#bankamount').val()
	}

}






