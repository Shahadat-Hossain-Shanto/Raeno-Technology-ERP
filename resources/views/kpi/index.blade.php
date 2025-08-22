@extends('layouts.master')
@section('title', 'RAENO :: KPI List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list"></i> KPI List</strong></h5>
                </div>

                {{-- Selection Field --}}
                <div class="container-fluid p-3">
                    <div class="row align-items-center">
                        <!-- Left side: Select + View Data -->
                        <div class="col-md-4 col-sm-12 mb-0 d-flex flex-wrap">
                            <select id="kpiType" class="form-control mr-2 mb-2 mb-md-0 w-auto flex-fill">
                                <option value="">Select KPI Type --</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                            </select>
                            <button id="viewDataBtn" class="btn btn-primary w-sm-100">
                                 Submit
                            </button>
                        </div>

                        <!-- Right side: Add New KPI -->
                        <div class="col-md-8 col-sm-12 text-md-right text-sm-left">
                            <a href="{{ route('kpi.create') }}" class="btn btn-primary w-sm-100">
                                <i class="fas fa-plus"></i> Add New KPI
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table id="kpiTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>KPI For</th>
                                    <th>Type</th>
                                    <th>Month/Year</th>
                                    <th>Target Amount</th>
                                    <th>Criteria</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
              <th>Incentive Rate</th>
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

{{-- DataTable Script --}}
<script>
    $(document).ready(function () {
        let table = $('#kpiTable').DataTable({
            responsive: true,
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + 1 },
                {
                    data: 'kpi_for',
                    render: function(data, type, row) {
                        return row.kpi_type === 'monthly' ? data : '-';
                    }
                },
                { data: 'kpi_type' },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (row.kpi_type === 'monthly') return row.month_year;
                        if (row.kpi_type === 'quarterly') return `Q${row.quarter} - ${row.month_year}`;
                        return '-';
                    }
                },
                {
                    data: 'target_amount',
                    render: function(data, type, row) {
                        return row.kpi_type === 'monthly' ? data : '-';
                    }
                },
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

        $('#viewDataBtn').on('click', function() {
            let type = $('#kpiType').val();
            if (!type) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Selection',
                    text: 'Please select KPI Type',
                    confirmButtonText: 'OK'
                });
                return;
            }

            table.clear().draw();

            $.ajax({
                url: '{{ route('monthly.data') }}',
                type: 'GET',
                data: { type: type },
                success: function(response) {
                    table.rows.add(response.data).draw();
                }
            });
        });
    });

    $(document).on('click', '.viewCriteriaBtn', function () {
        let slabs = $(this).data('slabs');
        let tbody = "";

        slabs.forEach((slab, index) => {
            tbody += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${slab.criteria_percent}%</td>
                    <td>${slab.incentive_rate}</td>
                </tr>
            `;
        });

        $('#criteriaTableBody').html(tbody);
        $('#criteriaModal').modal('show');
    });

    let kpiIdToDelete = null;

    $('#kpiTable').on('click', '.deleteKpiBtn', function() {
        kpiIdToDelete = $(this).data('id');
        $('#deleteConfirmModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (!kpiIdToDelete) return;

        $.ajax({
            url: `/kpi/${kpiIdToDelete}`,
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
                        $('#viewDataBtn').click();
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

</script>
@endsection
