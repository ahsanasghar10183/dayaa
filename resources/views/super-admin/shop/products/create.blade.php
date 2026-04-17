<x-super-admin-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800">Create New Product</h2>
            <a href="{{ route('super-admin.shop.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-gray-700 hover:bg-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" x-data="productForm()">
        <!-- Error Messages -->
        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('super-admin.shop.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
            @csrf

            <div class="space-y-6">
                <!-- Basic Product Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name (Default) *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Name English -->
                    <div>
                        <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">Product Name (English)</label>
                        <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name_en') border-red-500 @enderror">
                        @error('name_en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Name German -->
                    <div>
                        <label for="name_de" class="block text-sm font-medium text-gray-700 mb-1">Product Name (German)</label>
                        <input type="text" name="name_de" id="name_de" value="{{ old('name_de') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name_de') border-red-500 @enderror">
                        @error('name_de')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Default) *</label>
                        <textarea name="description" id="description" rows="4" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description English -->
                    <div class="md:col-span-2">
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">Description (English)</label>
                        <textarea name="description_en" id="description_en" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description_en') border-red-500 @enderror">{{ old('description_en') }}</textarea>
                        @error('description_en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description German -->
                    <div class="md:col-span-2">
                        <label for="description_de" class="block text-sm font-medium text-gray-700 mb-1">Description (German)</label>
                        <textarea name="description_de" id="description_de" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description_de') border-red-500 @enderror">{{ old('description_de') }}</textarea>
                        @error('description_de')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weight -->
                    <div class="md:col-span-2">
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight') }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Product Type Selection -->
                <div class="border-t pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Product Type *</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-blue-500 transition" :class="productType === 'simple' ? 'border-blue-500 ring-2 ring-blue-500' : ''">
                            <input type="radio" name="product_type" value="simple" x-model="productType" class="sr-only">
                            <div class="flex flex-1">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-semibold text-gray-900">Simple Product</span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500">One product with fixed price and inventory</span>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-blue-600" :class="productType === 'simple' ? '' : 'invisible'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </label>

                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-blue-500 transition" :class="productType === 'variable' ? 'border-blue-500 ring-2 ring-blue-500' : ''">
                            <input type="radio" name="product_type" value="variable" x-model="productType" class="sr-only">
                            <div class="flex flex-1">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-semibold text-gray-900">Variable Product</span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500">Product with multiple variations (e.g., sizes, colors)</span>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-blue-600" :class="productType === 'variable' ? '' : 'invisible'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </label>
                    </div>
                    @error('product_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Simple Product Fields -->
                <div x-show="productType === 'simple'" x-transition class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SKU -->
                        <div>
                            <label for="simple_sku" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                            <input type="text" name="sku" id="simple_sku" value="{{ old('sku') }}" :required="productType === 'simple'" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('sku') border-red-500 @enderror">
                            @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="simple_price" class="block text-sm font-medium text-gray-700 mb-1">Price (€) *</label>
                            <input type="number" name="price" id="simple_price" value="{{ old('price') }}" step="0.01" min="0" :required="productType === 'simple'" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Compare Price -->
                        <div>
                            <label for="simple_compare_price" class="block text-sm font-medium text-gray-700 mb-1">Compare Price (€)</label>
                            <input type="number" name="compare_price" id="simple_compare_price" value="{{ old('compare_price') }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Original price to show discount</p>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="simple_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                            <input type="number" name="quantity" id="simple_quantity" value="{{ old('quantity', 0) }}" min="0" :required="productType === 'simple'" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                            @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="mt-6" x-data="imageUpload()">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Product Images</label>

                        <!-- Image Preview Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" x-show="images.length > 0">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="relative group rounded-xl overflow-hidden border-2 border-gray-200 bg-white shadow-sm hover:shadow-md transition-all">
                                    <!-- Image Preview -->
                                    <div class="aspect-square relative">
                                        <img :src="image.preview" :alt="'Product image ' + (index + 1)" class="w-full h-full object-cover">

                                        <!-- Primary Badge -->
                                        <div x-show="index === 0" class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-lg">
                                            Primary
                                        </div>

                                        <!-- Remove Button -->
                                        <button type="button" @click="removeImage(index)"
                                                class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        <!-- Set Primary Button -->
                                        <button type="button" @click="setPrimary(index)" x-show="index !== 0"
                                                class="absolute bottom-2 left-2 right-2 bg-white/90 hover:bg-white text-gray-700 text-xs font-medium px-2 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                                            Set as Primary
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Upload Button -->
                        <div class="relative">
                            <input type="file" id="simple_images" name="images[]" multiple accept="image/png,image/jpeg,image/jpg,image/gif,image/webp"
                                   @change="handleFileSelect($event)" class="hidden">
                            <label for="simple_images"
                                   class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 group-hover:text-gray-700"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF, WebP up to 2MB (Max 10 images)</p>
                                </div>
                            </label>
                        </div>

                        <p class="mt-2 text-xs text-gray-500">First image will be the primary product image</p>
                    </div>
                </div>

                <!-- Variable Product Fields -->
                <div x-show="productType === 'variable'" x-transition class="border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Product Variations</h3>
                            <p class="text-sm text-gray-500 mt-1">Add variations for this product (e.g., different sizes, colors, models)</p>
                        </div>
                        <button type="button" @click="addVariation" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Variation
                        </button>
                    </div>

                    <!-- Variations List -->
                    <div class="space-y-4">
                        <template x-for="(variation, index) in variations" :key="index">
                            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50" x-data="variationImageUpload(index)">
                                <div class="flex items-start justify-between mb-4">
                                    <h4 class="text-md font-semibold text-gray-900" x-text="'Variation ' + (index + 1)"></h4>
                                    <button type="button" @click="removeVariation(index)" class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Variation Name -->
                                    <div class="md:col-span-2">
                                        <label :for="'variation_name_' + index" class="block text-sm font-medium text-gray-700 mb-1">Variation Name *</label>
                                        <input type="text" :name="'variations[' + index + '][name]'" :id="'variation_name_' + index" x-model="variation.name" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <p class="mt-1 text-xs text-gray-500">e.g., "Small", "Red", "Model X", etc.</p>
                                    </div>

                                    <!-- SKU -->
                                    <div>
                                        <label :for="'variation_sku_' + index" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                                        <input type="text" :name="'variations[' + index + '][sku]'" :id="'variation_sku_' + index" x-model="variation.sku" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <label :for="'variation_price_' + index" class="block text-sm font-medium text-gray-700 mb-1">Price (€) *</label>
                                        <input type="number" :name="'variations[' + index + '][price]'" :id="'variation_price_' + index" x-model="variation.price" step="0.01" min="0" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <!-- Compare Price -->
                                    <div>
                                        <label :for="'variation_compare_price_' + index" class="block text-sm font-medium text-gray-700 mb-1">Compare Price (€)</label>
                                        <input type="number" :name="'variations[' + index + '][compare_price]'" :id="'variation_compare_price_' + index" x-model="variation.compare_price" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <!-- Quantity -->
                                    <div>
                                        <label :for="'variation_quantity_' + index" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                                        <input type="number" :name="'variations[' + index + '][quantity]'" :id="'variation_quantity_' + index" x-model="variation.quantity" min="0" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <!-- Variation Images -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Variation Images</label>

                                        <!-- Image Preview Grid -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" x-show="images.length > 0">
                                            <template x-for="(image, imgIndex) in images" :key="imgIndex">
                                                <div class="relative group rounded-xl overflow-hidden border-2 border-gray-200 bg-white shadow-sm hover:shadow-md transition-all">
                                                    <div class="aspect-square relative">
                                                        <img :src="image.preview" :alt="'Variation image ' + (imgIndex + 1)" class="w-full h-full object-cover">

                                                        <!-- Primary Badge -->
                                                        <div x-show="imgIndex === 0" class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-lg">
                                                            Primary
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button type="button" @click="removeImage(imgIndex)"
                                                                class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-lg">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>

                                                        <!-- Set Primary Button -->
                                                        <button type="button" @click="setPrimary(imgIndex)" x-show="imgIndex !== 0"
                                                                class="absolute bottom-2 left-2 right-2 bg-white/90 hover:bg-white text-gray-700 text-xs font-medium px-2 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                                                            Set as Primary
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Upload Button -->
                                        <div class="relative">
                                            <input type="file" :id="'variation_images_' + index" :name="'variations[' + index + '][images][]'" multiple accept="image/png,image/jpeg,image/jpg,image/gif,image/webp"
                                                   @change="handleFileSelect($event)" class="hidden">
                                            <label :for="'variation_images_' + index"
                                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-white hover:border-blue-500 transition-all group">
                                                <div class="flex flex-col items-center justify-center py-4">
                                                    <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <p class="text-xs text-gray-500 group-hover:text-gray-700"><span class="font-semibold">Click to upload</span> images</p>
                                                    <p class="text-xs text-gray-500">Max 10 images, 2MB each</p>
                                                </div>
                                            </label>
                                        </div>

                                        <p class="mt-2 text-xs text-gray-500">First image will be the primary variation image</p>
                                    </div>

                                    <!-- Active Status -->
                                    <div class="md:col-span-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" :name="'variations[' + index + '][is_active]'" :id="'variation_active_' + index" value="1" x-model="variation.is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label :for="'variation_active_' + index" class="ml-2 text-sm text-gray-700">Active (available for purchase)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div x-show="variations.length === 0" class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No variations added</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first variation</p>
                            <div class="mt-6">
                                <button type="button" @click="addVariation" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Variation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Checkboxes -->
                <div class="border-t pt-6 space-y-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Product Status</h3>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active (visible in shop)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured (show on homepage)</label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-3 border-t pt-6">
                <a href="{{ route('super-admin.shop.products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create Product
                </button>
            </div>
        </form>
    </div>

    <script>
        function productForm() {
            return {
                productType: '{{ old('product_type', 'simple') }}',
                variations: []
            }
        }

        function imageUpload() {
            return {
                images: [],
                maxImages: 10,

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);

                    if (this.images.length + files.length > this.maxImages) {
                        alert(`You can only upload a maximum of ${this.maxImages} images.`);
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
                            this.images.push({
                                file: file,
                                preview: e.target.result,
                                name: file.name
                            });
                            this.updateFileInput();
                        };
                        reader.readAsDataURL(file);
                    });
                },

                removeImage(index) {
                    this.images.splice(index, 1);
                    this.updateFileInput();
                },

                setPrimary(index) {
                    const [image] = this.images.splice(index, 1);
                    this.images.unshift(image);
                    this.updateFileInput();
                },

                updateFileInput() {
                    const input = event.target.closest('[x-data]').querySelector('input[type="file"]');
                    if (!input) return;

                    const dataTransfer = new DataTransfer();
                    this.images.forEach(image => {
                        dataTransfer.items.add(image.file);
                    });
                    input.files = dataTransfer.files;
                }
            }
        }

        function variationImageUpload(variationIndex) {
            return {
                images: [],
                maxImages: 10,

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);

                    if (this.images.length + files.length > this.maxImages) {
                        alert(`You can only upload a maximum of ${this.maxImages} images per variation.`);
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
                            this.images.push({
                                file: file,
                                preview: e.target.result,
                                name: file.name
                            });
                            this.updateFileInput();
                        };
                        reader.readAsDataURL(file);
                    });
                },

                removeImage(index) {
                    this.images.splice(index, 1);
                    this.updateFileInput();
                },

                setPrimary(index) {
                    const [image] = this.images.splice(index, 1);
                    this.images.unshift(image);
                    this.updateFileInput();
                },

                updateFileInput() {
                    const input = document.getElementById('variation_images_' + variationIndex);
                    if (!input) return;

                    const dataTransfer = new DataTransfer();
                    this.images.forEach(image => {
                        dataTransfer.items.add(image.file);
                    });
                    input.files = dataTransfer.files;
                }
            }
        }

        // Add variation function in parent scope
        document.addEventListener('alpine:init', () => {
            Alpine.data('productForm', () => ({
                productType: '{{ old('product_type', 'simple') }}',
                variations: [],

                addVariation() {
                    this.variations.push({
                        name: '',
                        sku: '',
                        price: '',
                        compare_price: '',
                        quantity: 0,
                        is_active: true
                    });
                },

                removeVariation(index) {
                    this.variations.splice(index, 1);
                }
            }));
        });
    </script>
</x-super-admin-sidebar-layout>
