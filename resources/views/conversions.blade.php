<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Image Conversions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Upload Image</h3>
                    <form action="{{ route('image.convert') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Choose Image (JPG or PNG)</label>
                            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" required
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        </div>
                        
                        <div>
                            <label for="quality" class="block text-sm font-medium text-gray-700">WebP Quality (1-100)</label>
                            <input type="number" name="quality" id="quality" min="1" max="100" value="80"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Convert to WebP
                        </button>
                    </form>

                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 rounded-md">
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-4 p-4 bg-red-100 rounded-md">
                            <ul class="list-disc list-inside text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 