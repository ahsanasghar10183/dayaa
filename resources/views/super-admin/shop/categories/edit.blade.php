<x-super-admin-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800">Edit Category: {{ $category->name }}</h2>
            <a href="{{ route('super-admin.shop.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-gray-700 hover:bg-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('super-admin.shop.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Category -->
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">None (Root Category)</option>
                        @foreach($categories as $cat)
                        @if($cat->id !== $category->id)
                        <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endif
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Leave empty to create a top-level category</p>
                    @error('parent_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Category Image -->
                @if($category->image)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                    <div class="flex items-center space-x-4">
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover rounded border border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Current category image</p>
                            <p class="text-xs text-gray-500 mt-1">Upload a new image below to replace it</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Category Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">{{ $category->image ? 'Replace' : 'Upload' }} Category Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Optional. Max size: 2MB. Formats: JPG, PNG, GIF. {{ $category->image ? 'Leave empty to keep current image.' : '' }}</p>
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                    @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active (visible in shop)</label>
                    </div>
                </div>

                <!-- Category Stats -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Products</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $category->products()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Subcategories</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $category->children()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('super-admin.shop.categories.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</x-super-admin-sidebar-layout>
