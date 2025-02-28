<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Image Conversions') }}
            </h2>
            <div class="text-sm text-gray-600">
                Total Saved: {{ number_format($totalSaved / 1024 / 1024, 2) }} MB
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Your Conversions</h3>
                        <a href="{{ route('conversions.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                            Convert New Image
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reduction</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($conversions as $conversion)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $conversion->original_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $conversion->original_format }}
                                            </span>
                                            →
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $conversion->converted_format }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ number_format($conversion->original_size / 1024, 1) }} KB →
                                            {{ number_format($conversion->converted_size / 1024, 1) }} KB
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $reduction = (($conversion->original_size - $conversion->converted_size) / $conversion->original_size) * 100;
                                                $savedKB = ($conversion->original_size - $conversion->converted_size) / 1024;
                                            @endphp
                                            <span class="text-green-600 font-medium">
                                                {{ number_format($reduction, 1) }}% smaller
                                            </span>
                                            <span class="text-gray-500 text-sm">
                                                ({{ number_format($savedKB, 1) }} KB saved)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $conversion->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                            <a href="{{ route('conversions.download', $conversion->id) }}" 
                                               class="text-primary-600 hover:text-primary-900">
                                                Download
                                            </a>
                                            <form action="{{ route('conversions.destroy', $conversion->id) }}" 
                                                  method="POST" 
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this conversion?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No conversions yet. Start by converting your first image!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $conversions->links() }}
                    </div>
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