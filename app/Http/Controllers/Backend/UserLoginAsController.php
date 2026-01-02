<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserLoginAsController extends Controller
{
    public function loginAs(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['user.login_as']);

        $user = User::findOrFail($id);

        // Simpan ID user asli di session
        session()->put('original_user_id', auth()->id());

        Auth::login($user);

        session()->flash('success', __('You are now logged in as :name.', ['name' => $user->name]));

        if ($user->can('dashboard.view')) {
            return redirect()->route('admin.dashboard');
        }

        session()->flash('warning', __('User tidak memiliki izin untuk mengakses Dashboard. Dialihkan ke halaman Home.'));
        return redirect()->route('home');
    }

    public function switchBack(): RedirectResponse
    {
        if (session()->has('original_user_id')) {
            $originalUserId = session()->pull('original_user_id');
            $originalUser = User::findOrFail($originalUserId);
            Auth::login($originalUser);
            session()->flash('success', __('You have switched back to your original account.'));
        }

        return redirect()->route('admin.users.index');
    }
}
