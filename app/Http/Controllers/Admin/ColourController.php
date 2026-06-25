<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ColourController extends Controller
{
    public function index(): View
    {
        $colours = Colour::orderBy('name')->get();
        return view('admin.colours.index', compact('colours'));
    }

    public function create(): View
    {
        return view('admin.colours.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100', 'unique:colours,name'],
            'hex_code' => ['nullable', 'string', 'max:7'],
        ]);
        Colour::create($data);
        return redirect()->route('admin.colours.index')->with('success', 'Colour created.');
    }

    public function edit(Colour $colour): View
    {
        return view('admin.colours.edit', compact('colour'));
    }

    public function update(Request $request, Colour $colour): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100', 'unique:colours,name,' . $colour->id],
            'hex_code' => ['nullable', 'string', 'max:7'],
        ]);
        $colour->update($data);
        return redirect()->route('admin.colours.index')->with('success', 'Colour updated.');
    }

    public function destroy(Colour $colour): RedirectResponse
    {
        $colour->delete();
        return redirect()->route('admin.colours.index')->with('success', 'Colour deleted.');
    }
}
