<x-super-admin-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800">Edit Product: {{ $product->name }}</h2>
            <a href="{{ route('super-admin.shop.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-gray-700 hover:bg-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('super-admin.shop.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select name="category_id" id="category_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('sku') border-red-500 @enderror">
                    @error('sku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (€) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                    @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compare Price -->
                <div>
                    <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-1">Compare Price (€)</label>
                    <input type="number" name="compare_price" id="compare_price" value="{{ old('compare_price', $product->compare_price) }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Original price to show discount</p>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" min="0" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                    @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="description" rows="4" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Images Manager -->
                <div class="md:col-span-2" x-data="imageManager()">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Product Images</label>

                    <!-- Existing Images Grid -->
                    @if($product->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        @foreach($product->images as $existingImage)
                        <div class="relative group rounded-xl overflow-hidden border-2 border-gray-200 bg-white shadow-sm hover:shadow-md transition-all"
                             data-image-id="{{ $existingImage->id }}">
                            <!-- Image Preview -->
                            <div class="aspect-square relative">
                                <img src="{{ asset('storage/' . $existingImage->image_path) }}"
                                     alt="{{ $existingImage->alt_text }}"
                                     class="w-full h-full object-cover">

                                <!-- Primary Badge -->
                                @if($existingImage->is_primary)
                                <div class="absolute top-2 left-2 bg-gradient-dayaa text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-lg">
                                    Primary
                                </div>
                                @endif

                                <!-- Delete Button -->
                                <button type="button"
                                        @click="markForDeletion({{ $existingImage->id }})"
                                        class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                <!-- Set Primary Button -->
                                @if(!$existingImage->is_primary)
                                <form action="{{ route('super-admin.shop.products.set-primary-image', [$product, $existingImage]) }}"
                                      method="POST" class="absolute bottom-2 left-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full bg-white/90 hover:bg-white text-gray-700 text-xs font-medium px-2 py-1.5 rounded-lg shadow-md transition-colors">
                                        Set as Primary
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- New Images Preview Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" x-show="newImages.length > 0">
                        <template x-for="(image, index) in newImages" :key="index">
                            <div class="relative group rounded-xl overflow-hidden border-2 border-green-300 bg-white shadow-sm hover:shadow-md transition-all">
                                <!-- Image Preview -->
                                <div class="aspect-square relative">
                                    <img :src="image.preview" :alt="'New image ' + (index + 1)" class="w-full h-full object-cover">

                                    <!-- New Badge -->
                                    <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-lg">
                                        New
                                    </div>

                                    <!-- Remove Button -->
                                    <button type="button" @click="removeNewImage(index)"
                                            class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Upload Button -->
                    <div class="relative">
                        <input type="file" id="new-images" name="images[]" multiple accept="image/*"
                               @change="handleFileSelect($event)" class="hidden">
                        <label for="new-images"
                               class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-primary-500 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 group-hover:text-gray-700"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </label>
                    </div>

                    <!-- Hidden inputs for images to delete -->
                    <template x-for="imageId in imagesToDelete" :key="imageId">
                        <input type="hidden" name="delete_images[]" :value="imageId">
                    </template>

                    <p class="mt-2 text-xs text-gray-500">💡 Green border indicates new images to be uploaded. Click "Set as Primary" on any existing image to make it the main product image.</p>
                </div>

                <script>
                    function imageManager() {
                        return {
                            newImages: [],
                            imagesToDelete: [],
                            maxImages: 10,
                            currentImageCount: {{ $product->images->count() }},

                            handleFileSelect(event) {
                                const files = Array.from(event.target.files);
                                const totalImages = this.currentImageCount + this.newImages.length - this.imagesToDelete.length + files.length;

                                if (totalImages > this.maxImages) {
                                    alert(`You can only have a maximum of ${this.maxImages} images.`);
                                    return;
                                }

                                files.forEach(file => {
                                    if (file.size > 2 * 1024 * 1024) {
                                        alert(`${file.name} is larger than 2MB.`);
                                        return;
                                    }

                                    if (!file.type.startsWith('image/')) {
                                        alert(`${file.name} is not an image file.`);
                                        return;
                                    }

                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.newImages.push({
                                            file: file,
                                            preview: e.target.result,
                                            name: file.name
                                        });
                                    };
                                    reader.readAsDataURL(file);
                                });

                                // Reset input
                                event.target.value = '';
                            },

                            removeNewImage(index) {
                                this.newImages.splice(index, 1);
                            },

                            markForDeletion(imageId) {
                                if (confirm('Are you sure you want to delete this image?')) {
                                    this.imagesToDelete.push(imageId);
                                    // Hide the image element
                                    const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
                                    if (imageElement) {
                                        imageElement.style.opacity = '0.3';
                                        imageElement.style.pointerEvents = 'none';
                                        // Add deleted badge
                                        const badge = document.createElement('div');
                                        badge.className = 'absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10';
                                        badge.innerHTML = '<span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-lg">Deleted</span>';
                                        imageElement.querySelector('.aspect-square').appendChild(badge);
                                    }
                                }
                            }
                        }
                    }
                </script>

                <!-- Status Checkboxes -->
                <div class="md:col-span-2 space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active (visible in shop)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured (show on homepage)</label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('super-admin.shop.products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</x-super-admin-sidebar-layout>
