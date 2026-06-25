<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function store(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'label'        => ['required', 'string', 'max:200'],
            'url'          => ['nullable', 'string', 'max:500'],
            'type'         => ['required', 'string', 'in:custom,page,category,collection'],
            'reference_id' => ['nullable', 'integer'],
            'target'       => ['required', 'string', 'in:_self,_blank'],
            'parent_id'    => ['nullable', 'integer', 'exists:menu_items,id'],
        ]);

        $data['menu_id']    = $menu->id;
        $data['sort_order'] = $menu->items()->max('sort_order') + 1;

        MenuItem::create($data);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Item added.');
    }

    public function update(Request $request, Menu $menu, MenuItem $item): RedirectResponse
    {
        $data = $request->validate([
            'label'     => ['required', 'string', 'max:200'],
            'url'       => ['nullable', 'string', 'max:500'],
            'target'    => ['required', 'string', 'in:_self,_blank'],
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
        ]);

        $item->update($data);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Item updated.');
    }

    public function destroy(Menu $menu, MenuItem $item): RedirectResponse
    {
        $item->children()->delete();
        $item->delete();

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Item removed.');
    }

    public function reorder(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'items'              => ['required', 'array'],
            'items.*.id'         => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['items'] as $entry) {
            MenuItem::where('id', $entry['id'])->where('menu_id', $menu->id)
                ->update(['sort_order' => $entry['sort_order']]);
        }

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Order saved.');
    }
}
