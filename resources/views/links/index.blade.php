<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Source Link Widget - My Links</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Source Link Widget</h1>
                        <p class="text-gray-600 mt-1">Never forget where your links came from</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('links.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus mr-2"></i>Add Link
                        </a>
                        <a href="{{ route('categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-tags mr-2"></i>Categories
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Search and Filter -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" placeholder="Search links..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\LinkCategory::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button id="clearFilters" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Links Grid -->
            <div id="linksGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($links as $link)
                    <div class="link-card bg-white rounded-lg shadow-sm border hover:shadow-md transition-all duration-200" 
                         data-title="{{ strtolower($link->title) }}" 
                         data-description="{{ strtolower($link->description ?? '') }}"
                         data-categories="{{ $link->categories->pluck('id')->join(',') }}">
                        <div class="p-6">
                            <!-- Link Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    @if($link->favicon)
                                        <img src="{{ $link->favicon }}" alt="Favicon" class="w-6 h-6 rounded">
                                    @else
                                        <div class="w-6 h-6 bg-gray-300 rounded flex items-center justify-center">
                                            <i class="fas fa-link text-gray-500 text-xs"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $link->title }}</h3>
                                        <p class="text-sm text-gray-500 truncate">{{ parse_url($link->url, PHP_URL_HOST) }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('links.edit', $link) }}" class="text-gray-400 hover:text-blue-600 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this link?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($link->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $link->description }}</p>
                            @endif

                            <!-- Categories -->
                            @if($link->categories->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($link->categories as $category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex justify-between items-center">
                                <a href="{{ $link->url }}" target="_blank" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Visit Link
                                </a>
                                <span class="text-xs text-gray-400">
                                    {{ $link->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-link text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No links yet</h3>
                        <p class="text-gray-600 mb-6">Start saving links to never forget where they came from!</p>
                        <a href="{{ route('links.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus mr-2"></i>Add Your First Link
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const clearFilters = document.getElementById('clearFilters');
            const linksGrid = document.getElementById('linksGrid');
            const linkCards = document.querySelectorAll('.link-card');

            function filterLinks() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCategory = categoryFilter.value;

                linkCards.forEach(card => {
                    const title = card.dataset.title;
                    const description = card.dataset.description;
                    const categories = card.dataset.categories;

                    const matchesSearch = !searchTerm || 
                        title.includes(searchTerm) || 
                        description.includes(searchTerm);

                    const matchesCategory = !selectedCategory || 
                        categories.includes(selectedCategory);

                    if (matchesSearch && matchesCategory) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterLinks);
            categoryFilter.addEventListener('change', filterLinks);
            
            clearFilters.addEventListener('click', function() {
                searchInput.value = '';
                categoryFilter.value = '';
                filterLinks();
            });
        });
    </script>
</body>
</html>
