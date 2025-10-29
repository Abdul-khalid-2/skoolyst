<?php
// app/Http/Controllers/ContactInquiryController.php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\School;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    /**
     * Store a newly created contact inquiry.
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to submit an inquiry.',
                'login_required' => true
            ], 401);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|in:admission,tour,general,feedback,other',
            'custom_subject' => 'required_if:subject,other|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            // Sanitize and secure the data
            $sanitizedData = $this->sanitizeInquiryData($validated);

            // Add user_id to the data
            $sanitizedData['user_id'] = auth()->id();

            $inquiry = ContactInquiry::create($sanitizedData);

            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been submitted successfully. We will get back to you soon.',
                'inquiry_id' => $inquiry->uuid,
            ]);
        } catch (\Exception $e) {
            \Log::error('Contact inquiry submission failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error submitting your inquiry. Please try again.',
            ], 500);
        }
    }

    /**
     * Sanitize inquiry data for security
     */
    private function sanitizeInquiryData(array $data): array
    {
        return [
            'school_id' => (int) $data['school_id'],
            'branch_id' => isset($data['branch_id']) ? (int) $data['branch_id'] : null,
            'name' => $this->cleanInput($data['name']),
            'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            'phone' => isset($data['phone']) ? $this->cleanInput($data['phone']) : null,
            'subject' => $data['subject'],
            'custom_subject' => isset($data['custom_subject']) ? $this->cleanInput($data['custom_subject']) : null,
            'message' => $this->cleanInput($data['message']),
        ];
    }

    /**
     * Clean and sanitize input string
     */
    private function cleanInput(string $input): string
    {
        // Remove potentially harmful characters
        $cleaned = strip_tags($input);
        $cleaned = htmlspecialchars($cleaned, ENT_QUOTES, 'UTF-8');

        // Remove excessive whitespace
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));

        return $cleaned;
    }

    /**
     * Display inquiries for school admin.
     */
    public function index(Request $request)
    {
        // Remove the forSchool scope call since it doesn't exist yet
        $query = ContactInquiry::with(['school', 'branch', 'assignedAdmin'])
            ->where('school_id', auth()->user()->school_id) // Direct where clause
            ->latest();

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['new', 'in_progress', 'resolved', 'closed'])) {
            $query->where('status', $request->status);
        }

        // Filter by subject
        if ($request->has('subject') && $request->subject) {
            $query->where('subject', $request->subject);
        }

        $inquiries = $query->paginate(20);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Show specific inquiry for admin.
     */
    public function show(ContactInquiry $inquiry)
    {
        // Add basic authorization check
        if ($inquiry->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->load(['school', 'branch', 'assignedAdmin']);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update inquiry status (admin only).
     */
    public function updateStatus(Request $request, ContactInquiry $inquiry)
    {
        // Add basic authorization check
        if ($inquiry->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $inquiry->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry status updated successfully.',
        ]);
    }

    /**
     * Assign inquiry to admin user.
     */
    public function assign(Request $request, ContactInquiry $inquiry)
    {
        // Add basic authorization check
        if ($inquiry->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $inquiry->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry assigned successfully.',
        ]);
    }

    /**
     * Get inquiry statistics for dashboard.
     */
    public function getStats()
    {
        $schoolId = auth()->user()->school_id;

        $stats = ContactInquiry::where('school_id', $schoolId) // Direct where clause
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $total = array_sum($stats);
        $newCount = $stats['new'] ?? 0;

        return response()->json([
            'total' => $total,
            'new' => $newCount,
            'stats' => $stats,
        ]);
    }
}
