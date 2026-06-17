<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCollectionRequest;
use App\Http\Requests\Admin\UpdateCollectionRequest;
use App\Models\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Collection::withCount('products')->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $collections = $query->paginate(20)->withQueryString();

        return view('general.collections.list', compact('collections'));
    }

    public function create(): View
    {
        return view('general.collections.create');
    }

    public function store(StoreCollectionRequest $request): RedirectResponse
    {
        $slug = $this->uniqueSlug(
            $request->filled('slug') ? $request->input('slug') : Str::slug($request->input('name'))
        );

        $bannerPath = $request->hasFile('banner_image')
            ? $request->file('banner_image')->store('collections', 'public')
            : null;

        Collection::create([
            'name'               => $request->input('name'),
            'slug'               => $slug,
            'banner_image'       => $bannerPath,
            'banner_title'       => $request->input('banner_title'),
            'banner_description' => $request->input('banner_description'),
        ]);

        return redirect()->route('admin.collections.index')
            ->with('success', "Collection \"{$request->input('name')}\" created.");
    }

    public function edit(Collection $collection): View
    {
        $collection->loadCount('products');

        return view('general.collections.edit', compact('collection'));
    }

    public function update(UpdateCollectionRequest $request, Collection $collection): RedirectResponse
    {
        $inputSlug = $request->filled('slug') ? $request->input('slug') : Str::slug($request->input('name'));
        $slug = ($inputSlug === $collection->slug)
            ? $collection->slug
            : $this->uniqueSlug($inputSlug, $collection->id);

        if ($request->boolean('remove_banner') && $collection->banner_image) {
            Storage::disk('public')->delete($collection->banner_image);
            $bannerPath = null;
        } elseif ($request->hasFile('banner_image')) {
            if ($collection->banner_image) {
                Storage::disk('public')->delete($collection->banner_image);
            }
            $bannerPath = $request->file('banner_image')->store('collections', 'public');
        } else {
            $bannerPath = $collection->banner_image;
        }

        $collection->update([
            'name'               => $request->input('name'),
            'slug'               => $slug,
            'banner_image'       => $bannerPath,
            'banner_title'       => $request->input('banner_title'),
            'banner_description' => $request->input('banner_description'),
        ]);

        return redirect()->route('admin.collections.edit', $collection)
            ->with('success', "Collection \"{$collection->name}\" updated.");
    }

    public function destroy(Collection $collection): RedirectResponse
    {
        if ($collection->banner_image) {
            Storage::disk('public')->delete($collection->banner_image);
        }

        // Detach all products before deleting
        $collection->products()->detach();
        $collection->delete();

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection deleted.');
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug     = $base ?: 'collection';
        $original = $slug;
        $counter  = 1;

        while (
            Collection::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
