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
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name (Default) *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Name English -->
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">Product Name (English)</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $product->name_en) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name_en') border-red-500 @enderror">
                    @error('name_en')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Name German -->
                <div>
                    <label for="name_de" class="block text-sm font-medium text-gray-700 mb-1">Product Name (German)</label>
                    <input type="text" name="name_de" id="name_de" value="{{ old('name_de', $product->name_de) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name_de') border-red-500 @enderror">
                    @error('name_de')
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
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Default) *</label>
                    <textarea name="description" id="description" rows="4" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description English -->
                <div class="md:col-span-2">
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">Description (English)</label>
                    <textarea name="description_en" id="description_en" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description_en') border-red-500 @enderror">{{ old('description_en', $product->description_en) }}</textarea>
                    @error('description_en')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description German -->
                <div class="md:col-span-2">
                    <label for="description_de" class="block text-sm font-medium text-gray-700 mb-1">Description (German)</label>
                    <textarea name="description_de" id="description_de" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description_de') border-red-500 @enderror">{{ old('description_de', $product->description_de) }}</textarea>
                    @error('description_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Images Manager -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Product Images</label>

                    <!-- Existing Images Grid -->
                    @if($product->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        @foreach($product->images as $existingImage)
                        <div class="relative group rounded-xl overflow-hidden border-2 border-gray-200 bg-white shadow-sm hover:shadow-md transition-all"
                             data-image-id="{{ $existingImage->id }}">
                            <!-- Image Preview -->
                            <div class="aspect-square relative">
                                <img src="{{ $existingImage->url }}"
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
                                        onclick="markForDeletion({{ $existingImage->id }})"
                                        class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                <!-- Set Primary Button -->
                                @if(!$existingImage->is_primary)
                                <button type="button"
                                        onclick="setPrimaryImage({{ $product->id }}, {{ $existingImage->id }})"
                                        class="absolute bottom-2 left-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity w-full bg-white/90 hover:bg-white text-gray-700 text-xs font-medium px-2 py-1.5 rounded-lg shadow-md transition-colors">
                                    Set as Primary
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- New Images Preview Grid -->
                    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" style="display: none;"></div>

                    <!-- Upload Button -->
                    <div class="relative">
                        <input type="file" id="new-images" name="images[]" multiple accept="image/png,image/jpeg,image/jpg,image/gif,image/webp"
                               onchange="previewImages(event)" class="hidden">
                        <label for="new-images"
                               class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 group-hover:text-gray-700"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, WebP up to 2MB (Max 10 images total)</p>
                            </div>
                        </label>
                    </div>

                    <script>
                        function previewImages(event) {
                            const container = document.getElementById('preview-container');
                            const files = event.target.files;

                            if (files.length > 0) {
                                container.style.display = 'grid';
                                container.innerHTML = ''; // Clear previous previews

                                Array.from(files).forEach((file, index) => {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const div = document.createElement('div');
                                        div.className = 'relative group rounded-xl overflow-hidden border-2 border-green-300 bg-white shadow-sm hover:shadow-md transition-all';
                                        div.innerHTML = `
                                            <div class="aspect-square relative">
                                                <img src="${e.target.result}" alt="New image ${index + 1}" class="w-full h-full object-cover">
                                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-lg">
                                                    New
                                                </div>
                                            </div>
                                        `;
                                        container.appendChild(div);
                                    };
                                    reader.readAsDataURL(file);
                                });
                            } else {
                                container.style.display = 'none';
                            }
                        }
                    </script>

                    <!-- Hidden container for delete image IDs -->
                    <div id="delete-images-container"></div>

                    <p class="mt-2 text-xs text-gray-500">💡 Green border indicates new images to be uploaded. Click "Set as Primary" on any existing image to make it the main product image.</p>
                </div>

                <script>
                    function markForDeletion(imageId) {
                        if (confirm('Are you sure you want to delete this image?')) {
                            // Add hidden input for deletion
                            const container = document.getElementById('delete-images-container');
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'delete_images[]';
                            input.value = imageId;
                            container.appendChild(input);

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

                    function setPrimaryImage(productId, imageId) {
                        // Create a temporary form to submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/super-admin/shop/products/${productId}/images/${imageId}/set-primary`;

                        // Add CSRF token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Add method spoofing for PATCH
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';
                        form.appendChild(methodInput);

                        // Append to body and submit
                        document.body.appendChild(form);
                        form.submit();
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

        <!-- Product Variations Section -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Product Variations</h3>
                    <p class="text-sm text-gray-600 mt-1">Add variations like Style-1, Style-2, Style-3, etc.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-700">Product Type:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $product->isVariable() ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $product->isVariable() ? 'Variable' : 'Simple' }}
                    </span>
                </div>
            </div>

            @if($product->isVariable() && $product->quantity > 0)
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">⚠️ <strong>Note:</strong> This product is set to Variable type. Stock is managed per variation. The parent product quantity ({{ $product->quantity }}) is not used.</p>
            </div>
            @endif

            <!-- Add Variation Form -->
            <form action="{{ route('super-admin.shop.products.variations.store', $product) }}" method="POST" enctype="multipart/form-data" class="mb-6 p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                @csrf
                <h4 class="font-medium text-gray-700 mb-4">Add New Variation</h4>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <label for="variation_name" class="block text-sm font-medium text-gray-700 mb-1">Variation Name *</label>
                        <input type="text" name="name" id="variation_name" required placeholder="e.g., Style-1, Blue, Large"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="variation_sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" id="variation_sku" placeholder="Optional"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="variation_price" class="block text-sm font-medium text-gray-700 mb-1">Price (€)</label>
                        <input type="number" name="price" id="variation_price" step="0.01" min="0" placeholder="Uses product price if empty"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="variation_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                        <input type="number" name="quantity" id="variation_quantity" required min="0" value="0"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Add Variation
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="variation_images" class="block text-sm font-medium text-gray-700 mb-1">Variation Images (Optional)</label>

                    <!-- New Images Preview Grid for Add Variation -->
                    <div id="new-variation-preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" style="display: none;"></div>

                    <div class="relative">
                        <input type="file" id="variation_images" name="images[]" multiple accept="image/png,image/jpeg,image/jpg,image/gif,image/webp"
                               onchange="previewVariationImages(event)" class="hidden">
                        <label for="variation_images"
                               class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-green-500 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-1 text-sm text-gray-500 group-hover:text-gray-700"><span class="font-semibold">Click to upload</span> variation images</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, WebP up to 2MB (Max 10 images)</p>
                            </div>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Upload multiple images for this variation. First image will be the primary image. If no images uploaded, product's primary image will be used.</p>
                </div>

                <script>
                    function previewVariationImages(event) {
                        const container = document.getElementById('new-variation-preview-container');
                        const files = event.target.files;

                        if (files.length > 0) {
                            container.style.display = 'grid';
                            container.innerHTML = '';

                            Array.from(files).forEach((file, index) => {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const div = document.createElement('div');
                                    div.className = 'relative group rounded-xl overflow-hidden border-2 border-green-300 bg-white shadow-sm';
                                    div.innerHTML = `
                                        <div class="aspect-square relative">
                                            <img src="${e.target.result}" alt="New image ${index + 1}" class="w-full h-full object-cover">
                                            <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-lg">
                                                ${index === 0 ? 'Primary' : 'New'}
                                            </div>
                                        </div>
                                    `;
                                    container.appendChild(div);
                                };
                                reader.readAsDataURL(file);
                            });
                        } else {
                            container.style.display = 'none';
                        }
                    }
                </script>
                <div class="mt-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </form>

            <!-- Variations List -->
            @if($product->variations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($product->variations as $variation)
                        <tr id="variation-{{ $variation->id }}" class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="relative w-16 h-16 rounded-lg overflow-hidden border-2 {{ $variation->hasOwnImage() ? 'border-blue-300' : 'border-gray-200' }}">
                                    <img src="{{ $variation->image_url }}" alt="{{ $variation->name }}" class="w-full h-full object-cover">
                                    @if($variation->images_count > 0)
                                    <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded-bl font-semibold" title="{{ $variation->images_count }} images">
                                        {{ $variation->images_count }}
                                    </div>
                                    @elseif($variation->hasOwnImage())
                                    <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs px-1 rounded-bl" title="Has own image">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $variation->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $variation->sku ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                @if($variation->price)
                                    {{ $variation->formatted_price }}
                                @else
                                    <span class="text-gray-500 text-xs">({{ $product->formatted_price }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $variation->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $variation->quantity }} units
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form action="{{ route('super-admin.shop.products.variations.toggle-status', [$product, $variation]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium transition {{ $variation->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                        {{ $variation->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-sm text-right space-x-2">
                                <button onclick="editVariation({{ $variation->id }}, '{{ $variation->name }}', '{{ $variation->sku }}', '{{ $variation->price }}', {{ $variation->quantity }}, '{{ $variation->image_url }}')"
                                        class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                <form action="{{ route('super-admin.shop.products.variations.destroy', [$product, $variation]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this variation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-2 text-sm">No variations yet. Add your first variation above.</p>
            </div>
            @endif
        </div>

        <!-- Edit Variation Modal (Hidden by default) -->
        <div id="edit-variation-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">Edit Variation</h3>
                <form id="edit-variation-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Variation Name *</label>
                                <input type="text" name="name" id="edit_variation_name" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                <input type="text" name="sku" id="edit_variation_sku"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (€)</label>
                                <input type="number" name="price" id="edit_variation_price" step="0.01" min="0"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                                <input type="number" name="quantity" id="edit_variation_quantity" required min="0"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Existing Images Gallery -->
                        <div id="edit_variation_images_container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                            <div id="edit_variation_images_grid" class="grid grid-cols-3 md:grid-cols-4 gap-3 mb-3"></div>
                        </div>

                        <!-- Upload New Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Add More Images (Optional)</label>

                            <!-- New Images Preview -->
                            <div id="edit-variation-preview-container" class="grid grid-cols-3 md:grid-cols-4 gap-3 mb-3" style="display: none;"></div>

                            <div class="relative">
                                <input type="file" id="edit_variation_new_images" name="images[]" multiple accept="image/png,image/jpeg,image/jpg,image/gif,image/webp"
                                       onchange="previewEditVariationImages(event)" class="hidden">
                                <label for="edit_variation_new_images"
                                       class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all group">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 mb-1 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 group-hover:text-gray-700"><span class="font-semibold">Upload more images</span></p>
                                        <p class="text-xs text-gray-500">Max 10 images total</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit_variation_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Update Variation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            let currentVariationId = null;

            async function editVariation(id, name, sku, price, quantity, imageUrl) {
                currentVariationId = id;
                const form = document.getElementById('edit-variation-form');
                form.action = `/super-admin/shop/products/{{ $product->id }}/variations/${id}`;

                document.getElementById('edit_variation_name').value = name;
                document.getElementById('edit_variation_sku').value = sku || '';
                document.getElementById('edit_variation_price').value = price || '';
                document.getElementById('edit_variation_quantity').value = quantity;

                // Clear previous images and previews
                document.getElementById('edit_variation_images_grid').innerHTML = '';
                document.getElementById('edit-variation-preview-container').innerHTML = '';
                document.getElementById('edit-variation-preview-container').style.display = 'none';
                document.getElementById('edit_variation_new_images').value = '';

                // Fetch variation images from server
                try {
                    const response = await fetch(`/super-admin/shop/products/{{ $product->id }}/variations/${id}/images`);
                    if (response.ok) {
                        const data = await response.json();
                        displayVariationImages(data.images);
                    }
                } catch (error) {
                    console.error('Error fetching variation images:', error);
                }

                document.getElementById('edit-variation-modal').classList.remove('hidden');
            }

            function displayVariationImages(images) {
                const grid = document.getElementById('edit_variation_images_grid');
                grid.innerHTML = '';

                if (images && images.length > 0) {
                    images.forEach((image, index) => {
                        const div = document.createElement('div');
                        div.className = 'relative group rounded-lg overflow-hidden border-2 border-gray-300 bg-white shadow-sm hover:shadow-md transition-all';
                        div.innerHTML = `
                            <div class="aspect-square relative">
                                <img src="${image.url}" alt="Variation image ${index + 1}" class="w-full h-full object-cover">
                                ${image.is_primary ? `
                                    <div class="absolute top-1 left-1 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded shadow">
                                        Primary
                                    </div>
                                ` : ''}
                                <button type="button"
                                        onclick="deleteVariationImage(${image.id})"
                                        class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        `;
                        grid.appendChild(div);
                    });
                } else {
                    grid.innerHTML = '<p class="text-sm text-gray-500 col-span-full">No images for this variation. Upload some below!</p>';
                }
            }

            async function deleteVariationImage(imageId) {
                if (!confirm('Are you sure you want to delete this image?')) {
                    return;
                }

                try {
                    const response = await fetch(`/super-admin/shop/products/{{ $product->id }}/variations/${currentVariationId}/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        // Refresh the images
                        const imagesResponse = await fetch(`/super-admin/shop/products/{{ $product->id }}/variations/${currentVariationId}/images`);
                        const data = await imagesResponse.json();
                        displayVariationImages(data.images);
                    } else {
                        alert('Error deleting image. Please try again.');
                    }
                } catch (error) {
                    console.error('Error deleting image:', error);
                    alert('Error deleting image. Please try again.');
                }
            }

            function previewEditVariationImages(event) {
                const container = document.getElementById('edit-variation-preview-container');
                const files = event.target.files;

                if (files.length > 0) {
                    container.style.display = 'grid';
                    container.innerHTML = '';

                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative rounded-lg overflow-hidden border-2 border-green-300 bg-white shadow-sm';
                            div.innerHTML = `
                                <div class="aspect-square relative">
                                    <img src="${e.target.result}" alt="New image ${index + 1}" class="w-full h-full object-cover">
                                    <div class="absolute top-1 left-1 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded shadow">
                                        New
                                    </div>
                                </div>
                            `;
                            container.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    container.style.display = 'none';
                }
            }

            function closeEditModal() {
                document.getElementById('edit-variation-modal').classList.add('hidden');
                currentVariationId = null;
            }

            // Close modal on outside click
            document.getElementById('edit-variation-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });
        </script>
    </div>
</x-super-admin-sidebar-layout>
