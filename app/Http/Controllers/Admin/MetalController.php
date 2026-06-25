<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Metal;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetalController extends Controller
{
    public function index(): View
    {
        $metals = Metal::withCount('variants')->orderBy('name')->get();
        return view('admin.metals.index', compact('metals'));
    }

    public function create(): View
    {
        return view('admin.metals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:metals,name']]);
        Metal::create($data);
        return redirect()->route('admin.metals.index')->with('success', 'Metal created.');
    }

    public function edit(Metal $metal): View
    {
        return view('admin.metals.edit', compact('metal'));
    }

    public function update(Request $request, Metal $metal): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:metals,name,' . $metal->id]]);
        $metal->update($data);
        return redirect()->route('admin.metals.index')->with('success', 'Metal updated.');
    }

    public function destroy(Metal $metal): RedirectResponse
    {
        if (ProductVariant::where('metal_id', $metal->id)->orWhere('second_metal_id', $metal->id)->exists()) {
            return back()->with('error', 'Cannot delete — this metal is used by product variants.');
        }
        $metal->delete();
        return redirect()->route('admin.metals.index')->with('success', 'Metal deleted.');
    }
}
