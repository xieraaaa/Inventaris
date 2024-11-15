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
        <tbody>
            <!-- Data akan dimuat di sini melalui Ajax -->
        </tbody>
        
        
    </table>
    </div>
    </div>


</div>
@endsection
@push('scripts')
<script>
    // Fungsi untuk mengambil data peminjaman dan menampilkannya di tabel
    function loadPeminjaman() {
        $.ajax({
            url: '/peminjaman',  // URL untuk mengambil data
            method: 'GET',
            
            success: function(data) {
                let rows = '';
                data.forEach(function(item) {
                    rows += `<tr>
                        <td>${item.id}</td>
                        <td>${item.id_user}</td>
                        <td>${item.id_barang}</td>
                        <td>${item.tgl_pinjam}</td>
                        <td>${item.tgl_kembali}</td>
                        <td>${item.status}</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="updateStatus(${item.id}, 'accepted')"></button>
                            <button class="btn btn-danger btn-sm" onclick="updateStatus(${item.id}, 'rejected')"></button>
                        </td>
                    </tr>`;
                });
                $('#peminjaman-table tbody').html(rows);
            }
        });
    }

    // Fungsi untuk mengubah status peminjaman (approve/reject)
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
                        loadPeminjaman(); // Reload tabel setelah update
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui status', 'error');
                    }
                });
            }
        });
    }

    // Load data peminjaman saat halaman dimuat
    $(document).ready(function() {
        loadPeminjaman();
    });
</script>

@endpush
</body>
</html>
