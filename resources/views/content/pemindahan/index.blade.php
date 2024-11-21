@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Pemindahan Barang</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Pemindahan Barang</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card p-5 rounded">
            <form action="javascript:void(0)" id="form-pemindahan" name="form-pemindahan" class="form-horizontal"
                        method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal Pemindahan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal">
                </div>
                <div class="col-md-6">
                    <label for="asal" class="form-label">Asal Pemindahan</label>
                    <input type="text" class="form-control" id="asal" name="asal">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tujuan" class="form-label">Tujuan Pemindahan</label>
                    <input type="text" class="form-control" id="tujuan" name="tujuan">
                </div>
                <div class="col-md-6">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Dynamic Form Fields</h4>
                            <div id="dynamic_form_fields">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="barang" class="form-label">Barang</label>
                                        <select class="form-select" name="barang[]" id="barang">
                                            <option value="">-- Select Barang --</option>
                                            @foreach ($koleksiBarang as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah[]">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-3" onclick="addDynamicFormFields()">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex mt-3 justify-content-end">
                <button type="reset" class="btn btn-secondary">Batal</button>
                <button type="button" class="btn btn-primary" id="submit-btn">Selesai</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var count = 0;

    function addDynamicFormFields() {
        count++;
        var html = `
            <div id="row${count}" class="row mt-2">
                <div class="col-sm-6">
                    <label for="barang${count}" class="form-label">Barang</label>
                    <select class="form-select" id="barang${count}" name="barang[]">
                        <option value="">-- Select Barang --</option>
                        @foreach ($koleksiBarang as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <label for="jumlah${count}" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah${count}" name="jumlah[]" placeholder="Jumlah">
                </div>
                <div class="col-sm-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger" onclick="removeDynamicFormFields(${count});">Remove</button>
                </div>
            </div>
        `;
        $('#dynamic_form_fields').append(html);
    }

    function removeDynamicFormFields(row) {
        $('#row' + row).remove();
    }

    // Handle form submission with AJAX
    $('#submit-btn').on('click', function () {
        var formData = $('#form-pemindahan').serialize(); // Serialize form data

        $.ajax({
            url: '{{ url("store-pemindahan") }}', // Target URL
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response.message); // Show success message
                location.reload(); // Reload page
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    alert(JSON.stringify(xhr.responseJSON.errors));
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });
</script>
@endpush
