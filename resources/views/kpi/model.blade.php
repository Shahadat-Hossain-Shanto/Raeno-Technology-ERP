@extends('layouts.master')
@section('title', 'RAENO :: Model Based KPI')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list"></i> Model Based KPI</strong></h5>
                </div>
                <div class="d-flex justify-content-end" style="padding-top: 10px;padding-right: 30px;">
                    <a href="{{ route('kpi.create') }}" class="btn btn-primary">Add New KPI</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="modelTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Model</th>
                                    <th>Month/Year</th>
                                    <th>KPI For</th>
                                    <th>Target Quantity</th>
                                    <th>Criteria</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Criteria Modal -->
<div class="modal fade" id="criteriaModal" tabindex="-1" aria-labelledby="criteriaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criteriaModalLabel">KPI Criteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Criteria (%)</th>
              <th>Incentive Amount</th>
            </tr>
          </thead>
          <tbody id="criteriaTableBody">
            <!-- Filled dynamically -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
{{-- delete --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this KPI?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $('#modelTable').DataTable({
            ajax: {
                url: '{{ route('model.data') }}',
                dataSrc: 'data'
            },
            responsive: true,
            columns: [
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'model_name' },
                { data: 'month_year' },
                { data: 'kpi_for' },
                { data: 'target_quantity' },
                {
                    data: 'slabs',
                    render: function(data, type, row) {
                        // View Criteria button
                        let viewBtn = (!data || data.length === 0)
                            ? `<button class="btn btn-secondary btn-sm" disabled>No Criteria</button>`
                            : `<button class="btn btn-info btn-sm viewCriteriaBtn" data-slabs='${JSON.stringify(data)}'>
                                    <i class="fas fa-eye"></i> View
                            </button>`;

                        // Delete button
                        let deleteBtn = `<button class="btn btn-danger btn-sm ml-1 deleteKpiBtn" data-id='${row.id}'>
                                            <i class="fas fa-trash"></i>
                                        </button>`;

                        return viewBtn + deleteBtn;
                    }
                }
            ]
        });
    $(document).on('click', '.viewCriteriaBtn', function () {
        let slabs = $(this).data('slabs');
        let tbody = "";

        slabs.forEach((slab, index) => {
            tbody += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${slab.criteria_percent}%</td>
                    <td>${slab.incentive_amount}</td>
                </tr>
            `;
        });

        $('#criteriaTableBody').html(tbody);
        $('#criteriaModal').modal('show');
    });

    let kpiIdToDelete = null;

    $('#modelTable').on('click', '.deleteKpiBtn', function() {
        kpiIdToDelete = $(this).data('id');
        $('#deleteConfirmModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (!kpiIdToDelete) return;

        $.ajax({
            url: `/kpi-model/${kpiIdToDelete}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteConfirmModal').modal('hide');

                // Check if the response has success status
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Refresh the DataTable instead of full page reload
                        $('#modelTable').DataTable().ajax.reload(null, false);
                    });
                }
            },
            error: function(xhr) {
                $('#deleteConfirmModal').modal('hide');
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Something went wrong!';

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage,
                });
            }
        });
    });
        // Auto-hide success message
        const alertBox = document.querySelector('.alert-dismissible');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection
