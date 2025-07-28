<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with(['items' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('name')->paginate(10);
        
        return response()->json($menus);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:menus',
            'description' => 'nullable|string',
            'location' => 'required|in:header,footer,sidebar',
            'is_active' => 'boolean',
        ]);

        $menu = Menu::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'location' => $request->location,
            'is_active' => $request->get('is_active', true),
        ]);

        return response()->json($menu->load('items'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json($menu->load(['items' => function ($query) {
            $query->orderBy('order');
        }]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('menus')->ignore($menu->id),
            ],
            'description' => 'nullable|string',
            'location' => 'required|in:header,footer,sidebar',
            'is_active' => 'boolean',
        ]);

        $menu->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'location' => $request->location,
            'is_active' => $request->get('is_active', $menu->is_active),
        ]);

        return response()->json($menu->load('items'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu deleted successfully']);
    }

    /**
     * Store a menu item
     */
    public function storeItem(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'target' => 'in:_self,_blank,_parent,_top',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'attributes' => 'nullable|array',
        ]);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => $request->title,
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'order' => $request->order ?? 0,
            'is_active' => $request->get('is_active', true),
            'attributes' => $request->attributes,
        ]);

        return response()->json($item, 201);
    }

    /**
     * Update a menu item
     */
    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'target' => 'in:_self,_blank,_parent,_top',
            'icon' => 'nullable|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:menu_items,id',
                function ($attribute, $value, $fail) use ($item) {
                    if ($value == $item->id) {
                        $fail('A menu item cannot be its own parent.');
                    }
                },
            ],
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'attributes' => 'nullable|array',
        ]);

        $item->update([
            'title' => $request->title,
            'url' => $request->url,
            'target' => $request->target ?? $item->target,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'order' => $request->order ?? $item->order,
            'is_active' => $request->get('is_active', $item->is_active),
            'attributes' => $request->attributes ?? $item->attributes,
        ]);

        return response()->json($item);
    }

    /**
     * Delete a menu item
     */
    public function destroyItem(Menu $menu, MenuItem $item)
    {
        $item->delete();
        return response()->json(['message' => 'Menu item deleted successfully']);
    }
}
