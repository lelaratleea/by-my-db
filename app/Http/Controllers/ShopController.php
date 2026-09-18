<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class ShopController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Shop::withCount('products')->orderBy('code');
    }

    #[\Override()]
    function getFilterOptions(): array
    {
        return [
            'term' => [
                ...parent::getFilterOptions()['term'],
                'owner' => static fn(Builder $query, string $word)
                    => $query->where('owner', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria, null);

        return view('shops.list', [
            'criteria' => $criteria,
            'shops' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $shopCode): View
    {
        $shop = $this->find($shopCode);

        return view('shops.view', [
            'shop' => $shop,
        ]);
    }

    function showCreateForm(): View
    {
        return view('shops.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $shop = Shop::create($request->getParsedBody());

        return redirect()->route('shops.view', [
            'shop' => $shop->code,
        ]);
    }

    function showUpdateForm(string $shopCode): View
    {
        $shop = $this->find($shopCode);

        return view('shops.update-form', [
            'shop' => $shop,
        ]);
    }

    function update(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        $shop->fill($request->getParsedBody());
        $shop->save();

        return redirect()->route('shops.view', [
            'shop' => $shop->code,
        ]);
    }

    function delete(string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        $shop->delete();

        return redirect()->route('shops.list');
    }

    function viewProducts(string $shopCode, ServerRequestInterface $request): View
    {
        $shop = $this->find($shopCode);
        $productController = \resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $filterOptions = $productController->getFilterOptions();
        $query = $shop->products()
            ->with('category')       // eager load category กัน N+1
            ->withCount('shops');    // นับจำนวน shop ที่ขาย product นี้
        $productController->filter($query, $criteria, $filterOptions);

        return view('shops.view-products', [
            'criteria' => $criteria,
            'shop' => $shop,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function filterOutProductByShop(
        Builder|Relation $productQuery,
        Shop $shop,
    ): void {
        $productQuery->whereDoesntHave(
            'shops',
            static function (Builder $shopQuery) use ($shop): void {
                $shopQuery->where('code', $shop->code);
            },
        );
    }

    function showAddProductsForm(
        string $shopCode,
        ServerRequestInterface $request,
    ): View {
        $shop = $this->find($shopCode);
        $productController = \resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $filterOptions = $productController->getFilterOptions();

        $productQuery = $productController->getQuery()
            ->with('category')
            ->withCount('shops');
        $this->filterOutProductByShop($productQuery, $shop);
        $productController->filter($productQuery, $criteria, $filterOptions);

        return view('shops.add-products-form', [
            'criteria' => $criteria,
            'shop' => $shop,
            'products' => $productQuery->paginate($productController::MAX_ITEMS),
        ]);
    }

    function addProduct(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        $productController = \resolve(ProductController::class);
        $data = $request->getParsedBody();

        $productQuery = $productController->getQuery();
        $this->filterOutProductByShop($productQuery, $shop);

        $product = $productQuery->where('code', $data['product'])->firstOrFail();

        $shop->products()->attach($product);

        return redirect()->back();
    }

    function removeProduct(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        $data = $request->getParsedBody();

        $product = $shop->products()->where('code', $data['product'])->firstOrFail();

        $shop->products()->detach($product);

        return redirect()->back();
    }
}