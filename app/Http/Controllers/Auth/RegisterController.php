<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]{10,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,farmer'],
            'terms' => ['required', 'accepted'],
            'address' => ['required_if:role,farmer', 'string', 'max:255', 'nullable'],
            'latitude' => ['required_if:role,farmer', 'numeric', 'between:-90,90', 'nullable'],
            'longitude' => ['required_if:role,farmer', 'numeric', 'between:-180,180', 'nullable'],

        ], [
            'phone.regex' => 'Le format du numéro de téléphone est invalide.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
        ]);
        info($request->all());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_active' => true,
                'last_login_at' => now(),
            ]);

            //event(new Registered($user));

            //Auth::login($user);

            return redirect()->route( 'login')
                ->with('success', 'Votre compte a été créé avec succès !');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.')
                ->withInput();
        }
    }
}/* 1234567890A */