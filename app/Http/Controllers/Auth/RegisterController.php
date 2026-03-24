<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Get validated data (already validated by RegisterRequest)
            $validated = $request->validated();

            // Create user within a transaction
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'client', // Default role
            ]);

            // Log the registration
            Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ]);

            DB::commit();

            // Authenticate the user
            Auth::login($user);

            // Redirect with success message
            return redirect()->route('login')
                ->with('success', 'Account created successfully! Please login to continue.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? 'N/A'
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['registration' => 'An error occurred during registration. Please try again.']);
        }
    }
}
