@extends('layouts.admin')

@push('styles')
<link href="{{ asset('../assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

<!-- Custom CSS -->
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
@endpush
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
                    <li class="breadcrumb-item active">Barang</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="p-3 rounded">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <div>
            <div class="container">
                @for ($index = 0; $index < 50; ++$index)
                    <div class="row">
                    <?php
                    $increment = 4;
                    $max_index = $index + $increment;
                    for (; $index < $max_index; ++$index):
                        $barang = $koleksiBarang[$index];

                        $nama_barang = $barang['nama_barang'];
                        $merek       = $mereks[$barang['id_merek']]['merek'];
                        $keterangan  = $barang['keterangan'];
                    ?>

                        <div class="card col-sm m-3 px-0" style="width: 18rem;">
                            <img class="card-img-top img-fluid" src="/assets/images/img1.jpg" alt="Card image cap">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-auto">
                                    <h5 class="card-title"><?= $nama_barang ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?= $merek ?></h6>
                                    <p class="card-text"><?= $keterangan ?></p>
                                </div>
                                <a href="#" onClick="PinjamFunc({{ $barang['id'] }})" class="btn btn-primary mt-auto align-self-end">Pinjam</a>
                            </div>
                        </div>

                    <?php
                    endfor;
                    ?>
            </div>
            @endfor
        </div>
    </div>
</div>

<!-- Bootstrap barang model -->
<div class="modal fade" id="barang-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="barangModal"></h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="barangForm" name="barangForm" class="form-horizontal"
                    method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="id_user" value="<?=Auth::user()->id?>">
                    <input type="hidden" name='id_barang' id="id_barang">

                    
                    <div class="form-group">
                        <label for="nama_barang" class="col-sm-8 mb-2 control-label">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                placeholder="nama barang" maxlength="50" required="" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_merek" class="col-sm-8 mb-2 control-label">Merek</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="id_merek" name="id_merek" required disabled>
                                <option value="">-- Select merek --</option>
                                @foreach ($mereks as $merek)
                                <option value="{{ $merek->id }}">{{ $merek->merek }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="m-t-20 form-label">Dari</label>
                                    <input type="text" class="form-control" placeholder="2017-06-04" name = "mdate" id="mdate">
                                </div>
                                <div class="col-md-6">
                                    <label class="m-t-20 form-label">Sampai</label>
                                    <input type="text" class="form-control" placeholder="2017-06-04" name="pdate" id="pdate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jumlah" class="col-sm-8 mb-2 control-label">jumlah</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" value="1"
                                placeholder="jumlah stock" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kondisi" class="col-sm-8 mb-2 control-label">Kondisi</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="kondisi" name="kondisi" required disabled>
                                <option value="1">Baik</option>
                                <option value="0">Rusak</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan" class="col-sm-8 mb-2 control-label">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="keterangan" name="keterangan" placeholder="keterangan" rows="4" maxlength="500" required=""></textarea>
                        </div>
                    </div>


                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('getDatatables') }}",
                type: 'GET',
                dataSrc: function(json) {
                    console.log(json); // Inspect JSON structure to confirm data mapping
                    return json.data || []; // Ensure the data source path is correct
                }
            },
            responsive: true,
            autoWidth: true, // Allow automatic column width adjustments
            columns: [{
                    data: null,
                    name: 'id',
                    title: 'ID',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Urutan nomor berdasarkan halaman
                    }
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang',
                    title: 'Nama Barang',
                },
                {
                    data: 'merek',
                    name: 'merek',
                    title: 'Merek',
                    orderable: false
                },
                {
                    data: 'kondisi_label',
                    name: 'kondisi',
                    title: 'Kondisi',
                    orderable: false
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                    title: 'Jumlah',


                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                    title: 'Keterangan',
                    orderable: false
                },

                {
                    data: 'action',
                    name: 'action',
                    title: 'Action',
                    orderable: false
                }

            ],
            order: [
                [1, 'asc']
            ],
            scrollX: true
        });
    });


    function PinjamFunc(id) {
        $.ajax({
            type: "POST",
            url: "{{ url('edit-item') }}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#barangModal').html("Pinjam Barang");
                $('#barang-modal').modal('show');
                $('#id_barang').val(res.id);
                $('#nama_barang').val(res.nama_barang);
                $('#id_merek').val(res.id_merek);
                $('#jumlah').val('');
                $('#kondisi').val(res.kondisi);
            }
        });
    }


    $('#barangForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ url('store-item') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#barang-modal").modal('hide');
                $('#barang').DataTable().ajax.reload(null, false);
                Swal.fire("Success!", "barang has been saved successfully.", "success");
            },
            error: function(data) {
                Swal.fire("Error!", "Something went wrong.", "error");
            }
        });
    });

    function importData() {
        $('#import-modal').modal('show');
    }

    /* Skrip untuk modal detail */
    $(document).on('click', '#barang tbody tr', function(event) {
        console.log('Clicked');

        // Cek apakah elemen yang diklik adalah bagian dari kolom aksi (misalnya tombol)
        if ($(event.target).closest('td').hasClass('action')) {
            return; // Jangan lakukan apa-apa jika klik terjadi di dalam kolom aksi
        }

        var data = $('#barang').DataTable().row(this).data();

        if (data) {
            // Isi detail modal dengan data yang sesuai
            $('#barcode').html(data.barcode.replace('&gt;', '>').replace('&lt;', '<'));
            $('#detail_kode_barang').text(data.kode_barang);
            $('#detail_nama_barang').text(data.nama_barang);
            $('#detail_kategori').text(data.kategori);
            $('#detail_unit').text(data.unit);
            $('#detail_merek').text(data.merek);
            $('#detail_kondisi').text(data.kondisi === 1 ? 'Baik' : 'Rusak');
            $('#detail_jumlah').text(data.jumlah);
            $('#detail_keterangan').text(data.keterangan);

            // Tampilkan modal detail
            $('#detail-modal').modal('show');
        }
    });
</script>
<!-- Plugin JavaScript -->
<script src="{{ asset('../assets/node_modules/moment/moment.js') }}"></script>
<script src="{{ asset('../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('../assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

<script src="{{ asset('assets/node_modules/moment/moment.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/jquery-asColor/dist/jquery-asColor.js') }}"></script>
<script src="{{ asset('assets/node_modules/jquery-asGradient/dist/jquery-asGradient.js') }}"></script>
<script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- Date range Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
    // MAterial Date picker    
    $('#mdate').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false
    });
    $('#pdate').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false
    });
    $('#timepicker').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        time: true,
        date: false
    });
    $('#date-format').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY - HH:mm'
    });

    $('#min-date').bootstrapMaterialDatePicker({
        format: 'DD/MM/YYYY HH:mm',
        minDate: new Date()
    });
</script>
@endpush
