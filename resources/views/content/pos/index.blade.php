@extends ('layouts.admin')

@section('content')
    <div class="d-flex" style="padding: 32px 64px; column-gap: 24px">
        <div style="flex: 2">
            <div class="user-cart">
                <div class="card">
                    <table id="tabel-data" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="cart">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-danger btn-block" onClick={this.handleEmptyCart}
                        disabled={!cart.length}>
                        Cancel
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary btn-block" disabled={!cart.length}
                        onClick={this.handleClickSubmit}>
                        Check Out
                    </button>
                </div>
            </div>
        </div>
        <div style="flex: 3;">
            <div class="mb-2">
                <input type="text" class="form-control" placeholder="Cari produk" />
            </div>
            <div id="list-product" class="order-product d-flex flex-wrap justify-content-between" style="row-gap: 8px; column-gap: 8px">
                <template id="product-item">
                    <div style="height: 100px; width: 200px;"
                        class="d-flex flex-column justify-content-center align-items-center bg-info hover-product-effect cursor-pointer px-5 rounded">
                        <span class="text-center font-bold clamp-lines" data-role="name"></span>
                        <span>Jumlah: <span class="text-center" data-role="jumlah"></span></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .clamp-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* number of lines to show */
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hover-product-effect {
            cursor: pointer;
            transition: .25s background-color ease-out;
        }

        .hover-product-effect:hover {
            background-color: #0291d3 !important;
        }
    </style>
@endpush

@push('scripts')
    <script defer>
        // Ambil data barang untuk dimasukkan ke dalam tabel #tabel-data
        $.ajax({
            dataType: 'json',
            url: '{{ url('get-barang') }}',

            success: function(data) {
                const template = document.getElementById('product-item').content;

                for (const barang of data) {
                    const html = template.cloneNode(true);

                    // html.querySelector('[data-role=\"product_image\"]').src = 
                    html.querySelector('[data-role=\"name\"]').innerText   = barang.nama_barang;
                    html.querySelector('[data-role=\"name\"]').title       = barang.nama_barang;
                    html.querySelector('[data-role=\"jumlah\"]').innerText = barang.jumlah;

                    $('#list-product').append(html);
                }
            }
        });
    </script>
@endpush
