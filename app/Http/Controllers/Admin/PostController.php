<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::orderByDesc('published_at')->orderBy('sort_order')->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:300'],
            'slug'           => ['nullable', 'string', 'max:300', 'unique:posts,slug'],
            'excerpt'        => ['nullable', 'string', 'max:1000'],
            'body'           => ['required', 'string', 'max:65000'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'author'         => ['nullable', 'string', 'max:100'],
            'published_at'   => ['nullable', 'date'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

        $base = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title']);
        $data['slug'] = $this->uniqueSlug($base);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:300'],
            'slug'           => ['nullable', 'string', 'max:300', 'unique:posts,slug,' . $post->id],
            'excerpt'        => ['nullable', 'string', 'max:1000'],
            'body'           => ['required', 'string', 'max:65000'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'author'         => ['nullable', 'string', 'max:100'],
            'published_at'   => ['nullable', 'date'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

        $base = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title']);
        $data['slug'] = ($base === $post->slug) ? $post->slug : $this->uniqueSlug($base, $post->id);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        } else {
            unset($data['featured_image']);
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base ?: 'post';
        $original = $slug;
        $counter = 1;

        while (
            Post::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }
}
