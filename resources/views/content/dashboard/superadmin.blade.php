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
        <table class="table table-striped table-bordered yajra-datatable" id="peminjaman-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#peminjaman-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: `/peminjaman`,
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nama_user', name: 'nama_user' },
                { data: 'nama_barang', name: 'nama_barang' },
                { data: 'tgl_pinjam', name: 'tgl_pinjam' },
                { data: 'tgl_kembali', name: 'tgl_kembali' },
                { data: 'status', name: 'status' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ]
        });
    });

    function updateStatus(id, status) {
        Swal.fire({ 
            title: 'Konfirmasi',
            text: `Apakah Anda yakin ingin ${status} peminjaman ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/peminjaman/${id}/update-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', response.message, 'success');
                        $('#peminjaman-table').DataTable().ajax.reload(); // Reload tabel setelah update
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui status', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
