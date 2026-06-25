<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'location'     => ['nullable', 'string', 'max:100'],
            'body'         => ['required', 'string', 'max:1000'],
            'rating'       => ['required', 'integer', 'min:1', 'max:5'],
            'product_name' => ['nullable', 'string', 'max:200'],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
            'active'       => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');

        if ($request->hasFile('avatar_file')) {
            $data['avatar'] = $request->file('avatar_file')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'location'     => ['nullable', 'string', 'max:100'],
            'body'         => ['required', 'string', 'max:1000'],
            'rating'       => ['required', 'integer', 'min:1', 'max:5'],
            'product_name' => ['nullable', 'string', 'max:200'],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
            'active'       => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');

        if ($request->hasFile('avatar_file')) {
            $data['avatar'] = $request->file('avatar_file')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
