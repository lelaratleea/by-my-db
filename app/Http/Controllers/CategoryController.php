<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

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
        // categories.view-products ห้ามค้นหาด้วยชื่อ category (ตัดตัวเลือกนี้ออก)
        unset($filterOptions['term']['category']);

        $query = $category->products()->withCount('shops');
        $productController->filter($query, $criteria, $filterOptions);

        return view('categories.view-products', [
            'criteria' => $criteria,
            'category' => $category,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function filterOutProductByCategory(
        Builder|Relation $productQuery,
        Category $category,
    ): void {
        $productQuery->whereDoesntHave(
            'category',
            static function (Builder $categoryQuery) use ($category): void {
                $categoryQuery->where('code', $category->code);
            },
        );
    }

    function showAddProductsForm(
        string $categoryCode,
        ServerRequestInterface $request,
    ): View {
        $category = $this->find($categoryCode);
        $productController = \resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $filterOptions = $productController->getFilterOptions();

        $productQuery = $productController->getQuery()
            ->with('category')
            ->withCount('shops');
        $this->filterOutProductByCategory($productQuery, $category);
        $productController->filter($productQuery, $criteria, $filterOptions);

        return view('categories.add-products-form', [
            'criteria' => $criteria,
            'category' => $category,
            'products' => $productQuery->paginate($productController::MAX_ITEMS),
        ]);
    }

    function addProduct(
        string $categoryCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        $productController = \resolve(ProductController::class);
        $data = $request->getParsedBody();

        $productQuery = $productController->getQuery();
        $this->filterOutProductByCategory($productQuery, $category);

        $product = $productQuery->where('code', $data['product'])->firstOrFail();

        // "เพิ่มสินค้า" เข้าหมวดหมู่ = เปลี่ยน category ของสินค้านั้น
        $product->category()->associate($category);
        $product->save();

        return redirect()->back();
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