<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\OptimizesImages;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use OptimizesImages;
    public function index(Request $request): View
    {
        $query = Category::with('parent')
            ->withCount('products')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->paginate(30)->withQueryString();

        return view('general.category.list', compact('categories'));
    }

    public function create(): View
    {
        $parents = Category::whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get();

        return view('general.category.create', compact('parents'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $slug = $this->uniqueSlug(
            $request->filled('slug') ? $request->input('slug') : Str::slug($request->input('name'))
        );

        $bannerPath = $request->hasFile('banner_image')
            ? $this->storeOptimizedImage($request->file('banner_image'), 'categories', 1600, 82)
            : null;

        $thumbnailPath = $request->hasFile('thumbnail_image')
            ? $this->storeOptimizedImage($request->file('thumbnail_image'), 'categories', 800, 82)
            : null;

        Category::create([
            'name'               => $request->input('name'),
            'slug'               => $slug,
            'parent_id'          => $request->input('parent_id') ?: null,
            'sort_order'         => (int) $request->input('sort_order', 0),
            'banner_image'       => $bannerPath,
            'thumbnail_image'    => $thumbnailPath,
            'banner_title'       => $request->input('banner_title'),
            'banner_description' => $request->input('banner_description'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$request->input('name')}\" created.");
    }

    public function edit(Category $category): View
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('general.category.edit', compact('category', 'parents'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $inputSlug = $request->filled('slug') ? $request->input('slug') : Str::slug($request->input('name'));
        $slug = ($inputSlug === $category->slug)
            ? $category->slug
            : $this->uniqueSlug($inputSlug, $category->id);

        if ($request->boolean('remove_banner') && $category->banner_image) {
            Storage::disk('public')->delete($category->banner_image);
            $bannerPath = null;
        } elseif ($request->hasFile('banner_image')) {
            if ($category->banner_image) {
                Storage::disk('public')->delete($category->banner_image);
            }
            $bannerPath = $this->storeOptimizedImage($request->file('banner_image'), 'categories', 1600, 82);
        } else {
            $bannerPath = $category->banner_image;
        }

        if ($request->boolean('remove_thumbnail') && $category->thumbnail_image) {
            Storage::disk('public')->delete($category->thumbnail_image);
            $thumbnailPath = null;
        } elseif ($request->hasFile('thumbnail_image')) {
            if ($category->thumbnail_image) {
                Storage::disk('public')->delete($category->thumbnail_image);
            }
            $thumbnailPath = $this->storeOptimizedImage($request->file('thumbnail_image'), 'categories', 800, 82);
        } else {
            $thumbnailPath = $category->thumbnail_image;
        }

        // Prevent a category from being its own parent
        $parentId = $request->input('parent_id') ?: null;
        if ($parentId == $category->id) {
            $parentId = null;
        }

        $category->update([
            'name'               => $request->input('name'),
            'slug'               => $slug,
            'parent_id'          => $parentId,
            'sort_order'         => (int) $request->input('sort_order', 0),
            'banner_image'       => $bannerPath,
            'thumbnail_image'    => $thumbnailPath,
            'banner_title'       => $request->input('banner_title'),
            'banner_description' => $request->input('banner_description'),
        ]);

        return redirect()->route('admin.categories.edit', $category)
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return back()->with('error', "Cannot delete \"{$category->name}\" — it has sub-categories. Delete or re-parent them first.");
        }

        if ($category->banner_image) {
            Storage::disk('public')->delete($category->banner_image);
        }
        if ($category->thumbnail_image) {
            Storage::disk('public')->delete($category->thumbnail_image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Category deleted.");
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug     = $base ?: 'category';
        $original = $slug;
        $counter  = 1;

        while (
            Category::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
