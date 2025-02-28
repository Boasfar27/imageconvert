<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Convert New Image') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('conversions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Select Image (JPG or PNG, max 5MB)
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        JPG or PNG up to 5MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div>
                            <label for="quality" class="block text-sm font-medium text-gray-700">
                                WebP Quality (Optional)
                            </label>
                            <div class="mt-1">
                                <input type="range" 
                                       name="quality" 
                                       id="quality" 
                                       min="1" 
                                       max="100" 
                                       value="80"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                       oninput="qualityValue.textContent = this.value + '%'">
                                <span id="qualityValue" class="text-sm text-gray-600">80%</span>
                                <p class="mt-1 text-sm text-gray-500">
                                    Higher quality means larger file size. Default is 80%.
                                </p>
                            </div>
                        </div>

                        @error('quality')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex justify-end">
                            <button type="submit" class="gradient-bg text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                Convert to WebP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        }
    </style>
</x-app-layout> 