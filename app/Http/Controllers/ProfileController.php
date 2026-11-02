<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'tokens' => $request->user()->tokens()->latest()->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $datos['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($datos);

        return back()->with('success', 'Perfil actualizado.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $request->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Contraseña actualizada.');
    }

    public function createToken(Request $request): RedirectResponse
    {
        $request->validate(['nombre' => ['required', 'string', 'max:50']]);

        $token = $request->user()->createToken($request->nombre)->plainTextToken;

        return back()->with('token', $token);
    }

    public function destroyToken(Request $request, int $id): RedirectResponse
    {
        $request->user()->tokens()->where('id', $id)->delete();

        return back()->with('success', 'Token revocado.');
    }
}
