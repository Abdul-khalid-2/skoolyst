<?php
// app/Http/Controllers/ContactInquiryController.php

namespace App\Http\Controllers;

use App\Http\Requests\AssignContactInquiryRequest;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Http\Requests\UpdateContactInquiryStatusRequest;
use App\Enums\ContactInquiryStatus;
use App\Mail\SchoolContactInquiryMail;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactInquiryController extends Controller
{
    /**
     * Store a newly created contact inquiry.
     */
    public function store(StoreContactInquiryRequest $request)
    {
        try {
            $sanitizedData = $this->sanitizeInquiryData($request->validated());

            // Add user_id to the data
            $sanitizedData['user_id'] = auth()->id();

            $inquiry = ContactInquiry::create($sanitizedData);

            $this->sendInquiryEmails($inquiry);

            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been submitted successfully. We will get back to you soon.',
                'inquiry_id' => $inquiry->uuid,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error submitting your inquiry. Please try again.',
            ], 500);
        }
    }

    /**
     * Notify the school about a new inquiry and send a confirmation to the visitor.
     * Mail failures are logged but do not break inquiry submission.
     */
    private function sendInquiryEmails(ContactInquiry $inquiry): void
    {
        $inquiry->loadMissing(['school', 'branch']);

        $schoolEmail = $inquiry->school->email ?? null;

        if ($schoolEmail && filter_var($schoolEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($schoolEmail)
                    ->send(new SchoolContactInquiryMail($inquiry, 'school'));
            } catch (\Throwable $e) {
                Log::warning('Failed to email school contact inquiry to school', [
                    'inquiry_id' => $inquiry->id,
                    'school_email' => $schoolEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::info('School inquiry saved but school has no valid email', [
                'inquiry_id' => $inquiry->id,
                'school_id' => $inquiry->school_id,
            ]);
        }

        if ($inquiry->email && filter_var($inquiry->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($inquiry->email)
                    ->send(new SchoolContactInquiryMail($inquiry, 'user'));
            } catch (\Throwable $e) {
                Log::warning('Failed to email inquiry confirmation to user', [
                    'inquiry_id' => $inquiry->id,
                    'user_email' => $inquiry->email,
                    'error' => $e->getMessage(),
                ]);
            }
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
        $schoolId = auth()->user()->school_id;

        $query = ContactInquiry::with(['school', 'branch', 'assignedAdmin', 'user'])
            ->forSchool($schoolId)
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $inquiries = $query->paginate(20);

        // Get statistics
        $totalInquiries = ContactInquiry::forSchool($schoolId)->count();
        $newInquiries = ContactInquiry::forSchool($schoolId)->where('status', ContactInquiryStatus::Inbox)->count();
        $inProgressInquiries = ContactInquiry::forSchool($schoolId)->where('status', ContactInquiryStatus::InProgress)->count();
        $resolvedInquiries = ContactInquiry::forSchool($schoolId)->where('status', ContactInquiryStatus::Resolved)->count();

        return view('dashboard.inquiries_contacts.index', compact(
            'inquiries',
            'totalInquiries',
            'newInquiries',
            'inProgressInquiries',
            'resolvedInquiries'
        ));
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

        return view('dashboard.inquiries_contacts.show', compact('inquiry'));
    }

    /**
     * Update inquiry status (admin only).
     */
    public function updateStatus(UpdateContactInquiryStatusRequest $request, ContactInquiry $inquiry)
    {
        // Add basic authorization check
        if ($inquiry->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->update($request->validated());

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    /**
     * Assign inquiry to admin user.
     */
    public function assign(AssignContactInquiryRequest $request, ContactInquiry $inquiry)
    {
        // Add basic authorization check
        if ($inquiry->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->update([
            'assigned_to' => $request->validated('assigned_to'),
            'status' => ContactInquiryStatus::InProgress,
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



    public function getNotificationCount()
    {
        $schoolId = auth()->user()->school_id;

        $newCount = ContactInquiry::forSchool($schoolId)
            ->where('status', ContactInquiryStatus::Inbox)
            ->count();

        return response()->json([
            'count' => $newCount,
        ]);
    }

    /**
     * Mark inquiry as read
     */
    public function markAsRead(ContactInquiry $inquiry)
    {
        // Authorization check
        if ($inquiry->school_id !== auth()->user()->school_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $inquiry->update([
            'status' => ContactInquiryStatus::InProgress,
        ]);

        return redirect()->back()->with('success', 'Inquiry marked as read successfully.');
    }

    public function create()
    {
        return view('website.contact');
    }
}
