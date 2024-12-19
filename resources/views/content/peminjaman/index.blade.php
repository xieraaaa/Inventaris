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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div id="education_fields">
                            <div class="row education_fields" id="education_fields_0">
                                <div class="col-sm-3 nopadding">
                                    <div class="form-group">
                                        <select class="updateModal__selectBarang form-control" name="barang[]">
                                            <option>-- Nama Barang --</option>
                                            {{-- Diisi secara dinamis --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 nopadding">
                                    <div class="form-group">
                                        <input id="unavailable-amount" type="number" class="form-control" name="number[0]" value="1" placeholder="Jumlah" />
                                    </div>
                                </div>
                                <div class="col-sm-3 nopadding">
                                    <div class="form-group">
                                        <select  class="form-control" style="cursor: pointer;" placeholder="Keterangan">
                                            <option value="">--- KETERANGAN ---</option>
                                            <option value="kosong">Barang tidak tersedia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 nopadding">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <button class="btn btn-success text-white" type="button" onclick="education_fields();"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Skrip untuk menambahkan elemen --}}
    <script type="text/javascript" src="dff.js"></script>
    <script type="text/javascript" src="assets/node_modules/multiselect/js/jquery.multi-select.js"></script>

    {{-- Skrip untuk mengisi data modal --}}
    <script>
        let updateModalLength = 1;
        let updateModalMaxLength = 0;
        let updateModalBarangSelectData = [];
        let updateModalBarangSelectDataUsed = [];

        function populateUpdateModal(number) {
            {{-- Reset list barang --}}
            $(`#education_fields_${number} .updateModal__selectBarang`).html('<option>-- Nama Barang --</option>');

            for (const name of updateModalBarangSelectData) {
                if (updateModalBarangSelectDataUsed.includes(name)) {
                    continue;
                }

                const htmlString = `<option>${ name }</option>`;

                $(htmlString).appendTo(`#education_fields_${number} .updateModal__selectBarang`);
            }
        }

        $('.updateModal__selectBarang').on('change', (evt) => {
            const currentTarget = evt.currentTarget;
            const previousValue = currentTarget.dataset.previousValue;
            const currentValue = currentTarget.value;

            if (previousValue === currentValue) {
                return;
            }
            else if (typeof(previousValue) === 'undefined') {
                updateModalBarangSelectDataUsed.push(currentValue);
                currentTarget.dataset.previousValue = currentValue;
            }
            else {
                const indexOfValue = updateModalBarangSelectDataUsed.indexOf(previousValue);
                updateModalBarangSelectDataUsed.splice(indexOfValue, 1, currentValue);
            }
        });
    </script>

    <script>
        console.log('Loaded index.blade.php script');
        
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
            columns: [
                {
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
                                <i class="fas fa-check"></i> perbarui
                            </button>

                            <button class="btn btn-success btn-kembali" data-id="${row.id}">
                                <i class="fas fa-check"></i> Kembali
                            </button>
                        `
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
                if (data.status === 4) {
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

        function showUpdateModal(id) {
            $('#updateModal').modal('show');
        }

        peminjamanTable.on('click', '.btn-update', function() {
            const id = $(this).data('id'); // Ambil ID peminjaman
            $('#updateId').val(id); // Set ID ke input hidden

            $.ajax({
                url: '{{ route("peminjaman.fetchBarang") }}',
                data: {
                    id: id,
                    filter: 'nama_barang'
                },

                success: data => {
                    updateModalMaxLength = data.length;
                    updateModalBarangSelectData = data.map(item => item.nama_barang.trim());
                    populateUpdateModal(0);
                }
            });

            showUpdateModal(id);
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

        $('#updateModal').on('hide.bs.modal', () => {
            console.log('loc#358');
            
            for (let index = 1; index < updateModalLength; ++index) {
                remove_education_fields(index);
            }

            updateModalBarangSelectDataUsed = [];

            console.assert(updateModalLength === 1);
        });
        
        // Sinkronisasi
        $(window).on('load', () => {
            Echo.private('admin')
                .listen('NewApprovedPeminjaman', () => {
                    peminjamanTable.ajax.reload(null, false);
                })
                .error((err) => {
                    console.log(`Error:`);
                    console.log(err);
                });
        });
    </script>
@endpush
