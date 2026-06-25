<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gemstone;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GemstoneController extends Controller
{
    public function index(): View
    {
        $gemstones = Gemstone::withCount('variants')->orderBy('name')->get();
        return view('admin.gemstones.index', compact('gemstones'));
    }

    public function create(): View
    {
        return view('admin.gemstones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:gemstones,name']]);
        Gemstone::create($data);
        return redirect()->route('admin.gemstones.index')->with('success', 'Gemstone created.');
    }

    public function edit(Gemstone $gemstone): View
    {
        return view('admin.gemstones.edit', compact('gemstone'));
    }

    public function update(Request $request, Gemstone $gemstone): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:gemstones,name,' . $gemstone->id]]);
        $gemstone->update($data);
        return redirect()->route('admin.gemstones.index')->with('success', 'Gemstone updated.');
    }

    public function destroy(Gemstone $gemstone): RedirectResponse
    {
        if (ProductVariant::where('gemstone_id', $gemstone->id)->exists()) {
            return back()->with('error', 'Cannot delete — this gemstone is used by product variants.');
        }
        $gemstone->delete();
        return redirect()->route('admin.gemstones.index')->with('success', 'Gemstone deleted.');
    }
}
