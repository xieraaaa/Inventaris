@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">merek</h4>
            </div>
            <div class="col-md-7 align-self-center text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item">setting</li>
                        <li class="breadcrumb-item active">merek</li>
                    </ol>
                   
                </div>
            </div>
        </div>

        <div class="card p-3 rounded">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Create merek</a>
                    </div>
                </div>


                
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
                <table class="table table-striped table-bordered yajra-datatable" id="merek">
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
        <!-- Bootstrap merek model -->
        <div class="modal fade" id="merek-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="merekModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" id="merekForm" name="merekForm" class="form-horizontal"
                            method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="merek" name="merek"
                                        placeholder="merek Name" maxlength="50" required="">
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

    </div>

@endsection

@push('scripts')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#merek').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('merek') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'merek',
                        name: 'merek'
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
            $('#merekForm').trigger("reset");
            $('#merekModal').html("Add merek");
            $('#merek-modal').modal('show');
            $('#id').val('');
        }
        function editFunc(id) {
            $.ajax({
                type: "POST",
                url: "{{ url('edit-merek') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    $('#merekModal').html("Edit merek");
                    $('#merek-modal').modal('show');
                    $('#id').val(res.id);
                    $('#merek').val(res.merek);
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
                        url: "{{ url('delete-merek') }}",
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#merek').DataTable().ajax.reload(null, false);
                            Swal.fire("Deleted!", "Your record has been deleted.", "success");
                        }
                    });
                }
            });
        }
        $('#merekForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('store-merek') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#merek-modal").modal('hide');
                    $('#merek').DataTable().ajax.reload(null, false);
                    Swal.fire("Success!", "merek has been saved successfully.", "success");
                },
                error: function(data) {
                    Swal.fire("Error!", "Something went wrong.", "error");
                }
            });
        });
    </script>
@endpush
