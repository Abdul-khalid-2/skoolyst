<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user()->load('school');
        return view('dashboard.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = auth()->user()->load('school');

        return view('dashboard.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        try {
            // Update profile information
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]);

            // Update profile picture if provided
            if ($request->hasFile('profile_picture')) {
                // Get school folder name
                $folderName = $user->school ? Str::slug($user->school->name, '-') : 'default';

                // Delete old profile picture if exists
                if ($user->profile_picture && Storage::disk('website')->exists($user->profile_picture)) {
                    Storage::disk('website')->delete($user->profile_picture);
                }

                // Store new profile picture in website disk
                $profilePath = Storage::disk('website')
                    ->putFile("school/{$folderName}/profiles", $request->file('profile_picture'));

                $user->update(['profile_picture' => $profilePath]);
            }

            // Remove profile picture if requested
            if ($request->has('remove_profile_picture') && $user->profile_picture) {
                if (Storage::disk('website')->exists($user->profile_picture)) {
                    Storage::disk('website')->delete($user->profile_picture);
                }
                $user->update(['profile_picture' => null]);
            }

            // Update password if provided
            if (!empty($validated['new_password'])) {
                $user->update([
                    'password' => Hash::make($validated['new_password'])
                ]);
            }

            return redirect()->route('user_profile.show')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }
}
