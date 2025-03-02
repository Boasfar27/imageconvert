@php
    use App\Helpers\DateHelper;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Statistik Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Total Konversi</h3>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalConversions }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Total Penyimpanan Dihemat</h3>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ DateHelper::formatSize($totalSizeReduction) }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Konversi Hari Ini</h3>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-blue-400">{{ $todayConversions }}</p>
                    </div>
                </div>
            </div>

            {{-- Panduan Cepat --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Panduan Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Konversi Gambar</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Klik "Image Converter" di menu untuk mulai mengkonversi gambar ke format WebP.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Hemat Penyimpanan</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Format WebP menghemat hingga 30% ukuran file tanpa mengurangi kualitas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Konversi Terakhir --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Konversi Terakhir</h3>
                        <a href="{{ route('conversions.create') }}" class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-blue-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 ease-in-out transform hover:scale-105">
                            <span>Konversi Baru</span>
                            <svg class="w-4 h-4 ml-2 transform transition-transform duration-300 ease-in-out group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
                    @if($conversions->count() > 0)
                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="group px-6 py-3 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-xs font-medium tracking-wider text-gray-500 dark:text-gray-400 uppercase">Original Image</span>
                                        </div>
                                    </th>
                                    <th class="group px-6 py-3 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                            </svg>
                                            <span class="text-xs font-medium tracking-wider text-gray-500 dark:text-gray-400 uppercase">Format</span>
                                        </div>
                                    </th>
                                    <th class="group px-6 py-3 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                            </svg>
                                            <span class="text-xs font-medium tracking-wider text-gray-500 dark:text-gray-400 uppercase">Size</span>
                                        </div>
                                    </th>
                                    <th class="group px-6 py-3 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                            </svg>
                                            <span class="text-xs font-medium tracking-wider text-gray-500 dark:text-gray-400 uppercase">Reduction</span>
                                        </div>
                                    </th>
                                    <th class="group px-6 py-3 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-xs font-medium tracking-wider text-gray-500 dark:text-gray-400 uppercase">Date</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center gap-x-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            <span class="text-xs font-medium tracking-wider text-gray-500 dark:text-gray-400 uppercase">Actions</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($conversions as $conversion)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-900">
                                                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $conversion->original_name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-white dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $conversion->original_format }}
                                                </span>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-white dark:bg-green-900 dark:text-green-200">
                                                    {{ $conversion->converted_format }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span>{{ DateHelper::formatSize($conversion->original_size) }}</span>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                                <span>{{ DateHelper::formatSize($conversion->converted_size) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $reduction = (($conversion->original_size - $conversion->converted_size) / $conversion->original_size) * 100;
                                                $savedSize = $conversion->original_size - $conversion->converted_size;
                                            @endphp
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                                </svg>
                                                <div>
                                                    <span class="text-green-600 dark:text-green-400 font-medium">
                                                        {{ number_format($reduction, 1) }}% lebih kecil
                                                    </span>
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm block">
                                                        {{ DateHelper::formatSize($savedSize) }} tersimpan
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span>{{ DateHelper::formatIndonesian($conversion->created_at) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center space-x-3">
                                                <a href="javascript:void(0)" 
                                                   onclick="handleDownload('{{ route('conversions.download', $conversion->id) }}')"
                                                   class="inline-flex items-center p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/50 transition-colors duration-200"
                                                   title="Download">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('conversions.destroy', $conversion->id) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      id="delete-form-{{ $conversion->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="confirmDelete('{{ $conversion->id }}', '{{ $conversion->original_name }}')"
                                                            class="inline-flex items-center p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors duration-200"
                                                            title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="max-w-md mx-auto">
                                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                                                    <div class="flex flex-col items-center">
                                                        <div class="relative">
                                                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full blur opacity-25"></div>
                                                            <div class="relative bg-white dark:bg-gray-800 rounded-full p-4">
                                                                <svg class="w-16 h-16 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-gray-100">Belum Ada Konversi</h3>
                                                        <p class="mt-3 text-gray-500 dark:text-gray-400 text-center">
                                                            Mulai mengoptimalkan gambar Anda dengan mengkonversi ke format WebP untuk performa web yang lebih baik.
                                                        </p>
                                                        <div class="mt-8">
                                                            <a href="{{ route('conversions.create') }}" 
                                                               class="group relative inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-500 hover:to-blue-400 border border-transparent rounded-xl text-base font-medium text-white shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                                                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-600 to-blue-500 opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                </svg>
                                                                Mulai Konversi
                                                            </a>
                                                        </div>
                                                        <div class="mt-6 grid grid-cols-2 gap-4 text-center text-sm">
                                                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                                <p class="font-semibold text-gray-900 dark:text-gray-100">Format WebP</p>
                                                                <p class="mt-1 text-gray-500 dark:text-gray-400">Kompresi modern</p>
                                                            </div>
                                                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                                <p class="font-semibold text-gray-900 dark:text-gray-100">Hemat Ruang</p>
                                                                <p class="mt-1 text-gray-500 dark:text-gray-400">Hingga 30%</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="mt-6 px-6">
                            <div class="flex items-center justify-between">
                                {{ $conversions->links() }}
                            </div>
                        </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="max-w-md mx-auto">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full blur opacity-25"></div>
                                            <div class="relative bg-white dark:bg-gray-800 rounded-full p-4">
                                                <svg class="w-16 h-16 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-gray-100">Belum Ada Konversi</h3>
                                        <p class="mt-3 text-gray-500 dark:text-gray-400 text-center">
                                            Mulai mengoptimalkan gambar Anda dengan mengkonversi ke format WebP untuk performa web yang lebih baik.
                                        </p>
                                        <div class="mt-8">
                                            <a href="{{ route('conversions.create') }}" 
                                               class="group relative inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-500 hover:to-blue-400 border border-transparent rounded-xl text-base font-medium text-white shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-600 to-blue-500 opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Mulai Konversi
                                            </a>
                                        </div>
                                        <div class="mt-6 grid grid-cols-2 gap-4 text-center text-sm">
                                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">Format WebP</p>
                                                <p class="mt-1 text-gray-500 dark:text-gray-400">Kompresi modern</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">Hemat Ruang</p>
                                                <p class="mt-1 text-gray-500 dark:text-gray-400">Hingga 30%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .group:hover svg {
            animation: none;
        }
    </style>

    <script>
        function confirmDelete(id, filename) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus gambar "${filename}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        function handleDownload(url) {
            Swal.fire({
                title: 'Memulai Download',
                text: 'File akan segera diunduh',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = url;
                }
            });
        }
    </script>
</x-app-layout>
