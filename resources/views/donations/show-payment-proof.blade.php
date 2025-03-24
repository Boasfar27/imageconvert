<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Proof - Donation #{{ $donation->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000;
            color: #ffffff;
        }

        .card {
            background-color: #111111;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid #333333;
        }

        .card-header {
            padding: 1.25rem;
            border-bottom: 1px solid #333333;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid #34d399;
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid #fbbf24;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid #f87171;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 100;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .modal-content {
            background-color: #111111;
            border-radius: 0.75rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid #333333;
        }

        .image-viewer {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            background-color: #000000;
            border: 1px solid #333333;
        }

        .image-viewer img {
            width: 100%;
            height: auto;
            max-height: 70vh;
            object-fit: contain;
            display: block;
        }

        .zoom-controls {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .zoom-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background-color: rgba(17, 17, 17, 0.8);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            border: 1px solid #333333;
        }

        .zoom-btn:hover {
            background-color: rgba(31, 41, 55, 0.9);
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981;
            color: #d1fae5;
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            color: #fee2e2;
        }

        .text-label {
            color: #9ca3af;
        }

        .text-value {
            color: #ffffff;
        }

        .nav-bg {
            background-color: #111111;
            border-bottom: 1px solid #333333;
        }

        .notes-bg {
            background-color: #1a1a1a;
            border: 1px solid #333333;
        }

        @media (max-width: 640px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="nav-bg shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2 font-medium text-xl text-white">ImageConverter</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url()->current() }}" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Refresh</span>
                    </a>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back</span>
                    </a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ url('/admin/donations/') }}" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Payment Proof Details
        </h1>

        @if (session('success'))
            <div class="alert-success p-4 mb-6 rounded-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error p-4 mb-6 rounded-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Left column - Details -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-white">Donation Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-label">Donation ID</h3>
                                <p class="mt-1 text-base font-semibold text-value">#{{ $donation->id }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-label">Status</h3>
                                <p class="mt-1">
                                    @if ($donation->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($donation->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-label">User</h3>
                                <p class="mt-1 text-base font-semibold text-value">{{ $donation->user->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-label">Date</h3>
                                <p class="mt-1 text-base font-semibold text-value">
                                    {{ $donation->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-label">Amount</h3>
                                <p class="mt-1 text-base font-semibold text-value">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-label">Type</h3>
                                <p class="mt-1 text-base font-semibold text-value">
                                    {{ $donation->type === 'limit_increase' ? 'Limit Increase' : 'Premium Upgrade' }}
                                </p>
                            </div>
                        </div>

                        @if ($donation->admin_notes)
                            <div class="mt-6 p-4 notes-bg rounded-md">
                                <h3 class="text-sm font-medium text-label">Admin Notes</h3>
                                <p class="mt-1 text-sm text-value">{{ $donation->admin_notes }}</p>
                            </div>
                        @endif

                        @if (auth()->user()->isAdmin() && $donation->status === 'pending')
                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <form action="{{ route('donations.approve', $donation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full btn btn-success">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Approve</span>
                                    </button>
                                </form>

                                <button type="button" class="w-full btn btn-danger" onclick="openRejectModal()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Reject</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right column - Image -->
            <div class="lg:col-span-3">
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-white">Payment Proof</h2>
                        </div>
                        <button type="button" class="text-indigo-400 hover:text-indigo-300"
                            onclick="openFullScreenImage()">
                            <span class="text-sm font-medium">View Fullscreen</span>
                            <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                            </svg>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="image-viewer" id="imageViewer">
                            <img id="proofImage" src="{{ route('donations.payment-proof', $donation->id) }}"
                                alt="Payment Proof" style="transform-origin: center; transform: scale(1);">
                            <div class="zoom-controls">
                                <button class="zoom-btn" onclick="zoomIn()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                                <button class="zoom-btn" onclick="zoomOut()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                </button>
                                <button class="zoom-btn" onclick="resetZoom()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Modal -->
    <div id="fullscreenModal" class="modal" onclick="closeFullScreenImage()">
        <div class="relative max-w-5xl mx-auto">
            <button type="button"
                class="absolute top-4 right-4 bg-black bg-opacity-75 rounded-full p-2 text-white hover:text-gray-300 focus:outline-none"
                onclick="event.stopPropagation(); closeFullScreenImage()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img src="{{ route('donations.payment-proof', $donation->id) }}" alt="Payment Proof Full Screen"
                class="max-h-[90vh] max-w-[90vw]">
        </div>
    </div>

    <!-- Reject Modal -->
    @if (auth()->user()->isAdmin() && $donation->status === 'pending')
        <div id="rejectModal" class="modal">
            <div class="modal-content">
                <div class="p-6 border-b border-gray-800">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white">Reject Donation</h2>
                        <button type="button" class="text-gray-400 hover:text-gray-300"
                            onclick="closeRejectModal()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <form action="{{ route('donations.reject', $donation->id) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="admin_notes" class="block text-sm font-medium text-gray-300 mb-2">
                                Rejection Reason:
                            </label>
                            <textarea id="admin_notes" name="admin_notes" rows="4"
                                class="w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-900 text-white"
                                required></textarea>
                            <p class="mt-1 text-sm text-gray-400">
                                This reason will be sent to the user via email.
                            </p>
                        </div>
                    </div>
                    <div class="p-6 border-t border-gray-800 flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 border border-gray-700 rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="closeRejectModal()">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reject Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        // Zoom functionality
        let currentZoom = 1;
        const zoomFactor = 0.1;
        const maxZoom = 3;
        const minZoom = 0.5;
        const image = document.getElementById('proofImage');

        function zoomIn() {
            if (currentZoom < maxZoom) {
                currentZoom += zoomFactor;
                updateZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > minZoom) {
                currentZoom -= zoomFactor;
                updateZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            updateZoom();
        }

        function updateZoom() {
            image.style.transform = `scale(${currentZoom})`;
        }

        // Fullscreen modal
        function openFullScreenImage() {
            document.getElementById('fullscreenModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeFullScreenImage() {
            document.getElementById('fullscreenModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Reject modal
        function openRejectModal() {
            document.getElementById('rejectModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modals with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFullScreenImage();
                closeRejectModal();
            }
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const fullscreenModal = document.getElementById('fullscreenModal');
            const rejectModal = document.getElementById('rejectModal');

            if (event.target === fullscreenModal) {
                closeFullScreenImage();
            }

            if (rejectModal && event.target === rejectModal) {
                closeRejectModal();
            }
        }
    </script>
</body>

</html>
