<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'account_type' => 'nullable|string|in:buyer,Factory,Brand,Wholesaler',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $accountType = $request->input('account_type', 'buyer');
        $isSupplier = in_array($accountType, ['Factory', 'Brand', 'Wholesaler'], true);

        if ($isSupplier) {
            $user->assignRole('factory_owner');
            session(['registered_business_type' => $accountType]);

            try {
                $locale = app()->getLocale() ?: 'en';
                $factory = new \App\Models\Factory([
                    'user_id' => $user->id,
                    'business_type' => $accountType,
                    'email' => $user->email,
                    'language' => $locale,
                    'member_since' => date('Y'),
                    'status' => 'pending',
                    'subscription_plan' => 'free',
                    'product_limit' => 10,
                    'max_images_per_product' => 10,
                ]);
                foreach (array_unique([$locale, 'en']) as $lang) {
                    $factory->setTranslation('official_name', $lang, $user->name);
                }
                $factory->save();
            } catch (\Throwable $e) {
                Log::error('Failed to auto-create supplier profile on frontend register: ' . $e->getMessage());
            }

            \App\Services\EmailService::sendWelcome($user, 'factory', $locale);
        } else {
            $user->assignRole('buyer');

            \App\Services\EmailService::sendWelcome($user, 'buyer', $request->input('language') ?: app()->getLocale());
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'user' => $user,
            'redirect_url' => $isSupplier ? url('/factory/my-factory') : null,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => true, 'user' => Auth::user()]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->back();
    }
}
