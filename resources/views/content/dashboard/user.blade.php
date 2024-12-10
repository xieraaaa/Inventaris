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
                            {{-- Cart items will be rendered here --}}
                            <tr>
                                <td class="text-center" colspan="2">Keranjang kosong!<br />Silahkan menaruh barang di keranjang melalui interface yang berada di sebelah kanan Anda.</td>
                            </tr>
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
                        Pinjam
                    </button>
                </div>
            </div>
        </div>
        <div style="flex: 3;">
            <div class="mb-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari produk" />
            </div>
            {{--
            <div class="col">
                <button type="button" class="btn btn-secondary btn-block" onClick="openQrModal()">
                    Scan barcode
                </button>
            </div>
            --}}
            <div id="list-product" class="order-product d-flex flex-wrap justify-content-between" style="row-gap: 8px; column-gap: 8px">
                <!-- Product items will be rendered here -->
            </div>
            <nav class="mt-3">
                    @php
                        $paginationLength =30;
                    @endphp
                <ul id="pagination" class="pagination pagination-lg justify-content-center" data-length="{{ $paginationLength }}">
                        <li data-role="pagination-direction" data-direction="left" class="page-item" onclick="changePage(currentPage - paginationLength)">
                            <a class="page-link" href="#">&laquo;</a>
                        </li>
                        @for ($idx = 1; $idx <= $paginationLength; ++$idx)
                            <li onclick="changePage({{ $idx }})" data-page="{{ $idx }}" data-role="pagination-number" class="page-item">
                                <a class="page-link" href="#">{{ $idx }}</a>
                            </li>
                        @endfor
                        <li data-role="pagination-direction" data-direction="right" class="page-item" onclick="changePage(currentPage + paginationLength)">
                            <a class="page-link" href="#">&raquo;</a>
                        </li>
                    </ul>
            </nav>
            <template id="product-item">
                <div
                    class="product-card d-flex flex-column justify-content-between align-items-center p-3 rounded shadow-sm"
                    onClick="addToCart(this)">
                    <img src="" alt="Product Image" data-role="image" class="product-image rounded mb-2" />
                    <div class="product-info text-center">
                        <h6 class="product-name font-weight-bold mb-1 clamp-lines" data-role="name"></h6>
                        <p class="product-quantity text-muted mb-0">Jumlah: <span data-role="jumlah"></span></p>
                    </div>
                </div>
            </template>
        </div>

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
        .pagination .page-item.active .page-link {
        background-color: #0291d3;  /* Highlight color */
        border-color: #0291d3;
    }

            .product-card {
            width: 180px;
            height: 220px;
            background-color: #f9f9f9;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: #e8f5ff;
        }

        .product-image {
            max-width: 100%;
            max-height: 100px;
            object-fit: cover;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .product-name {
            color: #333;
            font-size: 14px;
        }

        .product-quantity {
            font-size: 12px;
            color: #666;
        }

        .clamp-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }

    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script defer src="{{ asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script defer src="{{ asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    @vite('resources/js/dashboard/user/pagination.js')

    <script defer>
        let cart = []; // { id, name, jumlah }
        let allProducts = []; // To store all products
        let currentPage = 1;
        let totalPages = 20; // This should be dynamically calculated from the server-side data
        const paginationLength = 5; // Number of pages to show at a time
        const productTemplate = document.getElementById('product-item').content.firstElementChild;
        const paginationRoot  = document.getElementById('pagination');

        /**
         * Memunculkan popup menandakan bahwa user baru saja mencoba untuk meminjam
         * produk dengan jumlah melebihi stok yang ada
         */
        function loadStockAlert() {
            Swal.fire({
                icon: 'error',
                title: 'Ups...',
                text: 'Anda tidak bisa meminjam lebih dari stok yang ada!',
                confirmButtonText: 'OKE'
            });
        }

        function changePage(pageNumber) {
            if (pageNumber < 1 || pageNumber > totalPages)
                return;
            currentPage = pageNumber;
            updatePagination(); // Update the pagination display
            loadPageData(pageNumber); // Load the data for the selected page
        }

        function loadPageData(pageNumber) {
        $.ajax({
            dataType: 'json',
            url: '{{ url('get-barang') }}',
            data: {
                page: pageNumber, // Kirim nomor halaman ke backend
            },
            success: function(data) {
                allProducts = data; // Simpan data barang
                renderProducts(); // Render produk pada halaman
            }
        });
    }

    function renderProducts() {
        const listProductContainer = document.getElementById('list-product');
        listProductContainer.innerHTML = ''; // Bersihkan produk saat ini

        allProducts.forEach(product => {
            const html = productTemplate.cloneNode(true);
            html.dataset.id = product.id;

            // Tambahkan data produk
            html.querySelector('[data-role="name"]').innerText = product.nama_barang;
            html.querySelector('[data-role="jumlah"]').innerText = product.jumlah;

            // Tambahkan gambar produk
            const imageElement = html.querySelector('[data-role="image"]');
            imageElement.src = product.image_url || "{{ asset('assets/images/imac.png') }}"; 
            imageElement.alt = product.nama_barang;

            listProductContainer.appendChild(html);
        });
    }


    function updatePagination() {
    const paginationRoot = document.getElementById('pagination');
    const startPage = Math.max(currentPage - Math.floor(paginationLength / 2), 1);
    const endPage = Math.min(startPage + paginationLength - 1, totalPages);

    paginationRoot.innerHTML = ''; // Bersihkan pagination lama

    for (let page = startPage; page <= endPage; page++) {
        const isActive = page === currentPage;
        paginationRoot.innerHTML += `
            <li class="page-item ${isActive ? 'active' : ''}" onclick="changePage(${page})">
                <a class="page-link" href="#">${page}</a>
            </li>`;
    }
}


        // TODO Refaktor agar lebih mudah untuk dipahami dan dimodifikasi
        // Untuk menampilkan dialog konfirmasi
        function confirmCartSubmission() {
            let cartSummary = '<ul style="list-style-type: none; padding: 0;">'; // Start a list for better formatting
            cart.forEach(item => {
                cartSummary += `
                    <li><strong>Nama Barang:</strong> ${item.name} <br>
                    <strong>Jumlah:</strong> ${item.jumlah}</li><br>
                `;
            });
            cartSummary += '</ul>'; // End the list

            Swal.fire({
                title: 'Konfirmasi Pinjaman',
                html: `
                    ${cartSummary} <!-- Insert the formatted list of items -->
                    <p><strong>Apakah Anda yakin ingin meminjam barang-barang di atas?</strong></p>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: {
                    content: 'text-left', // Align text to the left for better readability
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#barang-modal').modal('show');
                }
            });
        }

        // Add a product to the cart
        function addToCart(element) {
            const id = element.dataset.id;
            const name = element.querySelector('[data-role="name"]').innerText;
            const jumlah = parseInt(element.querySelector('[data-role="jumlah"]').innerText);
            const stock = jumlah;

            const existingProduct = cart.find(item => item.id === id);

            if (existingProduct) {
                if (existingProduct.jumlah + 1 > stock) {
                    loadStockAlert();
                    return;
                }
                existingProduct.jumlah++;
            }
            else {
                cart.push({ id, name, jumlah: 1, stock });
            }

            renderCart();
        }

        // Render the cart items in the table
        function renderCart() {
            const cartTable = document.getElementById('cart');

            if (cart.length === 0) {
                cartTable.innerHTML =
                `<tr>
                    <td class="text-center" colspan="2">Keranjang kosong!<br />Silahkan menaruh barang di keranjang melalui interface yang berada di sebelah kanan Anda.</td>
                </tr>`;

                return;
            }
            
            cartTable.innerHTML = '';

            cart.forEach(item => {
                const row = `<tr>
                    <td>${item.name}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-secondary" onClick="decreaseQuantity('${item.id}')">-</button>
                        <input type="number" value="${item.jumlah}" min="1" max="${item.stock}" style="width: 60px; text-align: center;" onChange="updateQuantity('${item.id}', this.value)" />
                        <button type="button" class="btn btn-sm btn-secondary" onClick="increaseQuantity('${item.id}', ${item.stock})">+</button>
                    </td>
                </tr>`;
                cartTable.innerHTML += row;
            });

            document.getElementById('cancelButton').disabled = cart.length === 0;
            document.getElementById('checkoutButton').disabled = cart.length === 0;
        }

        function increaseQuantity(id, maxQuantity) {
            const item = cart.find(product => product.id === id);
            if (item.jumlah < maxQuantity) {
                item.jumlah++;
            } else {
                loadStockAlert();
            }
            renderCart();
        }

        function decreaseQuantity(id) {
            const item = cart.find(product => product.id === id);
            if (item.jumlah > 1) {
                item.jumlah--;
                renderCart();
            } else {
                // Show confirmation alert when quantity is 1
                Swal.fire({
                    title: 'Hapus Item',
                    html: `Apakah Anda yakin ingin menghapus<br /><b>${item.name}</b><br />dari keranjang?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Remove item from cart
                        cart = cart.filter(product => product.id !== id);
                        renderCart();
                        
                        Swal.fire(
                            'Terhapus!',
                            'Item telah dihapus dari keranjang.',
                            'success'
                        );
                    }
                });
            }
        }

        function updateQuantity(id, quantity) {
            const item = cart.find(product => product.id === id);
            const newQuantity = parseInt(quantity);

            if (newQuantity >= 1 && newQuantity <= item.stock) {
                item.jumlah = newQuantity;
            } else {
                loadStockAlert();
            }
            renderCart();
        }

        function handleEmptyCart() {
            Swal.fire({
                'title'            : 'Konfirmasi Pengosongan',
                'text'             : 'Yakin untuk mengosongkan keranjang?',
                'confirmButtonText': 'Ya',
                'cancelButtonText' : 'Batal',
                'icon'             : 'warning',
                'showCancelButton' : true
            })
            .then(result => {
                if (result.isConfirmed) {
                    emptyCart();
                }
            });
        }

        function emptyCart() {
            cart = [];
            renderCart();
        }

        function handleClickSubmit() {
            // Call the confirmCartSubmission function before proceeding to checkout
            confirmCartSubmission();
        }

       
        // Filter and render products based on search query
        function filterAndRenderProducts() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();

            if (searchQuery !== '') {
                $.ajax({
                    url : '/get-barang-filtered',
                    data: {
                        query: searchQuery
                    },

                    success: data => {
                        console.log(data);
                        console.log('Successfully fetched filtered data regarding products!');
                        
                        $('#list-product').empty();
                        Array.from(data).forEach(product => {
                            const html = productTemplate.cloneNode(true);
                            html.dataset.id = product.id;

                            // Tambahkan data produk
                            html.querySelector('[data-role="name"]').title       = product.nama_barang;
                            html.querySelector('[data-role="name"]').innerText   = product.nama_barang;
                            html.querySelector('[data-role="jumlah"]').innerText = product.jumlah;

                            // Tambahkan gambar produk
                            const imageElement = html.querySelector('[data-role="image"]');
                            imageElement.src = product.image_url || "{{ asset('assets/images/imac.png') }}"; 
                            imageElement.alt = product.nama_barang;

                            $(html).appendTo('#list-product');
                        });
                    }
                });
            }
            else {
                const filteredProducts = allProducts.filter(product =>
                    product.nama_barang.toLowerCase().includes(searchQuery)
                );

                const listProductContainer = document.getElementById('list-product');
                listProductContainer.innerHTML = ''; // Bersihkan produk saat ini

                filteredProducts.forEach(product => {
                    const html = productTemplate.cloneNode(true);
                    html.dataset.id = product.id;

                    // Tambahkan data produk
                    html.querySelector('[data-role="name"]').innerText   = product.nama_barang;
                    html.querySelector('[data-role="jumlah"]').innerText = product.jumlah;

                    // Tambahkan gambar produk
                    const imageElement = html.querySelector('[data-role="image"]');
                    imageElement.src = product.image_url || "{{ asset('assets/images/imac.png') }}"; 
                    imageElement.alt = product.nama_barang;

                    listProductContainer.appendChild(html);
                });
            }
        }

        // Event listener for search input
        document.getElementById('searchInput').addEventListener('change', filterAndRenderProducts);

        updatePagination();
        changePage(1); // Start on page 1
    </script>
@endpush
