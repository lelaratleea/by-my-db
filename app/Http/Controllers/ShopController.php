<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('list', Shop::class);

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
        Gate::authorize('view', $shop);

        // Route shops.view bookmarks its own URL for update-form and
        // view-products back links.
        session()->put('bookmarks.shops.update-form', url()->full());
        session()->put('bookmarks.shops.view-products', url()->full());

        // Forward: shops.update and shops.delete use the same back link
        // location as shops.view itself.
        session()->put(
            'bookmarks.shops.update',
            session()->get('bookmarks.shops.view'),
        );
        session()->put(
            'bookmarks.shops.delete',
            session()->get('bookmarks.shops.view'),
        );

        return view('shops.view', [
            'shop' => $shop,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Shop::class);

        return view('shops.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Shop::class);

        $shop = Shop::create($request->getParsedBody());

        if (session()->has('bookmarks.shops.create')) {
            session()->put(
                'bookmarks.shops.view',
                session()->get('bookmarks.shops.create'),
            );
        }
        session()->forget('bookmarks.shops.create');

        return redirect()
            ->route('shops.view', [
                'shop' => $shop->code,
            ])
            ->with('status', "Shop {$shop->code} was created.");
    }

    function showUpdateForm(string $shopCode): View
    {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        return view('shops.update-form', [
            'shop' => $shop,
        ]);
    }

    function update(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        $shop->fill($request->getParsedBody());
        $shop->save();

        if (session()->has('bookmarks.shops.update')) {
            session()->put(
                'bookmarks.shops.view',
                session()->get('bookmarks.shops.update'),
            );
        }
        session()->forget('bookmarks.shops.update');

        return redirect()
            ->route('shops.view', [
                'shop' => $shop->code,
            ])
            ->with('status', "Shop {$shop->code} was updated.");
    }

    function delete(string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        Gate::authorize('delete', $shop);

        $code = $shop->code;
        $shop->delete();

        return redirect(
            session()->get('bookmarks.shops.delete')
                ?? route('shops.list')
        )->with('status', "Shop {$code} was deleted.");
    }

    function viewProducts(string $shopCode, ServerRequestInterface $request): View
    {
        $shop = $this->find($shopCode);
        Gate::authorize('view', $shop);

        $productController = \resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $filterOptions = $productController->getFilterOptions();
        $query = $shop->products()
            ->with('category')       // eager load category กัน N+1
            ->withCount('shops');    // นับจำนวน shop ที่ขาย product นี้
        $productController->filter($query, $criteria, $filterOptions);

        // Route shops.view-products bookmarks its own URL for
        // shops.add-products-form back link.
        session()->put('bookmarks.shops.add-products-form', url()->full());

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
        Gate::authorize('update', $shop);

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
        Gate::authorize('update', $shop);

        $productController = \resolve(ProductController::class);
        $data = $request->getParsedBody();

        $productQuery = $productController->getQuery();
        $this->filterOutProductByShop($productQuery, $shop);

        $product = $productQuery->where('code', $data['product'])->firstOrFail();

        $shop->products()->attach($product);

        return redirect()
            ->back()
            ->with('status', "Product {$product->code} was added to Shop {$shop->code}.");
    }

    function removeProduct(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        $data = $request->getParsedBody();

        $product = $shop->products()->where('code', $data['product'])->firstOrFail();

        $shop->products()->detach($product);

        return redirect()
            ->back()
            ->with('status', "Product {$product->code} was removed from Shop {$shop->code}.");
    }
}