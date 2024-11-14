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
                            <!-- Cart items will be rendered here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-danger btn-block" onClick="handleEmptyCart()" disabled id="cancelButton">
                        Cancel
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary btn-block" disabled id="checkoutButton" onClick="handleClickSubmit()">
                        Check Out
                    </button>
                </div>
            </div>
        </div>
        <div style="flex: 3;">
            <div class="mb-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari produk" />
            </div>
            <div class="col">
                <button type="button" class="btn btn-secondary btn-block" onClick="openQrModal()">
                    Scan barcode
                </button>
            </div>
            <div id="list-product" class="order-product d-flex flex-wrap justify-content-between" style="row-gap: 8px; column-gap: 8px">
                <!-- Product items will be rendered here -->
            </div>
            <nav class="mt-3">
                @php
                    $paginationLength = 5;
                @endphp
                <ul id="pagination" class="pagination pagination-lg justify-content-center" data-length="{{ $paginationLength }}">
                    <li data-role="pagination-left" class="page-item"><a class="page-link" href="#">&laquo;</a></li>

                    <li onclick="changePage(1)" data-page="1" data-role="pagination-number" class="page-item active"><a class="page-link" href="#">1</a></li>
                    @for ($idx = 2; $idx <= $paginationLength; ++$idx)
                        <li onclick="changePage({{ $idx }})" data-page="{{ $idx }}" data-role="pagination-number" class="page-item"><a class="page-link" href="#">{{ $idx }}</a></li>
                    @endfor

                    <li data-role="pagination-right" class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <template id="product-item">
        <div style="height: 100px; width: 200px;" class="d-flex flex-column justify-content-center align-items-center bg-info hover-product-effect cursor-pointer px-5 rounded" onClick="addToCart(this)">
            <span class="text-center font-bold clamp-lines" data-role="name"></span>
            <span>Jumlah: <span class="text-center" data-role="jumlah"></span></span>
        </div>
    </template>

    <!-- Modal for QR Code Scanner -->
    <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div id="reader" style="width: 500px; height: 500px;"></div>
                <div id="scanned-result" style="margin-top: 250px;">
                    <p id="decoded-text"></p>
                </div>
            </div>
        </div>
    </div>
    <x-modals.peminjaman />
@endsection

@push('styles')
    <style>
        .clamp-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
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
<script src="https://unpkg.com/html5-qrcode"></script>
<script defer src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script defer src="{{ asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script defer src="{{ asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

@vite('resources/js/dashboard/user/pagination.js')

<script defer>
    let cart = []; // { id, name, jumlah }
    let allProducts = []; // To store all products
    const productTemplate = document.getElementById('product-item').content.firstElementChild;
    const paginationRoot  = document.getElementById('pagination');

    function changePage(pageNumber) {
        $.ajax({
            dataType   : 'json',
            url        : '{{ url('get-barang') }}',
            data       : { 'page': pageNumber },
            contentType: 'application/json',

            success: function(data) {
                allProducts = data;  // Save all the products fetched
                filterAndRenderProducts(); // Render the products based on search query
            }
        });
    }

    // Add a product to the cart
    function addToCart(element) {
        const id     = element.dataset.id;
        const name   = element.querySelector('[data-role="name"]').innerText;
        const jumlah = parseInt(element.querySelector('[data-role="jumlah"]').innerText);

        const existingProduct = cart.find(item => item.id === id);

        if (existingProduct) {
            if (existingProduct.jumlah + 1 > jumlah) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ups...',
                    text: 'Anda tidak bisa meminjam lebih dari stok yang ada!',
                    confirmButtonText: 'OKE'
                });
                return;
            }
            existingProduct.jumlah++;
        } else {
            cart.push({ id, name, jumlah: 1 });
        }

        renderCart();
    }

    // Render the cart items in the table
    function renderCart() {
        const cartTable = document.getElementById('cart');
        cartTable.innerHTML = '';

        cart.forEach(item => {
            const row = `<tr>
                <td>${item.name}</td>
                <td>${item.jumlah}</td>
            </tr>`;
            cartTable.innerHTML += row;
        });

        document.getElementById('cancelButton').disabled = cart.length === 0;
        document.getElementById('checkoutButton').disabled = cart.length === 0;
    }

    // Handle cart cancelation
    function handleEmptyCart() {
        cart = [];
        renderCart();
    }

    // Function for handling the checkout modal
    function handleClickSubmit() {
        $('#barang-modal').modal('show');
    }

    // Open QR modal for barcode scanning
    function openQrModal() {
        $('#qrModal').modal('show');
        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
        }
        html5QrcodeScanner.render(onScanSuccess);
    }

    let html5QrcodeScanner = null;

    // QR Scanner success callback with error handling
    function onScanSuccess(decodedText, decodedResult) {
        fetch(`http://127.0.0.1:8000/items/${decodedText}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                const itemName   = data.name;
                const itemId     = data.id;
                const itemJumlah = data.jumlah;

                document.getElementById('decoded-text').innerText = itemName;

                const existingProduct = cart.find(item => item.id === itemId);
                if (existingProduct) {
                    if (existingProduct.jumlah + 1 <= itemJumlah) {
                        existingProduct.jumlah++;
                    } else {
                        alert('Stock limit reached');
                    }
                } else {
                    cart.push({ id: itemId, name: itemName, jumlah: 1 });
                }

                renderCart();
            })
            .catch(error => {
                alert('Error fetching item details: ' + error.message);
            });
    }

    // Clear QR scanner when modal is closed
    $('#qrModal').on('hidden.bs.modal', function () {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }
    });

    // Filter and render products based on search query
    function filterAndRenderProducts() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const filteredProducts = allProducts.filter(product =>
            product.nama_barang.toLowerCase().includes(searchQuery)
        );

        const listProductContainer = document.getElementById('list-product');
        listProductContainer.innerHTML = ''; // Clear current products

        filteredProducts.forEach(product => {
            const html = productTemplate.cloneNode(true);
            html.dataset.id = product.kode_barang;

            html.querySelector('[data-role="name"]').innerText = product.nama_barang;
            html.querySelector('[data-role="name"]').title = product.nama_barang;
            html.querySelector('[data-role="jumlah"]').innerText = product.jumlah;

            listProductContainer.appendChild(html);
        });
    }

    // Event listener for search input
    document.getElementById('searchInput').addEventListener('keyup', filterAndRenderProducts);

    // Initialize first page
    changePage(1);
</script>
@endpush
