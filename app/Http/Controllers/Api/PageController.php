<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json($pages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $page = Page::create([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'featured_image' => $request->featured_image,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'order' => $request->order ?? 0,
            'is_featured' => $request->get('is_featured', false),
            'published_at' => $request->published_at,
        ]);

        return response()->json($page, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return response()->json($page->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages')->ignore($page->id),
            ],
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'featured_image' => $request->featured_image,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'order' => $request->order ?? $page->order,
            'is_featured' => $request->get('is_featured', $page->is_featured),
            'published_at' => $request->published_at,
        ]);

        return response()->json($page);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json(['message' => 'Page deleted successfully']);
    }
}
