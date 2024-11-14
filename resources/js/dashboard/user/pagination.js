(() => {
	const root = document.getElementById('pagination');

	if (root === null) {
		throw 'Gagal untuk menemukan element utama dengan id "pagination"!';
	}

	const buttons           = root.querySelectorAll('[data-role="pagination-number"]');
	const buttonsLength     = buttons.length;
	let   centerIndex       = Math.floor(root.dataset.length / 2); // Indeks untuk tombol yang ditengah
	let   activeButtonIndex = 0;

	function update(index) {
		for (let buttonIndex = 0; buttonIndex < buttonsLength; ++buttonIndex) {
			buttons[buttonIndex].firstElementChild.innerText = (1 + buttonIndex + (index - centerIndex));

			activeButtonIndex = centerIndex;
		}
	}
	
	window.pagination.enableButton = function(index) {
		// Agar sesuai dengan zero-based indexing yang activeButtonIndex ikuti
		index -= 1;
		
		if (index > centerIndex) {
			buttons[activeButtonIndex].classList.remove('active');
			update(index);
			buttons[activeButtonIndex].classList.add('active');
		}
		else {
			buttons[activeButtonIndex].classList.remove('active');
			buttons[activeButtonIndex = index].classList.add('active');
		}
	};

	const paginationLeft  = root.querySelector('[data-role="pagination-left"]');
	const paginationRight = root.querySelector('[data-role="pagination-right"]');

	paginationLeft.addEventListener('click', () => {
		
	});
})();
