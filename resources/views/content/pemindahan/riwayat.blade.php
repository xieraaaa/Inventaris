@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Riwayat Pemindahan</h4>
            </div>
            <div class="col-md-7 align-self-center text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Riwayat Pemindahan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="card p-3 rounded">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="mb-3">
                        <h1>Data Pemindahan</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="pemindahan">
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pemindahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Pemindahan</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="asal" class="form-label">Asal</label>
                            <input type="text" class="form-control" id="asal" name="asal" required>
                        </div>
                        <div class="mb-3">
                            <label for="tujuan" class="form-label">Tujuan</label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <input type="hidden" id="editId" name="id">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script defer>
    function createChild(data) {
        return `<span><b>${data.nama_barang}</b>: ${data.jumlah}</span>`;
    }

    function format(data) {
        let str = '';

        for (const barang of data.barang) {
            str += createChild(barang) + '<br />';
        }

        return str;
    }

    const table = $('#pemindahan').DataTable({
        ajax: {
            url    : '/pemindahan/detail', // Endpoint API untuk data pemindahan
            dataSrc: ''
        },
        columns: [
            {
                className     : 'dt-control',
                data          : null,
                orderable     : false,
                defaultContent: '',
                width         : 40
            },
            {
                data      : null,
                name      : 'id',
                title     : 'No.',
                searchable: false,
                render    : function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Urutan nomor
                }
            },
            {
                data : 'tanggal',
                title: 'Tanggal Pemindahan',
                type : 'string'
            },
            {
                data : 'asal',
                title: 'Asal',
                type : 'string'
            },
            {
                data : 'tujuan',
                title: 'Tujuan',
                type : 'string'
            },
            {
                data : 'deskripsi',
                title: 'Deskripsi',
                type : 'string'
            },
            {
                data       : null,
                title      : 'Action',
                orderable  : false,
                render     : function(data) {
                    return `
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${data.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">Hapus</button>
                    `;
                }
            }
        ],
        order: [1, 'asc'] // Mengurutkan berdasarkan kolom No.
    });

    table.on('click', 'td.dt-control', function (evt) {
        const rowElement = evt.target.closest('tr');
        const row = table.row(rowElement);

        if (row.child.isShown()) {
            row.child.hide();
        }
        else {
            row.child(format(row.data()));
            row.child.show();
        }
    });

    // Event handler untuk tombol Edit
    $('#pemindahan').on('click', '.edit-btn', function () {
        const id = $(this).data('id');

        // Ambil data untuk edit
        $.ajax({
            url: `/pemindahan/edit/${id}`,
            type: 'GET',
            success: function(data) {
                $('#editId').val(data.id);
                $('#tanggal').val(data.tanggal);
                $('#asal').val(data.asal);
                $('#tujuan').val(data.tujuan);
                $('#deskripsi').val(data.deskripsi);
                $('#editModal').modal('show'); // Tampilkan modal
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengambil data'
                });
            }
        });
    });

    // Proses update data
    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#editId').val();
        const formData = $(this).serialize();

        $.ajax({
            url: `/pemindahan/update/${id}`,
            type: 'PUT',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Data berhasil diperbarui'
                });
                $('#editModal').modal('hide'); // Tutup modal
                table.ajax.reload(); // Reload data tabel
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memperbarui data'
                });
            }
        });
    });

    // Event handler untuk tombol Hapus
    $('#pemindahan').on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data ini akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url   : `/pemindahan/delete/${id}`,
                    type  : 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: 'Data berhasil dihapus'
                        });
                        table.ajax.reload();
                    },
                    error : function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    });

    // Event handler untuk menambah data
    $('#saveForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize(); // Ambil semua input dari form

        $.ajax({
            url: '/pemindahan/store', // Endpoint untuk menyimpan data
            type: 'POST',
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Data berhasil disimpan'
                });
                $('#addModal').modal('hide'); // Tutup modal tambah data
                table.ajax.reload(); // Reload data tabel
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan data'
                });
            }
        });
    });
</script>
@endpush
