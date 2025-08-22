var count = 1;
    $(document).ready(function () {
        $('#brand_table').DataTable({
            ajax: {
                "url": "/brand-list-data",
                "dataSrc": "brand"
            },
            columns: [
                {
                  "render": function(data, type, full, meta) {
                    return count++;
                  }
                },

                { data: 'brand_name' },
                { data: 'brand_origin', render: checkOrigin },
                { 
                    data: 'brand_logo',
                    render: getImg
                },
                { 
                    data: 'id',
                    render: getBtns
                },
            ],
        });
    });

    function checkOrigin(data, type, full, meta) {

        var origin = data;

        if (origin === null) {
           origin = "N/A"
        } else {
            origin = origin
        }

         return origin;
    }

    function getImg(data, type, full, meta) {

        var brand_logo = data;

        if (brand_logo === null) {
           brand_logo = "default.jpg"
        } else {
            brand_logo = brand_logo
        }

         return '<img src="uploads/brands/'+brand_logo+'" width="50px" height="50px" alt="image" class="rounded-circle">';
    }

    function getBtns(data, type, full, meta) {

        var id = data;
        return '<button type="button" value="'+id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
                <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+id+'"><i class="fas fa-trash"></i></a>';
    }