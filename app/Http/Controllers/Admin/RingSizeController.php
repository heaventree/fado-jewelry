<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RingSizeController extends Controller
{
    public function index(): View
    {
        $sizes = ProductSize::select('us_size')
            ->distinct()
            ->orderBy('us_size')
            ->get()
            ->pluck('us_size');

        return view('admin.ring-sizes.index', compact('sizes'));
    }
}
