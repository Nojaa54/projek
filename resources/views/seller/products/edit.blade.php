<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-ikea-black leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue" type="text" name="name" :value="$product->name" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue rounded-md shadow-sm" rows="4" required>{{ $product->description }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="price" :value="__('Price ($)')" />
                                <x-text-input id="price" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue" type="number" step="0.01" name="price" :value="$product->price" required />
                            </div>
                            <div>
                                <x-input-label for="stock" :value="__('Stock')" />
                                <x-text-input id="stock" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue" type="number" name="stock" :value="$product->stock" required />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="images" :value="__('Add More Images')" />
                             <input type="file" id="images" name="images[]" multiple class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-ikea-blue file:text-white hover:file:bg-blue-700 transition" />
                             <!-- Display existing images -->
                             <div class="mt-4 flex gap-2 overflow-x-auto">
                                @foreach($product->images as $image)
                                    <div class="w-16 h-16 border rounded overflow-hidden">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                             </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                             <a href="{{ route('seller.dashboard') }}" class="text-gray-600 hover:text-black hover:underline font-bold">Cancel</a>
                            <button type="submit" class="bg-ikea-blue hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-full transition">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
