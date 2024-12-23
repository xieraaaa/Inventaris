@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Dashboard</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card p-3 rounded">
        <table class="table table-striped table-bordered yajra-datatable" id="peminjaman-table">
            <thead>
                <tr>
                    {{--
                    <th>ID</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    --}}
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script>
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

        const peminjamanTable = $('#peminjaman-table').DataTable({
            ajax: {
                url: 'peminjaman/detail',
                dataSrc: ''
            },
            columns: [
                {
                    className     : 'dt-control',
                    data          : null,
                    orderable     : false,
                    defaultContent: ''
                },
                {
                    data : 'id',
                    title: 'ID'
                },
                {
                    data : 'nama_user',
                    title: 'Nama User'
                },
                {
                    data     : 'tgl_pinjam',
                    title    : 'Tanggal Peminjaman',
                    orderable: false
                },
                {
                    data     : 'tgl_kembali',
                    title    : 'Tanggal Pengembalian',
                    orderable: false
                },
                {
                    data     : 'keterangan',
                    title    : 'Keterangan',
                    orderable: false
                },
                {
                    data     : 'status',
                    title    : 'Status',
                    orderable: false
                },
                {
                    data     : null,
                    title    : 'Action',
                    orderable: false,
                    render   : function(data, type, row) {
                        return `
                            <button class="btn btn-success btn-accept" data-id="${row.id}">
                                <i class="fas fa-check"></i> Accept
                            </button>
                            <button class="btn btn-danger btn-reject" data-id="${row.id}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        `;
                    }
                }
            ],
            order: [[1, 'asc']]
        });

        peminjamanTable.on('click', 'td.dt-control', evt => {
            const rowElement = evt.target.closest('tr');
            const row        = peminjamanTable.row(rowElement);

            if (row.child.isShown()) {
                row.child.hide();
            }
            else {
                row.child(format(row.data()));
                row.child.show();
            }
        });


        peminjamanTable.on('click', '.btn-accept', function () {
            const id = $(this).data('id'); // Ambil ID peminjaman

            // Tampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Konfirmasi Peminjaman',
                text: "Apakah Anda yakin ingin menyetujui peminjaman ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // TODO Ambil kode status dari tabel status di database
                    // 2 = "Approved"
                    const newStatus = 2;

                    $.ajax({
                        type: 'POST',
                        url: `/peminjaman/update-status/${id}`, // Endpoint Laravel
                        data: {
                            _token: '{{ csrf_token() }}', // Token CSRF
                            status: newStatus // Status baru yang akan dikirim
                        },
                        success: (response) => {
                            // Tampilkan pesan berhasil
                            Swal.fire("Berhasil!", response.message, "success");
                            peminjamanTable.ajax.reload(null, false); // Reload tabel
                        },
                        error: (xhr) => {
                            // Tampilkan pesan gagal
                            Swal.fire("Gagal!", xhr.responseJSON.error || "Gagal menyetujui peminjaman.", "error");
                        }
                    });
                }
            });
        });

        peminjamanTable.on('click', '.btn-reject', function() {
            const id = $(this).data('id'); // Ambil ID peminjaman

            $.ajax({
                type: 'POST',
                url: `/peminjaman/reject/${id}`, // Endpoint Laravel
                data: {
                    _token: '{{ csrf_token() }}' // Token CSRF
                },
                success: (response) => {
                    Swal.fire("Success!", response.message, "success");
                    peminjamanTable.ajax.reload(null, false); // Reload tabel
                },
                error: (xhr) => {
                    Swal.fire("Error!", xhr.responseJSON.error || "Failed to delete peminjaman.", "error");
                }
            });
        });
    </script>
@endpush
