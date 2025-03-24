<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use App\Notifications\DonationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class DonationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $remainingConversions = $user->remainingConversions();
        $isLimited = $user->role === 0;
        $pendingDonations = $user->donations()->where('status', 'pending')->get();
        
        return view('donations.index', compact('user', 'remainingConversions', 'isLimited', 'pendingDonations'));
    }
    
    public function donate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_proof' => 'required|image|max:2048',
        ]);
        
        $user = auth()->user();
        $paymentProofData = null;
        
        if ($request->hasFile('payment_proof')) {
            // Read the file contents as binary data
            $paymentProofData = file_get_contents($request->file('payment_proof')->getPathname());
        }
        
        // Create a donation record with pending status
        $donation = $user->donations()->create([
            'amount' => $request->amount,
            'payment_proof' => $paymentProofData,
            'type' => 'limit_increase',
            'status' => 'pending',
        ]);
        
        Alert::success('Permintaan Terkirim', 'Terima kasih atas donasi Anda. Permintaan Anda sedang menunggu persetujuan admin.')
            ->toast()
            ->position('top-end')
            ->timerProgressBar()
            ->autoClose(5000);
        
        return redirect()->route('donations.index');
    }
    
    public function upgrade(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);
        
        $user = auth()->user();
        $paymentProofData = null;
        
        if ($request->hasFile('payment_proof')) {
            // Read the file contents as binary data
            $paymentProofData = file_get_contents($request->file('payment_proof')->getPathname());
        }
        
        // Create a donation record with pending status
        $donation = $user->donations()->create([
            'amount' => 50000, // Fixed amount for premium upgrade
            'payment_proof' => $paymentProofData,
            'type' => 'premium_upgrade',
            'status' => 'pending',
        ]);
        
        Alert::success('Permintaan Terkirim', 'Permintaan upgrade ke Premium sedang menunggu persetujuan admin.')
            ->toast()
            ->position('top-end')
            ->timerProgressBar()
            ->autoClose(5000);
        
        return redirect()->route('donations.index');
    }
    
    public function history()
    {
        $donations = auth()->user()->donations()->latest()->paginate(10);
        
        return view('donations.history', compact('donations'));
    }
    
    /**
     * Display the payment proof image
     */
    public function showPaymentProof($id)
    {
        $donation = Donation::findOrFail($id);
        
        // Check if the current user is the owner of the donation or an admin
        if (auth()->id() !== $donation->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        // If payment proof exists
        if ($donation->payment_proof) {
            // Get first bytes to detect image type
            $firstBytes = substr($donation->payment_proof, 0, 8);
            $mimeType = 'image/jpeg'; // Default
            
            // Simple image type detection based on binary data
            if (strpos($firstBytes, "\x89\x50\x4E\x47") === 0) {
                $mimeType = 'image/png';
            } elseif (strpos($firstBytes, "\xFF\xD8\xFF") === 0) {
                $mimeType = 'image/jpeg';
            } elseif (strpos($firstBytes, "GIF8") === 0) {
                $mimeType = 'image/gif';
            } elseif (strpos($firstBytes, "RIFF") === 0) {
                $mimeType = 'image/webp';
            }
            
            // Return the image with appropriate content type
            return response($donation->payment_proof)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="payment-proof-'.$id.'.'.$this->getExtensionFromMimeType($mimeType).'"');
        }
        
        abort(404);
    }
    
    /**
     * Get file extension from MIME type
     */
    private function getExtensionFromMimeType($mimeType)
    {
        return [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ][$mimeType] ?? 'jpg';
    }
    
    /**
     * Show payment proof with details
     */
    public function showPaymentProofPage($id)
    {
        $donation = Donation::with('user')->findOrFail($id);
        
        // Check if the current user is the owner of the donation or an admin
        if (auth()->id() !== $donation->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        if (!$donation->payment_proof) {
            abort(404, 'No payment proof available for this donation');
        }
        
        return view('donations.show-payment-proof', compact('donation'));
    }
    
    /**
     * Approve a donation
     */
    public function approve($id)
    {
        // Only admin users can approve donations
        if (Auth::user()->role !== 2) { // Cek langsung role sebagai integer 2
            return redirect()->back()->with('error', 'You do not have permission to approve donations.');
        }

        $donation = Donation::findOrFail($id);

        // Don't allow approving already-approved or rejected donations
        if ($donation->status !== 'pending') {
            return redirect()->back()->with('error', 'This donation has already been processed.');
        }

        // Update the donation status
        $donation->status = 'approved';
        $donation->save();

        // Update user based on donation type
        $user = $donation->user;

        if ($donation->type === 'limit_increase') {
            // Add conversion limits
            $user->addConversionLimit($user->getLimitIncreaseAmount());
            $successMessage = 'Donation approved! The user has received additional conversion limits.';
        } else {
            // Upgrade to premium
            $user->role = 1; // Set to premium (integer)
            $user->save();
            $successMessage = 'Donation approved! The user has been upgraded to premium.';
        }
        
        // Send notification to user
        $user->notify(new DonationStatusChanged($donation));

        return redirect()->back()->with('success', $successMessage . ' A notification email has been sent to the user.');
    }
    
    /**
     * Reject a donation
     */
    public function reject(Request $request, $id)
    {
        // Only admin users can reject donations
        if (Auth::user()->role !== 2) { // Cek langsung role sebagai integer 2
            return redirect()->back()->with('error', 'You do not have permission to reject donations.');
        }

        $donation = Donation::findOrFail($id);

        // Don't allow rejecting already-approved or rejected donations
        if ($donation->status !== 'pending') {
            return redirect()->back()->with('error', 'This donation has already been processed.');
        }

        // Validate admin notes
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        // Update the donation
        $donation->status = 'rejected';
        $donation->admin_notes = $request->admin_notes;
        $donation->save();
        
        // Send notification to user
        $donation->user->notify(new DonationStatusChanged($donation));

        return redirect()->back()->with('success', 'Donation has been rejected. A notification email has been sent to the user.');
    }
    
    // Admin actions are handled in Filament admin panel
}
