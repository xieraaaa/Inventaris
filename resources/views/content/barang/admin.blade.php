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
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Buat Barang</a>
                        <a class="btn btn-success" onClick="importData()" href="javascript:void(0)">Import Barang</a>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="card-body">
                <table class="table table-striped table-bordered yajra-datatable" id="barang"></table>
            </div>
        </div>
    </div>

    <!-- Modal import data -->
    <!--
        BEGIN
        Modal Import Data
    -->
    <div class="modal fade" id="import-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Excel</h4>
                </div>
                <div class="modal-body">
                    <form name="barangForm" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('barang.import') }}">
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

    <div class="modal fade" id="add-unit-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Unit</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="add-unit-form" name="add-unit-form" class="form-horizontal">
                    <input type="hidden"  name="id" id="id_barang_id">
                        <input type="hidden"  name="id_barang" id="id_barang">
                        <div class="form-group">
                            <label for="kode_inventaris" class="form-label">Kode Inventaris</label>
                            <input type="text" class="form-control" id="kode_inventaris" name="kode_inventaris" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                        </div>
                        
                        <div class="form-group mt-3">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <select class="form-control" id="kondisi" name="kondisi" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Tidak Tersedia">tidak tersedia</option>
                            </select>
                        </div>
                        <div class="form-group mt-4 text-end">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <!--
        END
        Modal Import Data
    -->

    <!-- Modal barang -->
    <div class="modal fade" id="barang-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="barangModal"></h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="barangForm" name="barangForm" class="form-horizontal"
                        method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="idModal">
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

    <template id="detail-barang-template">
        <table class="table">
            <thead>
                <th>Kode Inventaris</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Tanggal Inventaris</th>
                <th>action</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </template>
@endsection

