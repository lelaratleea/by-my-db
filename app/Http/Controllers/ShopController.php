<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use App\Models\Shop;

class ShopController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Shop::orderBy('code');
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

        return redirect()->route('shops.index');
    }
}