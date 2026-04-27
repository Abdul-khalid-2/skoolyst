<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Models\Curriculum;
use App\Models\Feature;
use App\Models\School;
use App\Models\User;
use App\Services\AdminSchoolService;
use App\Services\ImageWebpService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->hasRole('super-admin')) {
                $query = School::query()->select([
                    'id',
                    'name',
                    'email',
                    'contact_number',
                    'city',
                    'address',
                    'status',
                    'school_type',
                    'created_at',
                ]);
            } elseif ($user->hasRole('school-admin')) {
                $query = School::query()
                    ->where('visibility', 'public')
                    ->where('id', $user->school_id)
                    ->select([
                        'id',
                        'name',
                        'email',
                        'contact_number',
                        'city',
                        'status',
                        'address',
                        'school_type',
                        'created_at',
                    ]);
            } else {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            $search = $request->string('search')->trim()->toString();
            if ($search !== '') {
                $searchableColumns = ['name', 'email', 'contact_number', 'address', 'city'];
                $query->where(function ($q) use ($search, $searchableColumns) {
                    $q->where($searchableColumns[0], 'like', '%'.$search.'%');
                    foreach (array_slice($searchableColumns, 1) as $column) {
                        $q->orWhere($column, 'like', '%'.$search.'%');
                    }
                });
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = strtolower((string) $request->get('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
            $allowedSort = [
                'id', 'name', 'email', 'contact_number', 'address', 'city',
                'status', 'school_type', 'created_at',
            ];
            if (! in_array($sortBy, $allowedSort, true)) {
                $sortBy = 'created_at';
            }

            $query->orderBy($sortBy, $sortDir);

            $schools = $query->paginate(10)->withQueryString();

            return view('dashboard.schooles.index', compact('schools'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load schools. Please try again.');
        }
    }

    public function create()
    {
        $features = Feature::orderBy('category')->orderBy('name')->get();
        $curriculums = Curriculum::orderBy('name')->get();

        return view('dashboard.schooles.create', compact('features', 'curriculums'));
    }

    public function store(StoreSchoolRequest $request, ImageWebpService $imageWebp, AdminSchoolService $adminSchools)
    {
        try {
            $adminSchools->createSchool($request, $imageWebp);
        } catch (\Throwable $e) {
            report($e);
            $userMessage = 'Could not save the school. Please review the highlighted fields and the message below, then try again.';
            if (str_contains($e->getMessage(), 'GD extension')
                || str_contains($e->getMessage(), 'Imagick extension')
                || str_contains($e->getMessage(), 'Image uploads require')) {
                $userMessage = 'Image upload failed: PHP GD or Imagick is not enabled for this site. Enable extension=gd in the php.ini used by your web server and restart it, or see WEBP_IMAGE_DEPLOY_INSTRUCTIONS.md. You can set IMAGE_NO_DRIVER_STORE_ORIGINAL=true in .env as a temporary workaround.';
            } elseif (config('app.debug')) {
                $userMessage .= ' Technical detail: '.$e->getMessage();
            }

            return redirect()->back()
                ->with('error', $userMessage)
                ->withInput();
        }

        return $request->has('save_and_add')
            ? redirect()->route('schools.create')->with('success', 'School created successfully! Create another school.')
            : redirect()->route('schools.index')->with('success', 'School and admin created successfully!');
    }

    /**
     * Display the specified school.
     */
    public function show($id)
    {
        try {
            $school = School::with([
                'profile',
                'curriculums',
                'features',
                'reviews',
                'images',
                'branches',
                'events',
            ])->findOrFail($id);

            return view('dashboard.schooles.show', compact('school'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load school details. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            $school = School::with([
                'profile',
                'translations',
                'curriculums',
                'features',
                'reviews',
                'images',
                'branches',
                'events',
            ])->findOrFail($id);

            $user = User::where('school_id', $school->id)->first();

            // Get all features and curriculums for the form
            $features = Feature::orderBy('category')->orderBy('name')->get();
            $curriculums = Curriculum::orderBy('name')->get();

            // Get current school features and curriculums
            $schoolFeatures = $school->features->pluck('id')->toArray();
            $schoolCurriculums = $school->curriculums->pluck('id')->toArray();

            return view('dashboard.schooles.edit', compact(
                'school',
                'user',
                'features',
                'curriculums',
                'schoolFeatures',
                'schoolCurriculums'
            ));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load school for editing. Please try again.');
        }
    }

    public function update(UpdateSchoolRequest $request, School $school, ImageWebpService $imageWebp, AdminSchoolService $adminSchools)
    {
        $school->load(['profile', 'images', 'user', 'features', 'curriculums']);
        try {
            $adminSchools->updateSchool($school, $request, $imageWebp);
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()->with('error', 'Failed to update school. Please try again.')->withInput();
        }

        return redirect()->route('schools.show', $school->id)
            ->with('success', 'School updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $school = School::findOrFail($id);
            $school->delete();

            return redirect()->route('schools')
                ->with('success', 'School deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')
                ->with('error', 'School not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete school. Please try again.');
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_email' => 'required|email|unique:schools,email',
            'school_address' => 'required|string',
            'school_city' => 'required|string',
            'school_contact' => 'nullable|string',
            'school_description' => 'nullable|string',
            'school_facilities' => 'nullable|string',
            'school_type' => 'required|in:Co-Ed,Boys,Girls,Separate',
            'school_website' => 'nullable|url',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'fee_structure_type' => 'required|in:fixed,class_wise',
            'regular_fees' => 'nullable',
            'discounted_fees' => 'nullable',
            'admission_fees' => 'nullable',
            'class_wise_fees' => 'required_if:fee_structure_type,class_wise|array|min:1|max:5',
            'class_wise_fees.*.range' => 'required|string|max:25',
            'class_wise_fees.*.amount' => 'required|string|max:15',
            'school_terms' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Prepare fees data based on structure type
            $regularFees = null;
            $discountedFees = null;
            $admissionFees = $validated['admission_fees'] ?? null;
            $classWiseFees = null;

            if ($validated['fee_structure_type'] === 'fixed') {
                $regularFees = $validated['regular_fees'] ?? null;
                $discountedFees = $validated['discounted_fees'] ?? null;
            } else {
                $transformedFees = [];
                if ($validated['fee_structure_type'] === 'class_wise' && isset($validated['class_wise_fees'])) {
                    foreach ($validated['class_wise_fees'] as $feeEntry) {
                        $transformedFees[$feeEntry['range']] = $feeEntry['amount'];
                    }
                }
                $classWiseFees = json_encode($transformedFees);
            }

            // Create the school
            $school = School::create([
                'uuid' => Str::uuid(),
                'name' => $validated['school_name'],
                'email' => $validated['school_email'],
                'address' => $validated['school_address'],
                'city' => $validated['school_city'],
                'contact_number' => $validated['school_contact'],
                'website' => $validated['school_website'],
                'description' => $validated['school_description'],
                'facilities' => $validated['school_facilities'],
                'school_type' => $validated['school_type'],
                'fee_structure_type' => $validated['fee_structure_type'],
                'regular_fees' => $regularFees,
                'discounted_fees' => $discountedFees,
                'admission_fees' => $admissionFees,
                'class_wise_fees' => $classWiseFees,
                'status' => 'inactive',
                'visibility' => 'public',
                'publish_date' => now(),
            ]);

            // Create the admin user
            $user = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'school_id' => $school->id,
                'password' => $validated['admin_password'],
            ]);

            $user->assignRole('school-admin');

            DB::commit();

            Auth::login($user);

            return redirect()->route('school.dashboard')->with('success', 'School registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Registration failed: '.$e->getMessage())->withInput();
        }
    }
}
