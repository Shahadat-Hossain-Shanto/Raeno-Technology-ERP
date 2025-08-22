dataTableX()
function dataTableX() {
    $(document).ready(function () {


        $('#due_table_details').DataTable({
            pageLength: 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                //debugger;
                var index = iDisplayIndexFull + 1;
                $("td:first", nRow).html(index);
                return nRow;
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        })
    });
}
