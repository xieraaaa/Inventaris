<?php

namespace App\View\Components\Modals;

use App\Models\Unit;
use App\Models\Merek;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\View\Component;

class Peminjaman extends Component
{
	/**
	 * Mengembalikan view untuk modal peminjaman
	 * 
	 * @return Illuminate\Contracts\View\View
	 */
	public function render()
	{
		$kategoris = Kategori::all();
        $units     = Unit::all();
        $mereks    = Merek::all();
        $barang    = Barang::first();

        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'Baik' : 'Rusak') : 'N/A';

        $koleksiBarang = Barang::all();

		return view('components.modals.peminjaman', compact('mereks'));
	}
}
