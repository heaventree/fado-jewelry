<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::orderBy('sort_order')->get();
        $faqBannerImage = Setting::get('faq_banner_image', '');

        return view('admin.faqs.index', compact('faqs', 'faqBannerImage'));
    }

    public function create(): View
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'question'   => ['required', 'string', 'max:300'],
            'answer'     => ['required', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'active'     => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $request->validate([
            'question'   => ['required', 'string', 'max:300'],
            'answer'     => ['required', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'active'     => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        if ($request->hasFile('faq_banner_image_file')) {
            $path = $request->file('faq_banner_image_file')->store('pages', 'public');
            Setting::set('faq_banner_image', $path);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ page settings saved.');
    }
}
