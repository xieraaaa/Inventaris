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
                    <!-- Dropdown for Peminjaman -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-alt"></i> Peminjaman
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a id="create-peminjaman" class="dropdown-item">
                                    <i class="fas fa-file-alt"></i> Buat Laporan Peminjaman
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.peminjaman.export') }}" class="dropdown-item">
                                    <i class="fas fa-file-excel"></i> Export ke Excel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.peminjaman.pdf') }}" class="dropdown-item">
                                    <i class="fas fa-file-pdf"></i> Export ke PDF
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Dropdown for Pemindahan -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-alt"></i> Pemindahan
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a id="create-pemindahan" class="dropdown-item">
                                    <i class="fas fa-file-alt"></i> Buat Laporan Pemindahan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.pemindahan.export') }}" class="dropdown-item">
                                    <i class="fas fa-file-excel"></i> Export ke Excel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.pemindahan.pdf') }}" class="dropdown-item">
                                    <i class="fas fa-file-pdf"></i> Export ke PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
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