@push('scripts')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function importData() {
                $('#import-modal').modal('show');
            }
        </script>


    <script type="text/javascript">
        function format(data) {
			console.log(data);

            const table = document.getElementById('detail-barang-template').content.cloneNode(true);
            const body = table.querySelector('tbody');

            for (const unitBarang of data['unitBarang']) {
                const row = document.createElement('tr');

                const inventory_code = document.createElement('td');
                inventory_code.innerText = unitBarang.kode_inventaris;

                const location = document.createElement('td');
                location.innerText = unitBarang.lokasi;

                const condition = document.createElement('td');
                condition.innerText = unitBarang.kondisi;

                const inventory_date = document.createElement('td');
                inventory_date.innerText = unitBarang.tanggal_inventaris;

                const action = document.createElement('td');
                action.innerHTML = `
                    <button type="button" class="btn btn-primary btn-sm edit-unit-btn" data-id="${unitBarang.id}" data-id_barang="${unitBarang.id_barang}" data-bs-toggle="modal" data-bs-target="#edit-unit-modal">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm delete-unit-btn" data-id="${unitBarang.id}" data-id_barang="${unitBarang.id_barang}">Hapus</button>
                `;

                row.appendChild(inventory_code);
                row.appendChild(location);
                row.appendChild(condition);
                row.appendChild(inventory_date);
                row.appendChild(action);

                body.appendChild(row);
            }
            return table;
        }

        $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const peminjamanTable = $('#barang').DataTable({
        processing: true,    // Show processing indicator while fetching data
        serverSide: true,    // Enable server-side processing
        ajax: {
            url: "{{ url('barang/getDatatables') }}",  // URL for server-side data
            type: 'GET',     // Request method
            dataSrc: function(data) {
                return data.data;  // DataTables expects this key for row data
            }
        },
        columns: [
            {
                data          : null,
                class         : 'dt-control',
                defaultContent: '',
                orderable     : false,
                searchable    : false
            },
            {
                data      : null,
                title     : 'ID',
                orderable : false,
                searchable: false,
                render    : function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Page-adjusted index
                }
            },
            {
                data : 'nama_barang',
                title: 'Nama Barang',
            },
            {
                data : 'kategori',
                title: 'Kategori',
            },
            {
                data : 'unit',
                title: 'Unit',
            },
            {
                data : 'merek',
                title: 'Merek',
            },
            {
                data : 'jumlah',
                title: 'Jumlah',
            },
            {
                data: null,
                title: 'Actions',
                orderable: false,
                searchable: false,
                render: function(data) {

                   
                    return `                    <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton` + data.id + `" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton` + data.id + `">
                                <a style="cursor: pointer;" class="user-select-none dropdown-item add-unit-btn" data-id="${data.id}"><i class="fa-solid fa-plus"></i> Tambah Unit</a>
                                <a style="cursor: pointer;" class="user-select-none dropdown-item edit-btn" data-id="${data.id}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <a style="cursor: pointer;" class="user-select-none dropdown-item delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash"></i> Hapus</a>
                            </div>
                        </div>`;
                            
                }
            }
        ],
        order: [[1, 'asc']],  // Default order by ID
        pageLength: 10,  // Set the number of records per page
        lengthMenu: [10, 25, 50, 100],  // Options for records per page
        pagination: true,  // Ensure pagination is enabled
        processing: true,  // Show loading indicator
        deferRender: true,  // Optimizes performance for large datasets
    });



            peminjamanTable.on('click', 'td.dt-control', ({ target }) => {
                const rowElement = target.closest('tr');
                const row        = peminjamanTable.row(rowElement);

                if (row.child.isShown()) {
                    row.child.hide();
                }
                else {
                    row.child(format(row.data()));
                    row.child.show();
                }
            });
        });

        function add() {
            $('#barangForm').trigger("reset");
            $('#barangModal').html("Add barang");
            $('#barang-modal').modal('show');
            $('#id').val('');
        }

        $(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    editFunc(id);
});
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
                    $('#idModal').val(res.id);
                    $('#nama_barang').val(res.nama_barang);
                    $('#id_kategori').val(res.id_kategori);
                    $('#id_unit').val(res.id_unit);
                    $('#id_merek').val(res.id_merek);
                    $('#jumlah').val(res.jumlah);
                }
            });
        }

        $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        deleteFunc(id);
 });

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

        $(document).ready(function() {
    // Event untuk membuka modal Tambah Unit
    $('#barang').on('click', '.add-unit-btn', function() {
        const id = $(this).data('id');
    $('#id_barang').val(id);


        $('#add-unit-modal').modal('show');
    });

    // Event untuk submit form Tambah Unit
    $('#add-unit-form').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: "{{ url('add-unit') }}",
            data: formData,
            success: (res) => {
                
                $('#id_barang').val(res.id_barang);
                $('#kode_inventaris').val(res.kode_inventaris);
                $('#lokasi').val(res.lokasi);
                $('#kondisi').val(res.kondisi);
                
                $('#add-unit-modal').modal('hide');
                $('#barang').DataTable().ajax.reload(null, false);
                Swal.fire("Success!", "Unit berhasil ditambahkan.", "success");
            },
            error: (error) => {
                Swal.fire("Error!", "Terjadi kesalahan.", "error");
            }
        });
    });
});


       $('#barangForm').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: 'POST',
        url: "{{ url('store-barang') }}", // Pastikan URL ini benar
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#barang-modal").modal('hide'); // Tutup modal
            $('#barang').DataTable().ajax.reload(null, false); // Refresh DataTable
            Swal.fire("Success!", "Barang telah disimpan.", "success"); // Tampilkan pesan sukses
        },
        error: function(data) {
            Swal.fire("Error!", "Terjadi kesalahan saat menyimpan data.", "error"); // Tampilkan pesan error
        }
    });
});


$(document).on('click', '.edit-unit-btn', function() {
    const id = $(this).data('id');
    const id_barang = $(this).data('id_barang');
    $('#id_barang').val(id_barang);
    $('#id_barang_id').val(id);
    editunitFunc(id);
});
        function editunitFunc(id) {
            $.ajax({
                type: "POST",
                url: `/edit-unit-barang/${id}`,
               
                
                success: function(res) {
                    $('#unitModal').html("Edit unit");
                    $('#add-unit-modal').modal('show');
                    $('#idModal').val(res.id);
                    $('#kode_inventaris').val(res.kode_inventaris);
                    $('#lokasi').val(res.lokasi);
                    $('#kondisi').val(res.kondisi);
                    $('#tanggal_inventaris').val(res.tanggal_inventaris);
                }
            });
        }


$(document).on('click', '.delete-unit-btn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST', // Gunakan metode POST
                url: `/delete-unit/${id}`,
                success: function (res) {
                    if (res.success) {
                        Swal.fire("Berhasil!", res.message, "success");
                        location.reload(); 
                    } else {
                        Swal.fire("Error!", res.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error!", "Terjadi kesalahan saat menghapus data.", "error");
                }
            });
        }
    });
}); 
    </script>

@endpush
