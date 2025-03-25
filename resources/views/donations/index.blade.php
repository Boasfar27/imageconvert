<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Donations & Premium') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900 dark:text-gray-100">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-2">Your Account Status</h3>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg flex-1">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Account Type</h4>
                                @if ($user->role === 0)
                                    <div class="text-lg font-bold text-gray-600 dark:text-gray-400 flex items-center">
                                        <span
                                            class="inline-flex items-center justify-center bg-gray-200 dark:bg-gray-600 rounded-md px-2 py-1 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </span>
                                        Regular (Limited)
                                    </div>
                                @elseif($user->role === 1)
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400 flex items-center">
                                        <span
                                            class="inline-flex items-center justify-center bg-green-200 dark:bg-green-600 rounded-md px-2 py-1 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-green-500 dark:text-green-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                        Premium (Unlimited)
                                    </div>
                                @else
                                    <div class="text-lg font-bold text-red-600 dark:text-red-400 flex items-center">
                                        <span
                                            class="inline-flex items-center justify-center bg-red-200 dark:bg-red-600 rounded-md px-2 py-1 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-red-500 dark:text-red-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </span>
                                        Admin
                                    </div>
                                @endif
                            </div>

                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg flex-1">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Conversions</h4>
                                @if ($user->role >= 1)
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400 flex items-center">
                                        <span
                                            class="inline-flex items-center justify-center bg-green-200 dark:bg-green-600 rounded-md px-2 py-1 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-green-500 dark:text-green-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m-8 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </span>
                                        Unlimited
                                    </div>
                                @else
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400 flex items-center">
                                        <span
                                            class="inline-flex items-center justify-center bg-blue-200 dark:bg-blue-600 rounded-md px-2 py-1 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-blue-500 dark:text-blue-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                        {{ $remainingConversions }} remaining
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (count($pendingDonations) > 0)
                        <div class="mb-8">
                            <div
                                class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-300 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">
                                            You have {{ count($pendingDonations) }} requests in process
                                            approval.
                                        </p>
                                        <p class="text-xs mt-1">
                                            Please wait for admin approval. See donation history at
                                            <a href="{{ route('donations.history') }}"
                                                class="font-medium underline">donation history</a>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($isLimited)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                            <div
                                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="p-6">
                                    <div class="text-center mb-4">
                                        <h3 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Add
                                            Conversions</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2">Donate any amount to get 50
                                            more conversions</p>
                                    </div>

                                    <form method="POST" action="{{ route('donations.donate') }}" class="mt-6"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="amount"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Donation Amount (min Rp 1.000)
                                            </label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="amount" id="amount" value="1000"
                                                    min="1000"
                                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md py-2"
                                                    placeholder="0">
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label for="payment_proof"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Payment Proof (Screenshot)
                                            </label>
                                            <input type="file" name="payment_proof" id="payment_proof"
                                                accept="image/*" required
                                                class="block w-full text-sm text-gray-900 dark:text-gray-300 
                                                file:mr-4 file:py-2 file:px-4 file:rounded-md
                                                file:border-0 file:text-sm file:font-medium
                                                file:bg-indigo-50 file:text-indigo-700
                                                dark:file:bg-indigo-900 dark:file:text-indigo-300
                                                hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800
                                                border border-gray-300 dark:border-gray-700 rounded-md
                                                dark:bg-gray-900">
                                        </div>

                                        <div class="text-center">
                                            <button type="submit"
                                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                Donate & Get 50 Conversions
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div
                                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="p-6">
                                    <div class="text-center mb-4">
                                        <h3 class="text-xl font-bold text-green-600 dark:text-green-400">Upgrade to
                                            Premium</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2">Get unlimited conversions with
                                            premium account</p>
                                    </div>

                                    <div class="py-4">
                                        <ul class="space-y-3">
                                            <li class="flex items-center">
                                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">Unlimited image
                                                    conversions</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">Priority
                                                    support</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">No conversion
                                                    limits</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <form method="POST" action="{{ route('donations.upgrade') }}" class="mt-6"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-6">
                                            <label for="payment_proof_upgrade"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Payment Proof (Screenshot)
                                            </label>
                                            <input type="file" name="payment_proof" id="payment_proof_upgrade"
                                                accept="image/*" required
                                                class="block w-full text-sm text-gray-900 dark:text-gray-300 
                                                file:mr-4 file:py-2 file:px-4 file:rounded-md
                                                file:border-0 file:text-sm file:font-medium
                                                file:bg-green-50 file:text-green-700
                                                dark:file:bg-green-900 dark:file:text-green-300
                                                hover:file:bg-green-100 dark:hover:file:bg-green-800
                                                border border-gray-300 dark:border-gray-700 rounded-md
                                                dark:bg-gray-900">
                                        </div>

                                        <div class="text-center">
                                            <button type="submit"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                Upgrade to Premium
                                            </button>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">One-time payment
                                                of Rp 50.000</p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- QRIS Payment Instructions -->
                        <div
                            class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Payment
                                    Instructions with QRIS</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <ol
                                            class="list-decimal list-inside space-y-2 text-gray-700 dark:text-gray-300">
                                            <li>Open e-wallet or m-banking that supports QRIS (GoPay, OVO,
                                                DANA, LinkAja, etc)</li>
                                            <li>Select the scan QR code or pay with QRIS</li>
                                            <li>Scan QRIS code above</li>
                                            <li>Enter the amount according to the donation you want</li>
                                            <li>Complete the payment on the application</li>
                                            <li>Screenshot payment proof</li>
                                            <li>Upload payment proof on the form above</li>
                                            <li>Admin will verify your payment within 1x24 hours</li>
                                        </ol>

                                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900 rounded-md">
                                            <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">
                                                If payment is not verified within 24 hours, please contact admin
                                                at
                                                <a href="mailto:muhammadfarhan2747@gmail.com"
                                                    class="underline">muhammadfarhan2747@gmail.com</a>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-center">
                                        <div class="p-2 bg-white rounded-lg">
                                            <img src="{{ asset('images/qris.webp') }}" alt="QRIS Payment Code"
                                                class="max-w-full h-auto cursor-pointer" style="max-height: 300px;"
                                                id="qrisImage" onclick="openQrisModal()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="bg-green-50 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mt-8 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">
                                        <span class="font-medium">You have
                                            {{ $user->role === 1 ? 'Premium' : 'Admin' }} account!</span> You have
                                        unlimited conversions available.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('donations.history') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                View Donation History
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- QRIS Modal -->
<div id="qrisModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center hidden"
    onclick="closeQrisModal()">
    <div class="relative max-w-4xl mx-auto">
        <button type="button" class="absolute top-4 right-4 text-white hover:text-gray-300 focus:outline-none"
            onclick="event.stopPropagation(); closeQrisModal()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>
        <img src="{{ asset('images/qris.webp') }}" alt="QRIS Payment Code Full Screen"
            class="max-h-[90vh] max-w-[90vw]">
    </div>
</div>

<script>
    function openQrisModal() {
        document.getElementById('qrisModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }

    function closeQrisModal() {
        document.getElementById('qrisModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeQrisModal();
        }
    });
</script>
