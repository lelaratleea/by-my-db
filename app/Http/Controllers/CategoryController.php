<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use App\Models\Category;

class CategoryController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Category::orderBy('code');
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('categories.list', [
            'criteria' => $criteria,
            'categories' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $categoryCode): View
    {
        $category = $this->find($categoryCode);

        return view('categories.view', [
            'category' => $category,
        ]);
    }

    function viewProducts(string $categoryCode, ServerRequestInterface $request): View
    {
        $category = $this->find($categoryCode);
        $productController = \resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $filterOptions = $productController->getFilterOptions();
        $query = $category->products()->withCount('shops');
        $productController->filter($query, $criteria, $filterOptions);

        return view('categories.view-products', [
            'criteria' => $criteria,
            'category' => $category,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function showCreateForm(): View
    {
        return view('categories.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $category = Category::create($request->getParsedBody());

        return redirect()->route('categories.view', [
            'category' => $category->code,
        ]);
    }

    function showUpdateForm(string $categoryCode): View
    {
        $category = $this->find($categoryCode);

        return view('categories.update-form', [
            'category' => $category,
        ]);
    }

    function update(
        string $categoryCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        $category->fill($request->getParsedBody());
        $category->save();

        return redirect()->route('categories.view', [
            'category' => $category->code,
        ]);
    }

    function delete(string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        $category->delete();

        return redirect()->route('categories.list');
    }
}