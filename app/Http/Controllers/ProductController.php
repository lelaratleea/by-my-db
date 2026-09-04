<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
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

    function filterByMinPrice(Builder $query, float $minPrice): void
    {
        $query->where('price', '>=', $minPrice);
    }

    function filterByMaxPrice(Builder $query, float $maxPrice): void
    {
        $query->where('price', '<=', $maxPrice);
    }

#[\Override()]
function filter(Builder $query, array $criteria, array $filterOptions): void
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
    $query = $this->search($criteria);
    return view('products.list', [
        'criteria' => $criteria,
        'products' => $query->paginate(static::MAX_ITEMS), // ✅ ต้องเป็น paginate()
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
        return redirect()->route('products.index');
    }
}