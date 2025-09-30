<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $link->title }} - Source Link Widget</title>
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
                        <h1 class="text-3xl font-bold text-gray-900">Link Details</h1>
                        <p class="text-gray-600 mt-1">View and manage your saved link</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('links.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Links
                        </a>
                        <a href="{{ route('links.edit', $link) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-edit mr-2"></i>Edit Link
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Link Details -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <!-- Link Header -->
                <div class="flex items-start space-x-4 mb-6">
                    @if($link->favicon)
                        <img src="{{ $link->favicon }}" alt="Favicon" class="w-12 h-12 rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                            <i class="fas fa-link text-gray-500 text-xl"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $link->title }}</h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-globe mr-2"></i>
                                {{ parse_url($link->url, PHP_URL_HOST) }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                Saved {{ $link->created_at->diffForHumans() }}
                            </span>
                            @if($link->updated_at != $link->created_at)
                                <span class="flex items-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Updated {{ $link->updated_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($link->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $link->description }}</p>
                    </div>
                @endif

                <!-- Categories -->
                @if($link->categories->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($link->categories as $category)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- URL -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">URL</h3>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               value="{{ $link->url }}" 
                               readonly 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                        <button onclick="copyToClipboard('{{ $link->url }}')" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ $link->url }}" 
                       target="_blank" 
                       class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Visit Link
                    </a>
                    
                    <a href="{{ route('links.edit', $link) }}" 
                       class="flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Link
                    </a>
                    
                    <form action="{{ route('links.destroy', $link) }}" 
                          method="POST" 
                          class="flex"
                          onsubmit="return confirm('Are you sure you want to delete this link? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center justify-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show a temporary success message
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.add('bg-green-100', 'text-green-800');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-100', 'text-green-800');
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Could not copy to clipboard');
            });
        }
    </script>
</body>
</html>
