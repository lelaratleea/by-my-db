<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{
    function index(): View {
        $shops = Shop
            ::orderBy('code')
            ->get();

        return view('shops.list', [
            'shops' => $shops,
        ]);
    }

    function view(string $shopCode): View {
        $shop = Shop
            ::where('code', $shopCode)
            ->firstOrFail();

        return view('shops.view', [
            'shop' => $shop,
        ]);
    }
}