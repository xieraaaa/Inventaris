@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Riwayat Peminjaman</h4>
            </div>
            <div class="col-md-7 align-self-center text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Riwayat Peminjaman</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="card p-3 rounded">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="mb-3">
                        <h1>Data Peminjaman</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="peminjaman">
                </table>
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
    
        const table = $('#peminjaman').DataTable({
            ajax: {
                url    : `peminjaman/riwayat`,
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
                        return meta.row + meta.settings._iDisplayStart + 1; // Urutan nomor berdasarkan halaman
                    }
                },
                {
                    data : 'keterangan',
                    title: 'Keterangan'
                },
                {
                    data : 'tgl_pinjam',
                    title: 'Tanggal Pinjam'
                }
            ],
            order: [1, 'asc']
        });

        table.on('click', 'td.dt-control', evt => {
            const rowElement = evt.target.closest('tr');
            const row        = table.row(rowElement);

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
