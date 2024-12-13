@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Laporan Data</h4>
            </div>
        </div>

        <div class="card p-3 rounded">
            <div class="row mt-2">
                <div class="col-md-12">
                                    <div class="mb-3">
                        <a id="create-peminjaman" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Buat Laporan Peminjaman
                        </a>
                        <a id="create-pemindahan" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Buat Laporan Pemindahan
                        </a>
                        <a href="{{ route('laporan.peminjaman.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Peminjaman ke Excel
                        </a>
                        <a href="{{ route('laporan.pemindahan.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Pemindahan ke Excel
                        </a>
                        <a href="{{ route('laporan.peminjaman.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export Peminjaman ke PDF
                        </a>
                        <a href="{{ route('laporan.pemindahan.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export Pemindahan ke PDF
                        </a>
                    </div>
                </div>
            </div>
            <div id="laporan" class="row">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<link href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css')}}" rel="stylesheet">

    <script>
        const laporan = $('#laporan');
        
        $('#create-peminjaman').on('click', () => {
            $.ajax({
                url: "{{ route('laporan.peminjaman') }}",
                success: (data) => {
                    laporan.html(data);
                }
            });
        });

        $('#create-pemindahan').on('click', () => {
            $.ajax({
                url: "{{ route('laporan.pemindahan') }}",
                success: (data) => {
                    laporan.html(data);
                }
            });
        });
    </script>
@endpush
