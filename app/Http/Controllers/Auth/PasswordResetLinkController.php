<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        } catch (UnexpectedResponseException | TransportExceptionInterface $e) {
            // This catches "550 No such recipient" and similar SMTP issues
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This email address could not be reached. Please check and try again.']);
        } catch (\Exception $e) {
            // Generic fallback for any other unforeseen error
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Something went wrong while sending the reset link. Please try again later.']);
        }
    }
}
