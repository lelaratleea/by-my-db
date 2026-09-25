<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class ProductController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Product::orderBy('code');
    }

    #[\Override()]
    function prepareCriteria(array $criteria): array
    {
        return [
            'minPrice' => null,
            'maxPrice' => null,
            ...parent::prepareCriteria($criteria),
        ];
    }

    #[\Override()]
    function getFilterOptions(): array
    {
        return [
            'term' => [
                ...parent::getFilterOptions()['term'], // code, name
                'category' => static fn(Builder $query, string $word)
                    => $query->whereHas(
                        'category',
                        static fn(Builder $categoryQuery)
                            => $categoryQuery->where('name', 'LIKE', "%{$word}%"),
                    ),
            ],
        ];
    }

    #[\Override()]
    function filter(
        Builder|Relation $query, array $criteria,
        array $filterOptions,
    ): void {
        parent::filter($query, $criteria, $filterOptions);

        if ($criteria['minPrice'] !== null && $criteria['minPrice'] !== '') {
            $query->where('price', '>=', $criteria['minPrice']);
        }

        if ($criteria['maxPrice'] !== null && $criteria['maxPrice'] !== '') {
            $query->where('price', '<=', $criteria['maxPrice']);
        }
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)
            ->with('category')
            ->withCount('shops');

        return view('products.list', [
            'criteria' => $criteria,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $productCode): View
    {
        $product = $this->find($productCode);

        return view('products.view', [
            'product' => $product,
        ]);
    }

    function showCreateForm(): View
    {
        $categories = \resolve(CategoryController::class)->getQuery()->get();

        return view('products.create-form', [
            'categories' => $categories,
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $data = $request->getParsedBody();
        $category = \resolve(CategoryController::class)->find($data['category']);

        $product = new Product();
        $product->fill(\Illuminate\Support\Arr::only($data, [
            'code', 'name', 'price', 'description',
        ]));
        $product->category()->associate($category);
        $product->save();

        if (session()->has('bookmarks.products.create')) {
            session()->put(
                'bookmarks.products.view',
                session()->get('bookmarks.products.create'),
            );
        }
        session()->forget('bookmarks.products.create');

        return redirect()
            ->route('products.view', [
                'product' => $product->code,
            ])
            ->with('status', "Product {$product->code} was created.");
    }

    function showUpdateForm(string $productCode): View
    {
        $product = $this->find($productCode);
        $categories = \resolve(CategoryController::class)->getQuery()->get();

        return view('products.update-form', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    function update(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $data = $request->getParsedBody();
        $category = \resolve(CategoryController::class)->find($data['category']);

        $product->fill(\Illuminate\Support\Arr::only($data, [
            'code', 'name', 'price', 'description',
        ]));
        $product->category()->associate($category);
        $product->save();

        if (session()->has('bookmarks.products.update')) {
            session()->put(
                'bookmarks.products.view',
                session()->get('bookmarks.products.update'),
            );
        }
        session()->forget('bookmarks.products.update');

        return redirect()
            ->route('products.view', [
                'product' => $product->code,
            ])
            ->with('status', "Product {$product->code} was updated.");
    }

    function delete(string $productCode): RedirectResponse
    {
        $product = $this->find($productCode);
        $product->delete();

        return redirect(
            session()->get('bookmarks.products.delete') ?? route('products.list')
        )->with('status', "Product {$product->code} was deleted.");
    }

    // ==========================================
    // Shop relationship — จาก Lecture 10
    // ==========================================

    function viewShops(
        string $productCode,
        ServerRequestInterface $request,
    ): View {
        $product = $this->find($productCode);
        $shopController = \resolve(ShopController::class);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());

        $shopQuery = $product->shops()->withCount('products');

        // หน้านี้มีลิงก์ไป add-shops-form ต้อง bookmark URL ของตัวเอง
        // ให้ปุ่ม Back ของ add-shops-form ใช้กลับมา
        session()->put('bookmarks.products.add-shops-form', url()->full());

        return view('products.view-shops', [
            'criteria' => $criteria,
            'product' => $product,
            'shops' => $shopQuery->paginate($shopController::MAX_ITEMS),
        ]);
    }

    function filterOutShopByProduct(
        Builder|Relation $shopQuery,
        Product $product,
    ): void {
        $shopQuery->whereDoesntHave(
            'products',
            static function (Builder $productQuery) use ($product): void {
                $productQuery->where('code', $product->code);
            },
        );
    }

    function showAddShopsForm(
        string $productCode,
        ServerRequestInterface $request,
    ): View {
        $product = $this->find($productCode);
        $shopController = \resolve(ShopController::class);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());

        $shopQuery = $shopController->getQuery()->withCount('products');
        $this->filterOutShopByProduct($shopQuery, $product);

        return view('products.add-shops-form', [
            'criteria' => $criteria,
            'product' => $product,
            'shops' => $shopQuery->paginate($shopController::MAX_ITEMS),
        ]);
    }

    function addShop(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $shopController = \resolve(ShopController::class);
        $data = $request->getParsedBody();

        $shopQuery = $shopController->getQuery();
        $this->filterOutShopByProduct($shopQuery, $product);

        $shop = $shopQuery
            ->where('code', $data['shop'])
            ->firstOrFail();

        $product->shops()->attach($shop);

        return redirect()
            ->back()
            ->with('status', "Shop {$shop->code} was added to Product {$product->code}.");
    }

    function removeShop(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $data = $request->getParsedBody();

        $shop = $product->shops()->where('code', $data['shop'])->firstOrFail();

        $product->shops()->detach($shop);

        return redirect()
            ->back()
            ->with('status', "Shop {$shop->code} was removed from Product {$product->code}.");
    }
}