@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Barang</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Barang</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card p-3 rounded">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <div class="card-body">
            <table class="table table-striped table-bordered yajra-datatable" id="barang"></table>
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
                            <label for="kode_barang" class="col-sm-8 mb-2 control-label">Kode Barang</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="kode_barang" name="kode_barang"
                                    placeholder="kode barang" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_barang" class="col-sm-8 mb-2 control-label">Nama Barang</label>
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
                            <label for="id_unit" class="col-sm-8 mb-2 control-label">Unit</label>
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
                            <label for="id_merek" class="col-sm-8 mb-2 control-label">Merek</label>
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
                            <label for="jumlah" class="col-sm-8 mb-2 control-label">jumlah</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="jumlah" name="jumlah"
                                    placeholder="jumlah stock" maxlength="50" required="">
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
                            <label for="keterangan" class="col-sm-8 mb-2 control-label">Keterangan</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    placeholder="keterangan" maxlength="50" required="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save Changes</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

    <!-- Modal import data -->
    <div class="modal fade" id="import-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Excel</h4>
                </div>
                <div class="modal-body">
                    <form id="barangForm" name="barangForm" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('barang.import') }}">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="kode_barang" class="col-sm-8 mb-2 control-label">Data</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="data_excel" name="data_excel" required="" />
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save">Upload</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
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

        $('#barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('dashboard') }}",
                type: 'GET',
                dataSrc: function(json) {
                    console.log(json); // Inspect JSON structure to confirm data mapping
                    return json.data || []; // Ensure the data source path is correct
                }
            },
            responsive: true,
            autoWidth: true, // Allow automatic column width adjustments
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
                    data: 'nama_barang',
                    name: 'nama_barang',
                    title: 'Nama Barang',
                },
                {
                    data: 'merek',
                    name: 'merek',
                    title: 'Merek',
                    orderable: false
                },
                {
                    data: 'kondisi_label',
                    name: 'kondisi',
                    title: 'Kondisi',
                    orderable: false
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                    title: 'Jumlah',


                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                    title: 'Keterangan',
                    orderable: false
                },

                {
                    data: 'action',
                    name: 'action',
                    title: 'Action',
                    orderable: false
                }

            ],
            order: [
                [1, 'asc']
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
                $('#jumlah').val(res.jumlah);
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

    function importData() {
        $('#import-modal').modal('show');
    }
</script>
@endpush