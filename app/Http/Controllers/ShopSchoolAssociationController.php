<?php

namespace App\Http\Controllers;

use App\Models\ShopSchoolAssociation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ShopSchoolAssociationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ShopSchoolAssociation::class, 'shop-school-association');
    }

    public function index()
    {
        $query = ShopSchoolAssociation::with(['shop', 'school', 'createdBy', 'approvedBy']);

        if (Auth::user()->hasRole('shop_owner')) {
            $shopIds = Auth::user()->shops->pluck('id');
            $query->whereIn('shop_id', $shopIds);
        } elseif (Auth::user()->hasRole('school_admin')) {
            $schoolIds = Auth::user()->schools->pluck('id');
            $query->whereIn('school_id', $schoolIds);
        }

        $associations = $query->paginate(15);

        return view('shop-school-associations.index', compact('associations'));
    }

    public function show(ShopSchoolAssociation $shopSchoolAssociation)
    {
        $shopSchoolAssociation->load(['shop', 'school', 'createdBy', 'approvedBy', 'products']);
        return view('shop-school-associations.show', compact('shopSchoolAssociation'));
    }

    public function edit(ShopSchoolAssociation $shopSchoolAssociation)
    {
        return view('shop-school-associations.edit', compact('shopSchoolAssociation'));
    }

    public function update(Request $request, ShopSchoolAssociation $shopSchoolAssociation)
    {
        $validated = $request->validate([
            'association_type' => 'sometimes|in:preferred,official,affiliated,general',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'can_add_products' => 'boolean',
            'can_manage_products' => 'boolean',
            'can_view_analytics' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $shopSchoolAssociation->update($validated);

        return redirect()->route('shop-school-associations.show', $shopSchoolAssociation)->with('success', 'Association updated successfully.');
    }

    public function destroy(ShopSchoolAssociation $shopSchoolAssociation)
    {
        $shopSchoolAssociation->delete();
        return redirect()->route('shop-school-associations.index')->with('success', 'Association deleted successfully.');
    }

    public function approve(ShopSchoolAssociation $shopSchoolAssociation)
    {
        Gate::authorize('approve', $shopSchoolAssociation);

        $shopSchoolAssociation->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'is_active' => true
        ]);

        return back()->with('success', 'Association approved successfully.');
    }

    public function reject(ShopSchoolAssociation $shopSchoolAssociation)
    {
        Gate::authorize('approve', $shopSchoolAssociation);

        $shopSchoolAssociation->update([
            'status' => 'rejected',
            'is_active' => false
        ]);

        return back()->with('success', 'Association rejected successfully.');
    }
}
