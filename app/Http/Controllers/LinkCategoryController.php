<?php

namespace App\Http\Controllers;

use App\Models\LinkCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = LinkCategory::withCount('links')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
        ]);

        $category = LinkCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'color' => $validated['color'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified category.
     */
    public function show(LinkCategory $category)
    {
        return response()->json($category->load('links'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, LinkCategory $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(LinkCategory $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
