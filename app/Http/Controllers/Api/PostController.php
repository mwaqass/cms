<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|array',
            'category_id' => 'nullable|exists:categories,id',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'featured_image' => $request->featured_image,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'tags' => $request->tags ?? [],
            'category_id' => $request->category_id,
            'is_featured' => $request->get('is_featured', false),
            'allow_comments' => $request->get('allow_comments', true),
            'published_at' => $request->published_at,
        ]);

        return response()->json($post->load(['user', 'category']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post->load(['user', 'category']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($post->id),
            ],
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|array',
            'category_id' => 'nullable|exists:categories,id',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $post->update([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'featured_image' => $request->featured_image,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'tags' => $request->tags ?? $post->tags,
            'category_id' => $request->category_id,
            'is_featured' => $request->get('is_featured', $post->is_featured),
            'allow_comments' => $request->get('allow_comments', $post->allow_comments),
            'published_at' => $request->published_at,
        ]);

        return response()->json($post->load(['user', 'category']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
