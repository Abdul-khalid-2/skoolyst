<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AdminUserActivityMail;
use App\Services\HomeService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(HomeService $homeService): View
    {
        $defaultCities = [
            'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad',
            'Multan', 'Hyderabad', 'Peshawar', 'Sialkot', 'Gujranwala',
        ];

        $cities = collect($defaultCities)
            ->merge($homeService->getCities())
            ->unique()
            ->sort()
            ->values();

        return view('auth.register', compact('cities'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Notify admin about new registration
        Mail::to('skoolyst@gmail.com')->send(
            new AdminUserActivityMail($user, 'registered')
        );

        Auth::login($user);

        return redirect(route('website.home', absolute: false));
    }
}
