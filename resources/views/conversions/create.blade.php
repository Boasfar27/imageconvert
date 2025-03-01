<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ __('Convert New Image') }}
            </h2>
            <a href="{{ route('conversions.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Back to Conversions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative flex items-center" role="alert">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('conversions.store') }}" method="POST" enctype="multipart/form-data" 
                          class="space-y-6" 
                          x-data="{ 
                              dragOver: false,
                              quality: 80,
                              fileName: '',
                              fileSize: '',
                              isLoading: false,
                              handleFile(event) {
                                  let files;
                                  if (event.dataTransfer) {
                                      files = event.dataTransfer.files;
                                  } else {
                                      files = event.target.files;
                                  }
                                  
                                  if (files.length > 0) {
                                      const file = files[0];
                                      if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                                          alert('Hanya file JPG atau PNG yang diperbolehkan');
                                          return;
                                      }
                                      
                                      this.fileName = file.name;
                                      this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                                      
                                      // Update file input
                                      const fileInput = this.$refs.fileInput;
                                      const dataTransfer = new DataTransfer();
                                      dataTransfer.items.add(file);
                                      fileInput.files = dataTransfer.files;
                                  }
                              }
                          }"
                          @submit="isLoading = true"
                          @dragover.prevent="dragOver = true"
                          @dragleave.prevent="dragOver = false"
                          @drop.prevent="dragOver = false; handleFile($event)">
                        @csrf

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select Image (JPG or PNG, max 100MB)
                            </label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 transition-colors duration-200" 
                                :class="{'border-gray-300 dark:border-gray-600': !dragOver, 'border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20': dragOver}"
                                class="border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="image" 
                                                   name="image" 
                                                   type="file" 
                                                   class="sr-only" 
                                                   accept="image/jpeg,image/png" 
                                                   x-ref="fileInput"
                                                   @change="handleFile($event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        JPG or PNG up to 100MB
                                    </p>
                                </div>
                            </div>
                            
                            <!-- File Info -->
                            <div x-show="fileName" x-cloak class="mt-3">
                                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span x-text="fileName"></span>
                                    <span class="text-gray-400">|</span>
                                    <span x-text="fileSize"></span>
                                </div>
                            </div>

                            @error('image')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                WebP Quality (Optional)
                            </label>
                            <div class="flex items-center space-x-4">
                                <input type="range" name="quality" x-model="quality" min="1" max="100" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer">
                                <span class="text-sm text-gray-600 dark:text-gray-400 w-12" x-text="quality + '%'"></span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Higher quality means larger file size. Default is 80%.
                            </p>
                            @error('quality')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="group relative inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="isLoading || !fileName">
                                <template x-if="!isLoading">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </template>
                                <template x-if="isLoading">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <span x-text="isLoading ? 'Converting...' : 'Convert to WebP'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        
        input[type="range"]::-webkit-slider-thumb {
            @apply w-4 h-4 bg-indigo-600 rounded-full appearance-none cursor-pointer;
        }
        
        input[type="range"]::-moz-range-thumb {
            @apply w-4 h-4 bg-indigo-600 rounded-full appearance-none cursor-pointer border-0;
        }
        
        .dark input[type="range"]::-webkit-slider-thumb {
            @apply bg-indigo-400;
        }
        
        .dark input[type="range"]::-moz-range-thumb {
            @apply bg-indigo-400;
        }
    </style>
</x-app-layout> 