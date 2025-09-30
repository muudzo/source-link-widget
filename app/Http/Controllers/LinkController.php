<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkCategory;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::with('categories')->get();
        return view('links.index', compact('links'));
    }

    public function create()
    {
        $categories = LinkCategory::all();
        return view('links.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string',
            'favicon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:link_categories,id',
        ]);

        $link = Link::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'description' => $validated['description'] ?? null,
            'favicon' => $validated['favicon'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (isset($validated['categories'])) {
            $link->categories()->attach($validated['categories']);
        }

        return redirect()->route('links.index')->with('success', 'Link created successfully.');
    }

    public function show(Link $link)
    {
        $link->load('categories');
        return view('links.show', compact('link'));
    }

    public function edit(Link $link)
    {
        $categories = LinkCategory::all();
        $link->load('categories');
        return view('links.edit', compact('link', 'categories'));
    }

    public function update(Request $request, Link $link)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string',
            'favicon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:link_categories,id',
        ]);

        $link->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'description' => $validated['description'] ?? null,
            'favicon' => $validated['favicon'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (isset($validated['categories'])) {
            $link->categories()->sync($validated['categories']);
        } else {
            $link->categories()->detach();
        }

        return redirect()->route('links.index')->with('success', 'Link updated successfully.');
    }

    public function destroy(Link $link)
    {
        $link->categories()->detach();
        $link->delete();
        return redirect()->route('links.index')->with('success', 'Link deleted successfully.');
    }

    // API Methods for browser extension
    public function apiIndex()
    {
        $links = Link::with('categories')->get();
        return response()->json($links);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string',
            'favicon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:link_categories,id',
        ]);

        $link = Link::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'description' => $validated['description'] ?? null,
            'favicon' => $validated['favicon'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (isset($validated['categories'])) {
            $link->categories()->attach($validated['categories']);
        }

        return response()->json($link->load('categories'), 201);
    }

    public function apiShow(Link $link)
    {
        return response()->json($link->load('categories'));
    }

    public function apiUpdate(Request $request, Link $link)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|url|max:2048',
            'description' => 'nullable|string',
            'favicon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:link_categories,id',
        ]);

        $link->update($validated);

        if (isset($validated['categories'])) {
            $link->categories()->sync($validated['categories']);
        } else {
            $link->categories()->detach();
        }

        return response()->json($link->load('categories'));
    }

    public function apiDestroy(Link $link)
    {
        $link->categories()->detach();
        $link->delete();
        return response()->json(null, 204);
    }
}