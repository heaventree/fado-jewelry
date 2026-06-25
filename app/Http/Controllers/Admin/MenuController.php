<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::withCount('items')->orderBy('name')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('admin.menus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'in:header,footer_col_1,footer_col_2'],
        ]);

        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created.');
    }

    public function edit(Menu $menu): View
    {
        $menu->load(['items' => function ($q) {
            $q->whereNull('parent_id')->orderBy('sort_order')
              ->with(['children' => fn ($c) => $c->orderBy('sort_order')]);
        }]);

        $categories  = \App\Models\Category::orderBy('name')->get();
        $collections = \App\Models\Collection::orderBy('name')->get();

        return view('admin.menus.edit', compact('menu', 'categories', 'collections'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'in:header,footer_col_1,footer_col_2'],
        ]);

        $menu->update($data);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu updated.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted.');
    }
}
