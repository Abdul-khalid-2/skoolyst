<?php

namespace App\Http\Controllers;

use App\Enums\ModerationStatus;
use App\Models\School;
use App\Models\ShopSchoolAssociation;
use Illuminate\Http\Request;

class SchoolShopAssociationController extends Controller
{
    /**
     * Resolve the school owned by the currently logged-in school admin.
     * The link is on users.school_id.
     */
    private function getSchoolForAdmin(): School
    {
        $schoolId = auth()->user()->school_id;
        abort_if(!$schoolId, 403, 'No school assigned to this admin.');

        return School::findOrFail($schoolId);
    }

    public function index(Request $request)
    {
        $school = $this->getSchoolForAdmin();

        $associations = ShopSchoolAssociation::with(['shop', 'createdBy'])
            ->where('school_id', $school->id)
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $pendingCount  = ShopSchoolAssociation::where('school_id', $school->id)->where('status', ModerationStatus::Pending)->count();
        $approvedCount = ShopSchoolAssociation::where('school_id', $school->id)->where('status', ModerationStatus::Approved)->count();
        $rejectedCount = ShopSchoolAssociation::where('school_id', $school->id)->where('status', ModerationStatus::Rejected)->count();

        return view('dashboard.schools.shop-associations', compact(
            'school', 'associations', 'pendingCount', 'approvedCount', 'rejectedCount'
        ));
    }

    public function approve(ShopSchoolAssociation $association)
    {
        $school = $this->getSchoolForAdmin();
        abort_if($association->school_id !== $school->id, 403);

        $association->update([
            'status'      => ModerationStatus::Approved,
            'is_active'   => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Shop association approved successfully.');
    }

    public function reject(ShopSchoolAssociation $association)
    {
        $school = $this->getSchoolForAdmin();
        abort_if($association->school_id !== $school->id, 403);

        $association->update([
            'status'    => ModerationStatus::Rejected,
            'is_active' => false,
        ]);

        return back()->with('success', 'Shop association rejected.');
    }
}
