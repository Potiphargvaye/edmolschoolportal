<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    

    /**
     * Show the registration form to the user.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_id' => [
                'required',
                'string',
                'max:255',
                'unique:users,registration_id',
                'regex:/^EDMOL\d{4}\/\d{4}$/',
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)->max(8)->letters()->numbers(),
            ],
            'role' => ['required', 'in:student,teacher,admin'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile-images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'registration_id' => $request->registration_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'image' => $imagePath,
        ]);

        event(new Registered($user));
      
        return redirect()->route('admin.users.index')
               ->with('success', 'User registered successfully!');
    }
}