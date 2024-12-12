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
                        <a id="create-peminjaman" class="btn btn-primary">Buat Laporan Peminjaman</a>
                        <a id="create-pemindahan" class="btn btn-primary">Buat Laporan Pemindahan</a>
                    </div>
                </div>
            </div>
            <div id="laporan" class="row">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
