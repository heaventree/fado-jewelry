<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\OptimizesImages;
use Illuminate\View\View;

class SliderController extends Controller
{
    use OptimizesImages;
    public function index(): View
    {
        $sliders = Slider::orderBy('sort_order')->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'heading'     => ['required', 'string', 'max:200'],
            'subheading'  => ['nullable', 'string', 'max:200'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_url'  => ['nullable', 'string', 'max:500'],
            'image'       => ['required', 'image', 'max:5120'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'active'      => ['nullable', 'boolean'],
        ]);

        $data['image']  = $this->storeOptimizedImage($request->file('image'), 'sliders', 1920, 82);
        $data['active'] = $request->boolean('active');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide created.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $data = $request->validate([
            'heading'     => ['required', 'string', 'max:200'],
            'subheading'  => ['nullable', 'string', 'max:200'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_url'  => ['nullable', 'string', 'max:500'],
            'image'       => ['nullable', 'image', 'max:5120'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'active'      => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);
            $data['image'] = $this->storeOptimizedImage($request->file('image'), 'sliders', 1920, 82);
        } else {
            unset($data['image']);
        }

        $data['active'] = $request->boolean('active');
        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide updated.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slide deleted.');
    }
}
