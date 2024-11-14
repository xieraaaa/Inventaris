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
							<!--  -->
						</tbody>
					</table>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<button type="button" class="btn btn-danger btn-block" onClick="handleEmptyCart()"
						disabled id="cancelButton">
						Cancel
					</button>
				</div>
				<div class="col">
					<button type="button" class="btn btn-primary btn-block" disabled id="checkoutButton"
						onClick="handleClickSubmit()">
						Check Out
					</button>
				</div>
			</div>
		</div>
		<div style="flex: 3;">
			<div class="mb-2">
				<input type="text" class="form-control" placeholder="Cari produk" />
			</div>
			<div
				id="list-product"
				class="order-product d-flex flex-wrap justify-content-between"
				style="row-gap: 8px; column-gap: 8px"
			></div>
			<nav class="mt-3">
				@php
					$paginationLength = 5;
				@endphp
				<ul id="pagination" class="pagination pagination-lg justify-content-center" data-length="{{ $paginationLength }}">
					<li class="page-item"><a class="page-link" href="#">&laquo;</a></li>

					<li onclick="changePage(1)" data-page="1" data-role="pagination-number" class="page-item active"><a class="page-link" href="#">1</a></li>
					@for ($idx = 2; $idx <= $paginationLength; ++$idx)
						<li onclick="changePage({{ $idx }})" data-page="{{ $idx }}" data-role="pagination-number" class="page-item"><a class="page-link" href="#">{{ $idx }}</a></li>
					@endfor

					<li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
				</ul>
			</nav>
		</div>
	</div>

	<template id="product-item">
		<div style="height: 100px; width: 200px;"
			class="d-flex flex-column justify-content-center align-items-center bg-info hover-product-effect cursor-pointer px-5 rounded"
			onClick="addToCart(this)">
			<span class="text-center font-bold clamp-lines" data-role="name"></span>
			<span>Jumlah: <span class="text-center" data-role="jumlah"></span></span>
		</div>
	</template>

	<x-modals.peminjaman />
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
	<!-- Date Picker Plugin -->
	<script defer src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

	<!-- Date Range Plugin -->
	<script defer src="{{ asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js') }}"></script>
	<script defer src="{{ asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

	<script defer>
		let cart = []; // { id, name, jumlah }

		const productTemplate = document.getElementById('product-item').content.firstElementChild;

		const paginationButtons = document.querySelectorAll('[data-role="pagination-number"]');
		let page = paginationButtons[0].dataset.page;

		function enablePaginationButton(idx) {
			
		}

		function changePage(pageNumber) {
			$.ajax({
				dataType   : 'json',
				url        : '{{ url('get-barang') }}',
				data       : { 'page': pageNumber },
				contentType: 'application/json',

				success: function(data) {
					$('#list-product').html('');
					
					for (const barang of data) {
						const html = productTemplate.cloneNode(true);

						html.dataset.id = barang.kode_barang;

						html.querySelector('[data-role="name"]')  .innerText = barang.nama_barang;
						html.querySelector('[data-role="name"]')  .title     = barang.nama_barang;
						html.querySelector('[data-role="jumlah"]').innerText = barang.jumlah;

						$('#list-product').append(html);
					}
				}
			});
		}

		changePage(1);

		// Add a product to the cart
		function addToCart(element) {
			const id     = element.dataset.id;
			const name   = element.querySelector('[data-role="name"]').innerText;
			const jumlah = parseInt(element.querySelector('[data-role="jumlah"]').innerText);

			const existingProduct = cart.find(item => item.id === id);

			console.log(cart);

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

		// Mengirim data ke server untuk mengajukan peminjaman
		function handleClickSubmit() {
			$('#barang-modal').modal('show');
		}
	</script>
@endpush
