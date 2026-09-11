<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;

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
            ...parent::prepareCriteria($criteria),
            'minPrice' => (($criteria['minPrice'] ?? null) === null)
                ? null
                : (float) $criteria['minPrice'],
            'maxPrice' => (($criteria['maxPrice'] ?? null) === null)
                ? null
                : (float) $criteria['maxPrice'],
        ];
    }

    function filterByMinPrice(Builder|Relation $query, float $minPrice): void
    {
        $query->where('price', '>=', $minPrice);
    }

    function filterByMaxPrice(Builder|Relation $query, float $maxPrice): void
    {
        $query->where('price', '<=', $maxPrice);
    }

    #[\Override()]
    function filter(Builder|Relation $query, array $criteria, array $filterOptions): void
    {
        parent::filter($query, $criteria, $filterOptions);
        if ($criteria['minPrice'] !== null) {
            $this->filterByMinPrice($query, $criteria['minPrice']);
        }
        if ($criteria['maxPrice'] !== null) {
            $this->filterByMaxPrice($query, $criteria['maxPrice']);
        }
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('shops');
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

    function viewShops(string $productCode, ServerRequestInterface $request): View
    {
        $product = $this->find($productCode);
        $shopController = \resolve(ShopController::class);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());
        $filterOptions = $shopController->getFilterOptions();
        $query = $product->shops()->withCount('products');
        $shopController->filter($query, $criteria, $filterOptions);

        return view('products.view-shops', [
            'criteria' => $criteria,
            'product' => $product,
            'shops' => $query->paginate($shopController::MAX_ITEMS),
        ]);
    }

    function showCreateForm(): View
    {
        return view('products.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $product = Product::create($request->getParsedBody());
        return redirect()->route('products.view', [
            'product' => $product->code,
        ]);
    }

    function showUpdateForm(string $productCode): View
    {
        $product = $this->find($productCode);
        return view('products.update-form', [
            'product' => $product,
        ]);
    }

    function update(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $product->fill($request->getParsedBody());
        $product->save();
        return redirect()->route('products.view', [
            'product' => $product->code,
        ]);
    }

    function delete(string $productCode): RedirectResponse
{
    $product = $this->find($productCode);
    $product->delete();
    return redirect()->route('products.list');   // เปลี่ยนจาก products.index เป็น products.list
}
}