@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Unit</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item">setting</li>
                    <li class="breadcrumb-item active">unit</li>
                </ol>

            </div>
        </div>
    </div>

    <div class="card p-3 rounded">
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="mb-3">
                    <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Buat Unit</a>
                    <a class="btn btn-success" onClick="importData()" href="javascript:void(0)">Import Unit</a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <table class="table table-striped table-bordered yajra-datatable" id="unit">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th width="150px">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Bootstrap unit model -->
<div class="modal fade" id="unit-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="unitModal"></h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="unitForm" name="unitForm" class="form-horizontal"
                    method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="unit" name="unit"
                                placeholder="Nama Unit" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Changes
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- End bootstrap model -->
</div>

<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">IMPORT DATA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('unit.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>PILIH FILE</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                    <button type="submit" class="btn btn-success">IMPORT</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    function importData() {
        $('#import').modal('show');
    }
</script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#unit').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('unit') }}",
            columns: [{
                    data: null,
                    name: 'id',
                    title: 'ID',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Urutan nomor berdasarkan halaman
                    }
                },
                {
                    data: 'unit',
                    name: 'unit'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
    });

    function add() {
        $('#unitForm').trigger("reset");
        $('#unitModal').html("Add unit");
        $('#unit-modal').modal('show');
        $('#id').val('');
    }

    function editFunc(id) {
        $.ajax({
            type: "POST",
            url: "{{ url('edit-unit') }}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#unitModal').html("Edit unit");
                $('#unit-modal').modal('show');
                $('#id').val(res.id);
                $('#unit').val(res.unit);
            }
        });
    }

    function deleteFunc(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Delete this record?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('delete-unit') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#unit').DataTable().ajax.reload(null, false);
                        Swal.fire("Deleted!", "Your record has been deleted.", "success");
                    }
                });
            }
        });
    }
    $('#unitForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ url('store-unit') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#unit-modal").modal('hide');
                $('#unit').DataTable().ajax.reload(null, false);
                Swal.fire("Success!", "unit has been saved successfully.", "success");
            },
            error: function(data) {
                Swal.fire("Error!", "Something went wrong.", "error");
            }
        });
    });
</script>
@endpush
