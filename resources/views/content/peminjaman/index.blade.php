@extends('layouts.admin')

@section('content')


    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Peminjaman</h4>
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
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="mb-3">
                        <h1>Data Peminjaman</h1>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <div class="card-body">
                <table class="table table-striped table-bordered yajra-datatable" id="peminjaman">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Nama Peminjam</th>
                            <th>Tanggal Pinjam</th>                       
                            <th>Tanggal Kembali</th>
                            <th>deskripsi</th>
                            <th width="150px">Action</th>
                        </tr>
                </table>
            </div>
        </div>

        <!-- Bootstrap peminjaman model -->

    </div>
    </div>
    @endsection