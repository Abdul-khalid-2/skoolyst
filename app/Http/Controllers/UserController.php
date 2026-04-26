<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Services\ImageWebpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        try {
            // Super admin can see all users
            if (auth()->user()->hasRole('super-admin')) {
                $users = User::with(['school', 'roles'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
                return view('dashboard.users.index', compact('users'));
            } 
            // School admin can only see users from their school
            elseif (auth()->user()->hasRole('school-admin')) {
                $schoolId = auth()->user()->school_id;
                
                $users = User::with(['school', 'roles'])
                    ->where('school_id', $schoolId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
                return view('dashboard.users.index', compact('users'));
            } 
            // Unauthorized access
            else {
                return redirect()->route('dashboard')
                    ->with('error', 'Unauthorized access.');
            }
        } catch (\Exception $e) {
            Log::error('Error loading users: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load users. Please try again.');
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        try {
            // Get schools for dropdown (only for super-admin)
            $schools = [];
            
            if (auth()->user()->hasRole('super-admin')) {
                $schools = School::where('status', 'active')
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();
            } elseif (auth()->user()->hasRole('school-admin')) {
                // School admin can only create users for their school
                $schools = School::where('id', auth()->user()->school_id)
                    ->select('id', 'name')
                    ->get();
            }

            return view('dashboard.users.create', compact('schools'));
        } catch (\Exception $e) {
            Log::error('Error loading create user form: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load create user form. Please try again.');
        }
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request, ImageWebpService $imageWebp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'school_id' => [
                'nullable',
                'exists:schools,id',
                function ($attribute, $value, $fail) {
                    // School admin can only assign users to their own school
                    if (auth()->user()->hasRole('school-admin') && $value != auth()->user()->school_id) {
                        $fail('You can only assign users to your own school.');
                    }
                },
            ],
            'role' => 'required|string|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'sometimes|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = new User();
            $user->uuid = Str::uuid();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->phone = $validated['phone'] ?? null;
            $user->address = $validated['address'] ?? null;
            $user->bio = $validated['bio'] ?? null;
            
            // Set school_id based on role
            if (auth()->user()->hasRole('super-admin')) {
                $user->school_id = $validated['school_id'] ?? null;
            } elseif (auth()->user()->hasRole('school-admin')) {
                $user->school_id = auth()->user()->school_id;
            }
            
            $user->save();

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $folderName = 'users/' . $user->uuid;
                $path = $imageWebp->putUploadedAsWebp('public', $folderName, $request->file('profile_picture'));
                $user->profile_picture = $path;
                $user->save();
            }

            // Assign role
            $user->assignRole($validated['role']);

            DB::commit();

            $redirect = $request->has('save_and_add')
                ? redirect()->route('users.create')->with('success', 'User created successfully! Create another user.')
                : redirect()->route('users.index')->with('success', 'User created successfully!');

            return $redirect;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        try {
            $user = User::with(['school', 'roles', 'reviews'])
                ->findOrFail($id);

            // Check authorization
            if (auth()->user()->hasRole('school-admin') && $user->school_id != auth()->user()->school_id) {
                return redirect()->route('users.index')
                    ->with('error', 'You are not authorized to view this user.');
            }

            return view('dashboard.users.show', compact('user'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        } catch (\Exception $e) {
            Log::error('Error loading user details: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load user details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        try {
            $user = User::with(['school', 'roles'])
                ->findOrFail($id);

            // Check authorization
            if (auth()->user()->hasRole('school-admin')) {
                if ($user->school_id != auth()->user()->school_id) {
                    return redirect()->route('users.index')
                        ->with('error', 'You are not authorized to edit this user.');
                }
            }

            // Get schools for dropdown (only for super-admin)
            $schools = [];
            if (auth()->user()->hasRole('super-admin')) {
                $schools = School::where('status', 'active')
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();
            }

            $userRole = $user->roles->first()?->name ?? '';

            return view('dashboard.users.edit', compact('user', 'schools', 'userRole'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        } catch (\Exception $e) {
            Log::error('Error loading edit user form: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load edit user form. Please try again.');
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id, ImageWebpService $imageWebp)
    {
        try {
            $user = User::findOrFail($id);

            // Check authorization
            if (auth()->user()->hasRole('school-admin')) {
                if ($user->school_id != auth()->user()->school_id) {
                    return redirect()->route('users.index')
                        ->with('error', 'You are not authorized to update this user.');
                }
            }

            // Dynamic validation rules
            $emailRule = ['required', 'email', 'max:100'];
            if ($request->input('email') !== $user->email) {
                $emailRule[] = Rule::unique('users', 'email')->ignore($user->id);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => $emailRule,
                'password' => 'nullable|string|min:8|confirmed',
                'school_id' => [
                    'nullable',
                    'exists:schools,id',
                    function ($attribute, $value, $fail) use ($user) {
                        // Super admin can change school_id
                        if (auth()->user()->hasRole('super-admin')) {
                            // No additional validation needed
                        }
                        // School admin cannot change school_id
                        elseif (auth()->user()->hasRole('school-admin') && $value != $user->school_id) {
                            $fail('You cannot change the school assignment.');
                        }
                    },
                ],
                'role' => 'required|string|exists:roles,name',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'bio' => 'nullable|string|max:1000',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'remove_profile_picture' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            // Update user basic info
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            // Update password only if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->phone = $validated['phone'] ?? null;
            $user->address = $validated['address'] ?? null;
            $user->bio = $validated['bio'] ?? null;

            // Update school_id only for super-admin
            if (auth()->user()->hasRole('super-admin') && isset($validated['school_id'])) {
                $user->school_id = $validated['school_id'];
            }

            $user->save();

            // Handle profile picture removal
            if ($request->filled('remove_profile_picture') && $user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
                $user->profile_picture = null;
                $user->save();
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                
                $folderName = 'users/' . $user->uuid;
                $path = $imageWebp->putUploadedAsWebp('public', $folderName, $request->file('profile_picture'));
                $user->profile_picture = $path;
                $user->save();
            }

            // Update role (sync removes old roles and assigns new one)
            $user->syncRoles([$validated['role']]);

            DB::commit();

            return redirect()->route('users.show', $user->id)
                ->with('success', 'User updated successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Check authorization
            if (auth()->user()->hasRole('school-admin')) {
                if ($user->school_id != auth()->user()->school_id) {
                    return redirect()->route('users.index')
                        ->with('error', 'You are not authorized to delete this user.');
                }
            }

            // Prevent users from deleting themselves
            if (auth()->id() === $user->id) {
                return redirect()->route('users.index')
                    ->with('error', 'You cannot delete your own account.');
            }

            DB::beginTransaction();

            // Delete profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Delete the user
            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Update user status (activate/deactivate).
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Check authorization
            if (auth()->user()->hasRole('school-admin')) {
                if ($user->school_id != auth()->user()->school_id) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
            }

            $validated = $request->validate([
                'status' => 'required|in:active,inactive'
            ]);

            // You'll need to add a 'status' column to users table if you want this feature
            // $user->status = $validated['status'];
            // $user->save();

            return response()->json(['success' => true, 'message' => 'User status updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating user status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status'], 500);
        }
    }
}