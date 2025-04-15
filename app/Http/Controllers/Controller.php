<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Display a listing of the links.
     */
    public function index(Request $request)
    {
        $query = Link::query();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort links
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $links = $query->with('categories')->paginate(15);

        return response()->json($links);
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'categories' => 'array',
            'categories.*' => 'exists:link_categories,id',
        ]);

        $link = Link::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (isset($validated['categories'])) {
            $link->categories()->attach($validated['categories']);
        }

        return response()->json($link->load('categories'), 201);
    }

    /**
     * Display the specified link.
     */
    public function show(Link $link)
    {
        return response()->json($link->load('categories'));
    }

    /**
     * Update the specified link in storage.
     */
    public function update(Request $request, Link $link)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|url|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'categories' => 'array',
            'categories.*' => 'exists:link_categories,id',
        ]);

        $link->update($validated);

        if (isset($validated['categories'])) {
            $link->categories()->sync($validated['categories']);
        }

        return response()->json($link->load('categories'));
    }

    /**
     * Remove the specified link from storage.
     */
    public function destroy(Link $link)
    {
        $link->delete();
        return response()->json(null, 204);
    }
}
