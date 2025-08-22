$(document).ready(function(){

 $(document).on('click', '#page-link a', function(event){
    event.preventDefault();

    var page = $(this).attr('href').split('page=')[1];
    var searchPage = $(this).attr('href');

    if(searchPage.indexOf("search") >= 0){

      search_fetch_data(page);
    }else if(searchPage.indexOf("catogory") >= 0){
      category_fetch_data(page)
    }else{

      fetch_data(page);
    }


 });

  function fetch_data(page){
    var _token = $("input[name=_token]").val();
    $.ajax({
        url:"/pagination/fetch?page="+page,
        method:"POST",
        success:function(response){
           $("#products").empty();

            if(response.products.next_page_url == null){
              $("#next").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)))
            }else{
              $("#next").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)+1))
            }

            if(response.products.prev_page_url == null){
              $("#prev").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)))
            }else{
              $("#prev").attr("href", '/pos?page=' + parseInt(parseInt(response.products.current_page)-1))
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
  }

  function search_fetch_data(page){
    var _token = $("input[name=_token]").val();

    var keyword = $('#hiddensearchkeyword').val();
    $.ajax({
        url:"/pagination/search/"+keyword+"/fetch?page="+page,
        method:"POST",
        success:function(response){
           $("#products").empty();

            if(response.products.next_page_url == null){
              $("#next").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)))
            }else{
              $("#next").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)+1))
            }

            if(response.products.prev_page_url == null){
              $("#prev").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)))
            }else{
              $("#prev").attr("href", '/pos/search?page=' + parseInt(parseInt(response.products.current_page)-1))
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
  }

  function category_fetch_data(page){
    var _token = $("input[name=_token]").val();

    var data = $('#hiddencategorysearchkeyword').val();
    $.ajax({
        url:"/pagination/categorysearch/"+data+"/fetch?page="+page,
        method:"POST",
        success:function(response){
           $("#products").empty();

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
  }



});
