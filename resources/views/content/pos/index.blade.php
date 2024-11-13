<?php
	use App\Models\Barang;
?>

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
			<div id="list-product" class="order-product d-flex flex-wrap justify-content-between" style="row-gap: 8px; column-gap: 8px">
				<template id="product-item">
					<div style="height: 100px; width: 200px;"
						class="d-flex flex-column justify-content-center align-items-center bg-info hover-product-effect cursor-pointer px-5 rounded"
						onClick="addToCart(this)">
						<span class="text-center font-bold clamp-lines" data-role="name"></span>
						<span>Jumlah: <span class="text-center" data-role="jumlah"></span></span>
					</div>
				</template>
			</div>
			<div style="height: 75px" class="w-100 d-flex align-items-center justify-content-between">
				<div>
					<i class="fa-solid fa-angles-left fa-xl"></i>
					<i class="fa-solid fa-chevron-left fa-xl"></i>
				</div>
				<div class="d-flex gap-2">
					<?php
						$barangLength = ceil(count(Barang::all()) / 20);

						for ($idx = 1; $idx <= $barangLength; ++$idx):
					?>
						<div
							class="cursor-pointer size-12 rounded flex items-center justify-center hover:opacity-75"
						>
							<span class="text-xl">{{ $idx }}</span>
						</div>
					<?php
						endfor;
					?>
				</div>
				<div>
					<i class="fa-solid fa-chevron-right fa-xl"></i>
					<i class="fa-solid fa-angles-right fa-xl"></i>
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
		let cart = [];

		// Ambil data barang untuk list produk
		$.ajax({
			dataType   : 'json',
			url        : '{{ url('get-barang') }}',
			data       : { page: 1 },
			contentType: 'application/json',

			success: function(data) {
				const template = document.getElementById('product-item').content.firstElementChild;
				for (const barang of data) {
					const html = template.cloneNode(true);

					html.dataset.id                                      = barang.kode_barang;
					html.querySelector('[data-role="name"]').innerText   = barang.nama_barang;
					html.querySelector('[data-role="name"]').title       = barang.nama_barang;
					html.querySelector('[data-role="jumlah"]').innerText = barang.jumlah;
					$('#list-product').append(html);
				}
			}
		});

		// Add a product to the cart
		function addToCart(element) {
			const id     = element.dataset.id;
			const name   = element.querySelector('[data-role="name"]').innerText;
			const jumlah = parseInt(element.querySelector('[data-role="jumlah"]').innerText);

			const existingProduct = cart.find(item => item.id === id);

			if (existingProduct) {
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
			
			if (false) {
				$.ajax({
					method     : 'POST',
					url        : '{{ url('tambah-peminjaman ') }}',
					contentType: 'application/json',
					data       : cart
				});
			}
		}
	</script>
@endpush
