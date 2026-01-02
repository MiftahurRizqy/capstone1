<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): View
    {
        $this->checkAuthorization(auth()->user(), ['user.view']);

        return view('backend.pages.users.index', [
            'users' => $this->userService->getUsers(),
            'roles' => Role::all(),
        ]);
    }

    public function create(): View
    {
        $this->checkAuthorization(auth()->user(), ['user.create']);

        return view('backend.pages.users.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['user.create']);

        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        
        $user = User::create($data);
        
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        session()->flash('success', __('User has been created.'));

        return redirect()->route('admin.users.index');
    }

    public function edit(int $id): View
    {
        $this->checkAuthorization(auth()->user(), ['user.edit']);

        $user = User::findOrFail($id);

        return view('backend.pages.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    public function update(UserRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['user.edit']);

        $user = User::findOrFail($id);

        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        session()->flash('success', __('User has been updated.'));

        return redirect()->route('admin.users.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['user.delete']);

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', __('You cannot delete yourself.'));
            return back();
        }

        $user->delete();

        session()->flash('success', __('User has been deleted.'));

        return redirect()->route('admin.users.index');
    }
}
