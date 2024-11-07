<?php

namespace App\View\Components;

use Illuminate\View\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class sidebar extends Component
{
    public function __construct()
    {}

    public function render()
    {
        return view('partials.sidebar', ['admin' => Auth::user()->hasRole('admin')]);
    }
}
