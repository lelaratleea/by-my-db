<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    function index(): View {
        $products = Product
            ::orderBy('code')
            ->get();

        return view('products.list', [
            'products' => $products,
        ]);
    }

    function view(string $productCode): View {
        $product = Product
            ::where('code', $productCode)
            ->firstOrFail();

        return view('products.view', [
            'product' => $product,
        ]);
    }
}