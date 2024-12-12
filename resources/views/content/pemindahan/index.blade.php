@extends('layouts.admin')

@push('styles')
    {{-- CSS untuk Select2 --}}
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endpush

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
                                        {{-- Input Nama Barang --}}
                                        <div class="col-sm-3">
                                            <label for="barang" class="form-label">Barang</label>
                                            <select class="select2 form-control form-select" style="width: 100%; height:36px;" id="barang-select" name="barang[]">
                                                <option value="-1">-- PILIH --</option>
                                                @foreach ($koleksiBarang as $barang)
                                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Input Unit Barang (berdasarkan kode inventaris) --}}
                                        <div class="col-sm-3">
                                            <label for="ki_unit_barang" class="form-label">Kode Inventaris</label>
                                            <select class="select2 form-control form-select" style="width: 100%; height:36px;" id="unit-barang-select" name="unit-barang[]">
                                                <option>-- PILIH --</option>
                                            </select>
                                        </div>

                                        {{-- Input Jumlah --}}
                                          
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
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>

    <script>
        (function () {
            // Untuk select 2
            $(".select2").select2();
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
         function populate(count) {
        const idBarang = $(`#barang${count} > option:selected`).val();

        // -1 berarti yang dipilih masih placeholder
        if (parseInt(idBarang) === -1) {
            return;
        }

        $.ajax(`/get-unit-barang/${idBarang}/kode_inventaris`, {
            success: function(data) {
                const $unitSelect = $(`#unit-barang-select${count}`);
                
                if (data.length === 0) {
                    $unitSelect.html('<option value="-1">-- kosong --</option>');
                    return;
                }
                
                $unitSelect.html('<option value="-1">-- PILIH --</option>');
                
                data.forEach(datum => {
                    $(`<option value="${datum}">${datum}</option>`).appendTo($unitSelect);
                });
            }
        });
    }

    var count = 0;

        function addDynamicFormFields() {
    count++;
    var html = `
        <div id="row${count}" class="row mt-2">
            <div class="col-sm-3">
                <label for="barang${count}" class="form-label">Barang</label>
                <select class="select2 form-select" id="barang${count}" name="barang[]">
                    <option value="">-- Select Barang --</option>
                    @foreach ($koleksiBarang as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="unit-barang${count}" class="form-label">Kode Inventaris</label>
                <select class="select2 form-select" id="unit-barang-select${count}" name="unit-barang[]">
                    <option value="-1">-- PILIH --</option>
                </select>
            </div>
            <div class="col-sm-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger" onclick="removeDynamicFormFields(${count});">Remove</button>
            </div>
        </div>
    `;
    $('#dynamic_form_fields').append(html);

        // Reinitialize select2 for the new select elements
        $(".select2").select2();

        // Attach change event for barang select in dynamic fields
        $(`#barang${count}`).on('change', function () {
            populate(count);
        });
    }

        function removeDynamicFormFields(row) {
            $('#row' + row).remove();
        }

        function isFormComplete() {
            return (
                   $('#tanggal').val()   !== ''
                && $('#asal').val()      !== ''
                && $('#tujuan') .val()   !== ''
                && $('#deskripsi').val() !== ''
            );
        }

        // Handle form submission with AJAX
        $('#submit-btn').on('click', function () {
            if (!isFormComplete()) {
                Swal.fire({
                    icon : 'error',
                    title: 'Gagal mengirim!',
                    text : 'Pastikan data sudah lengkap!'
                });

                return;
            }
            
            var formData = $('#form-pemindahan').serialize(); // Serialize form data

            $.ajax({
                url: '{{ url("store-pemindahan") }}', // Target URL
                type: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        'title': 'Sukses!',
                        'icon' : 'success'
                    });

                    for (let idx = 1; idx <= count; ++idx) {
                        removeDynamicFormFields(idx);
                    }
                    
                    $('#tanggal').val('');
                    $('#asal').val('');
                    $('#tujuan').val('');
                    $('#deskripsi').val('');
                    $('#jumlah').val('');
                    
                    // Reset form select2 ke opsi placeholder
                    $(".select2").val(-1).trigger('change');
                    count = 0; // Reset counter for dynamic fields
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        alert(JSON.stringify(xhr.responseJSON.errors));
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        

    </script>

    {{-- Pengisian #unit-barang-select --}}
    <script>
        (() => {
            const getUnits = (idBarang) => {
                return new Promise(resolve => {
                    $.ajax(`/get-unit-barang/${idBarang}/kode_inventaris`, {
                        success: (data) => {
                            resolve(data);
                        }
                    });
                });
            };

            const populate = () => {
                const idBarang = $('#barang-select > option:selected').val();

                // -1 berarti yang dipilih masih placeholder (<option value="-1">-- PILIH --</option>)
                if (parseInt(idBarang) === -1) {
                    return;
                }

                getUnits(idBarang).then(data => {
                    if (data.length === 0) {
                        $('#unit-barang-select').html('<option value="-1">-- KOSONG --</option>');
                        return;
                    }
                    $('#unit-barang-select').html('<option value="-1">-- PILIH --</option>');
                    
                    data.forEach(datum => {
                        $(`<option value="${datum}">${datum}</option>`).appendTo($('#unit-barang-select'));
                    });
                });
            };
            
            $('#barang-select').on('change', populate);
        })();
    </script>
@endpush
