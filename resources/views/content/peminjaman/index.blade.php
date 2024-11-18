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


    </script>


@endpush
