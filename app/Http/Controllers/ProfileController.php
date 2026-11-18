<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'residence' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($user->role === 'siswa') {
            $rules['previous_school'] = ['nullable', 'string', 'max:180'];
            $rules['parent_name'] = ['nullable', 'string', 'max:150'];
        }
        if ($user->role === 'guru') $rules['nip'] = ['nullable', 'string', 'max:50'];

        $data = $request->validate($rules);
        unset($data['avatar']);

        if ($request->hasFile('avatar')) {
            $newPath = $request->file('avatar')->store('profile-photos', 'public');
            if ($user->avatar_path) Storage::disk('public')->delete($user->avatar_path);
            $data['avatar_path'] = $newPath;
        }

        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function password(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        auth()->user()->update(['password' => Hash::make($data['password'])]);
        return back()->with('success', 'Password berhasil diubah.');
    }
}
