@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">barang</h4>
            </div>
            <div class="col-md-7 align-self-center text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">barang</li>
                    </ol>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Create barang</a>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <div class="card-body">
                <table class="table table-striped table-bordered yajra-datatable" id="barang">
                    <thead>
                        <tr>
                            <th width="10px">Id</th>
                            <th width="10px">kode barang</th>
                            <th width="80px">nama barang</th>
                            <th width="80px">Kategori</th>
                            <th width="100px">Unit</th>
                            <th width="60px">merek</th>
                            <th width="190px">kondisi</th>
                            <th width="80px">keterangan</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
        <!-- Bootstrap barang model -->
        <div class="modal fade" id="barang-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="barangModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" id="barangForm" name="barangForm" class="form-horizontal"
                            method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label for="kode_barang" class="col-sm-8 mb-2 control-label">kode barang</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" id="kode_barang" name="kode_barang"
                                        placeholder="kode barang" maxlength="50" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_barang" class="col-sm-8 mb-2 control-label">nama barang</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                        placeholder="nama barang" maxlength="50" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_kategori" class="col-sm-8 mb-2 control-label">Kategori</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="id_kategori" name="id_kategori" required>
                                        <option value="">-- Select Kategori --</option>
                                        @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_unit" class="col-sm-8 mb-2 control-label">unit</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="id_unit" name="id_unit" required>
                                        <option value="">-- Select unit --</option>
                                        @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_merek" class="col-sm-8 mb-2 control-label">merek</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="id_merek" name="id_merek" required>
                                        <option value="">-- Select merek --</option>
                                        @foreach ($mereks as $merek)
                                        <option value="{{ $merek->id }}">{{ $merek->merek }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kondisi" class="col-sm-8 mb-2 control-label">Kondisi</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="kondisi" name="kondisi" required>
                                        <option value="">-- Select Kondisi --</option>
                                        <option value="1">Baik</option>
                                        <option value="0">Rusak</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="keterangan" class="col-sm-8 mb-2 control-label">keterangan</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan"
                                        placeholder="keterangan" maxlength="50" required="">
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

            $('#barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('barang') }}",
                    type: 'GET',
                    dataSrc: function(json) {
                        console.log(json); // Inspect JSON structure to confirm data mapping
                        return json.data || []; // Ensure the data source path is correct
                    }
                },
                responsive: true,
                autoWidth: true, // Allow automatic column width adjustments
                columns: [{
                        data: 'id',
                        name: 'id',
                        title: 'Id',
                        width: '10px'
                    },
                    {
                        data: 'kode_barang',
                        name: 'kode_barang',
                        title: 'kode_barang',
                        width: '100px'
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang',
                        title: 'nama_barang',
                        width: '80px'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                        title: 'kategori',
                        width: '80px'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        title: 'unit',
                        width: '100px'
                    },
                    {
                        data: 'merek',
                        name: 'merek',
                        title: 'merek',
                        width: '60px'
                    },
                    {
                        data: 'kondisi_label',
                        name: 'kondisi_label',
                        title: 'kondisi',
                        width: '60px'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        title: 'keterangan',
                        width: '80px'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        title: 'Action',
                        width: '100px',
                        orderable: false
                    }

                ],
                order: [
                    [0, 'desc']
                ],
                scrollX: true
            });
        });


        function add() {
            $('#barangForm').trigger("reset");
            $('#barangModal').html("Add barang");
            $('#barang-modal').modal('show');
            $('#id').val('');
        }

        function editFunc(id) {
            $.ajax({
                type: "POST",
                url: "{{ url('edit-barang') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    $('#barangModal').html("Edit barang");
                    $('#barang-modal').modal('show');
                    $('#id').val(res.id);
                    $('#kode_barang').val(res.kode_barang);
                    $('#nama_barang').val(res.nama_barang);
                    $('#id_kategori').val(res.id_kategori);
                    $('#id_unit').val(res.id_unit);
                    $('#id_merek').val(res.id_merek);
                    $('#kondisi').val(res.kondisi);
                    $('#keterangan').val(res.keterangan);
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
                        url: "{{ url('delete-barang') }}",
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#barang').DataTable().ajax.reload(null, false);
                            Swal.fire("Deleted!", "Your record has been deleted.", "success");
                        }
                    });
                }
            });
        }

        $('#barangForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('store-barang') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#barang-modal").modal('hide');
                    $('#barang').DataTable().ajax.reload(null, false);
                    Swal.fire("Success!", "barang has been saved successfully.", "success");
                },
                error: function(data) {
                    Swal.fire("Error!", "Something went wrong.", "error");
                }
            });
        });
    </script>
    @endpush