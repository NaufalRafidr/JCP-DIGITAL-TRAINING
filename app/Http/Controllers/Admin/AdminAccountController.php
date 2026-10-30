<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $request->merge([
            'username' => strtolower(trim((string) $request->input('username', ''))),
        ]);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:40',
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'string', 'min:5', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->username = strtolower(trim($validated['username']));

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'pengaturan'])
            ->with('success', 'Username dan pengaturan akun admin berhasil diperbarui.');
    }
}
