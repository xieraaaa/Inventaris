<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class DashboardController {
	private function getUserDashboard()
	{
		$pages = ceil(count(Barang::all()) / 20);
		
		return view('content.dashboard.user', ['pages' => $pages]);
	}

	private function getAdminDashboard()
	{
		return view('content.dashboard.admin');
	}

	private function getSuperadminDashboard()
	{
		return view('content.dashboard.superadmin');
	}
	
	public function index()
	{
		$user = Auth::user();

		if ($user->hasRole('user')) {
			return $this->getUserDashboard();
		}
		elseif ($user->hasRole('admin')) {
			return $this->getAdminDashboard();
		}
		elseif ($user->hasRole('superadmin')) {
			return $this->getSuperadminDashboard();
		}
	}
}
