$(document).ready(function () {
    var t = $('#product_table').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: 0
            }
        },
        ajax: {
            url: "/product-list-data",
            dataSrc: "product"
        },
        columns: [
            {
                data: null,
                className: 'dtr-control text-center',
                orderable: false,
                searchable: false
            },
            { data: 'productName', className: 'text-center' },
            { data: 'category_name', className: 'text-center' },
            { data: 'subcategory_name', className: 'text-center' },
            { data: 'brand', className: 'text-center' },
            { data: 'model', className: 'text-center' },

            // Optional image column
            /*
            {
                data: 'productImage',
                render: function(data, type, row) {
                    return `<img src="/uploads/products/${data}" width="100" height="100" alt="Product" onerror="imgErrorProduct(this)">`;
                },
                className: 'text-center'
            },
            */

            {
                render: getBtns,
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                searchable: false,
                orderable: false
            }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

function getBtns(data, type, row, meta) {

    var id = row.id;
    var product_id = row.product_id;

    return '<a type="button" class="edit_btn btn btn-secondary btn-sm" href="/product-edit/'+id+'"><i class="fas fa-edit"></i></a>\
    <a title="Details" type="button" class="details_btn btn btn-info btn-sm" href="/product-details/'+id+'"><i class="fas fa-info-circle"></i></a>';
{/* <a type="button" class="stock_btn btn btn-info btn-sm" href="/product-stock-create/'+product_id+'/'+id+'"><i class="fas fa-cubes"></i></a>\ */}
}


function imgErrorProduct(image) {
    image.onerror = "";
    image.src = "/uploads/products/default.jpg";
    return true;
}

function imgErrorVariant(image) {
    image.onerror = "";
    image.src = "/uploads/variants/default.jpg";
    return true;
}
