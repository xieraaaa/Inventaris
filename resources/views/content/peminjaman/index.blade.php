@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Peminjaman Barang</h4>
            </div>
            <div class="col-md-7 align-self-center text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Peminjaman</li>
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
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="updateNamaBarang" class="form-label">Nama Barang</label>
                            <select class="form-select" id="updateNamaBarang" name="nama_barang" required>
                                <option value="">Barang 1</option>
                                <option value="Barang 2">Barang 2</option>
                                <option value="Barang 3">Barang 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateKondisi" class="form-label">Kondisi</label>
                            <select class="form-select" id="updateKondisi" name="kondisi" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <input type="hidden" id="updateId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
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
                url: 'detal/Admin/1',
                dataSrc: ''

            },
            columns: [{
                    className: 'dt-control',
                    data: null,
                    orderable: false,
                    defaultContent: ''
                },
                {
                    data: 'id',
                    title: 'ID'
                },
                {
                    data: 'nama_user',
                    title: 'Nama User'
                },
                {
                    data: 'tgl_pinjam',
                    title: 'Tanggal Peminjaman',
                    orderable: false
                },
                {
                    data: 'tgl_kembali',
                    title: 'Tanggal Pengembalian',
                    orderable: false
                },
                {
                    data: 'keterangan',
                    title: 'Keterangan',
                    orderable: false
                },
                {
                    data: 'status',
                    title: 'Status',
                    orderable: false
                },
                {
                    data: null,
                    title: 'Action',
                    orderable: false,
                    render: function(data, type, row) {
                        return `
            <button class="btn btn-success btn-accept" data-id="${row.id}">
                <i class="fas fa-check"></i> Accept
            </button>
            <button class="btn btn-warning btn-update" data-id="${row.id}">
                <i class="fas fa-rotate "></i> update
            </button>
            <button class="btn btn-success btn-kembali" data-id="${row.id}">
                <i class="fas fa-check"></i> kembali
            </button>`
                    }
                }

            ],
            order: [
                [1, 'asc']
            ]
        });

        peminjamanTable.on('click', 'td.dt-control', evt => {
            const rowElement = evt.target.closest('tr');
            const row = peminjamanTable.row(rowElement);

            if (row.child.isShown()) {
                row.child.hide();
            } else {
                row.child(format(row.data()));
                row.child.show();
            }
        });

        peminjamanTable.on('draw.dt', function () {
            peminjamanTable.rows().every(function () {
                const row = this;
                const data = row.data();
                if (data.status === 'di pinjam') {
                    $(row.node()).find('.btn-accept').hide();
                }else {
                    $(row.node()).find('.btn-kembali').hide();
                }
            });
        });

        peminjamanTable.on('click', '.btn-accept', function() {
    const id = $(this).data('id'); // Ambil ID peminjaman
    const newStatus = 'di pinjam'; // Status baru

    // Tampilkan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Konfirmasi Peminjaman',
        text: "Apakah Anda yakin ingin menyetujui peminjaman ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Pinjamkan!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            // Lanjutkan proses jika pengguna menyetujui
            $.ajax({
                type: 'POST',
                url: `/peminjaman/admin-status/${id}`, // Endpoint Laravel
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                    status: newStatus // Status baru yang akan dikirim
                },
                success: (response) => {
                    Swal.fire("Berhasil!", response.message, "success");
                    peminjamanTable.ajax.reload(null, false); // Reload tabel
                },
                error: (xhr) => {
                    Swal.fire("Gagal!", xhr.responseJSON.error || "Gagal mengubah status.", "error");
                }
            });
        }
    });
});

peminjamanTable.on('click', '.btn-kembali', function() {
    const id = $(this).data('id'); // Ambil ID peminjaman
    const newStatus = 'di kembalikan'; // Status baru

    // Tampilkan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Konfirmasi Pengembalian',
        text: "Apakah Anda yakin barang telah dikembalikan?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            // Lanjutkan proses jika pengguna menyetujui
            $.ajax({
                type: 'POST',
                url: `/peminjaman/kembali-status/${id}`, // Endpoint Laravel
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                    status: newStatus // Status baru yang akan dikirim
                },
                success: (response) => {
                    Swal.fire("Berhasil!", response.message, "success");
                    peminjamanTable.ajax.reload(null, false); // Reload tabel
                },
                error: (xhr) => {
                    Swal.fire("Gagal!", xhr.responseJSON.error || "Gagal mengubah status.", "error");
                }
            });
        }
    });
});
peminjamanTable.on('click', '.btn-update', function() {
    const id = $(this).data('id'); // Ambil ID peminjaman
    $('#updateId').val(id); // Set ID ke input hidden
    $('#updateModal').modal('show'); // Tampilkan modal
});

// Form submission
$('#updateForm').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    const id = $('#updateId').val();
    const status = $('#updateStatus').val();

    $.ajax({
        type: 'POST',
        url: `/peminjaman/update-status/${id}`, // Endpoint Laravel untuk update
        data: {
            _token: '{{ csrf_token() }}', // Token CSRF
            status: status
        },
        success: (response) => {
            Swal.fire("Berhasil!", response.message, "success");
            $('#updateModal').modal('hide'); // Tutup modal
            peminjamanTable.ajax.reload(null, false); // Reload tabel
        },
        error: (xhr) => {
            Swal.fire("Gagal!", xhr.responseJSON.error || "Gagal mengupdate data.", "error");
        }
    });
});


    </script>
@endpush
