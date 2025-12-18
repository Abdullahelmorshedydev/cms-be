<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ResetPasswordEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendPasswordEmailRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateAdminProfileRequest;
use App\Jobs\SendRestPasswordJob;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\ProfileService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserService $userService,
        protected ProfileService $profileService,
    ) {
    }

    public function loginPage()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle login request
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Handle dashboard login request
     *
     * Authenticates users via dashboard guard (admin users only).
     * Different from website login - only allows admin users.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Check if user is already authenticated
        if (Auth::check()) {
            $authUser = Auth::user();
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Already authenticated',
                    'user' => $authUser
                ], 200);
            }
            // Redirect based on user type
            if ($authUser->isAdmin()) {
                return to_route('dashboard.home');
            }
            return redirect()->route('site.home');
        }

        $user = $request->input('user');
        $password = $request->input('password');
        $remember = $request->boolean('remember', false);

        $response = $this->userService->login($user, $password, $remember);

        if ($response['code'] != 200) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $response['message']
                ], $response['code']);
            }
            return back()->withErrors(['user' => $response['message']]);
        }

        // Get the user model from response
        $userModel = $response['data']['user'];

        // IMPORTANT: Only allow admin users to login to dashboard
        if (!$userModel->isAdmin()) {
            $error = 'Only admin users can login to the dashboard.';
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $error
                ], Response::HTTP_FORBIDDEN);
            }
            return back()->withErrors(['user' => $error]);
        }

        // Logout from any existing session first to avoid conflicts
        Auth::logout();

        // Login with web guard and remember me
        Auth::login($userModel, $remember);

        // Regenerate session to prevent session fixation attacks and ensure session persistence
        session()->regenerate();

        // Log successful login
        Log::info('Dashboard user logged in', [
            'user_id' => $userModel->id,
            'email' => $userModel->email,
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $response['message'],
                'user' => $userModel,
                'token' => $response['data']['token'] ?? null,
                'redirect' => route('dashboard.home')
            ], Response::HTTP_OK);
        }

        return to_route('dashboard.home')->with(
            'message',
            [
                'status' => true,
                'content' => $response['message'],
            ]
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('dashboard.login')->with('message', [
            'content' => __('custom.auth.logged_out'),
            'status' => true,
        ]);
    }

    /**
     * Display the authenticated user's profile
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();
        $user = $authUser->load(['image']);
        return view('admin.auth.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile
     *
     * @param UpdateAdminProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAdminProfileRequest $request)
    {
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();
        $user = $authUser;

        $message = [
            'status' => false,
            'content' => __('custom.messages.updated_failed')
        ];

        if ($this->profileService->updateAdminProfile($request->validated(), $user)) {
            $message = [
                'status' => true,
                'content' => __('custom.auth.profile_updated'),
            ];
        }

        return back()->with('message', $message);
    }

    /**
     * Change user password
     *
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();
        $this->userRepository->update($request->validated(), $authUser->id);
        return back()->with('message', [
            'status' => true,
            'content' => __('custom.auth.password_changed'),
        ]);
    }

    public function forgetPassword()
    {
        return view('admin.auth.forget-password');
    }

    public function sendResetCode(SendPasswordEmailRequest $request)
    {
        /** @var string $email */
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        $user->update([
            'reset_code' => collect(range(1, 6))->map(fn() => random_int(0, 9))->implode(''),
            'reset_code_expires_at' => Carbon::now()->addMinutes(2),
        ]);

        // SendRestPasswordJob::dispatch($user);
        event(new ResetPasswordEvent($user));

        return to_route('password.reset.code', $user->email);
    }

    public function resetCodePage($email)
    {
        return view('admin.auth.reset-code', compact('email'));
    }

    public function verifyCode(Request $request)
    {
        /** @var string $email */
        $email = $request->input('email');
        /** @var string $code */
        $code = $request->input('code');
        $user = User::where('email', $email)->where('reset_code', $code)->where('reset_code_expires_at', '>', Carbon::now())->first();
        if (!$user) {
            return back()->with('message', [
                'status' => false,
                'content' => __('custom.auth.invalid_code'),
            ]);
        }
        return to_route('password.reset', $user->email);
    }

    public function resetPasswordPage($email)
    {
        return view('admin.auth.reset-password', compact('email'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        /** @var string $email */
        $email = $request->input('email');
        /** @var string $password */
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        $user->update([
            'password' => $password
        ]);

        return to_route('dashboard.login')->with('message', [
            'status' => true,
            'content' => __('custom.auth.password_reset'),
        ]);
    }
}
