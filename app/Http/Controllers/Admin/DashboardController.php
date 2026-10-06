<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'categories' => Category::count(),
            'products' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('metrics'));
    }
}
