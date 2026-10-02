<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return User::orderBy('email');
    }

    // ตาราง users ไม่มีคอลัมน์ code จึงไม่เรียก parent
    #[\Override()]
    function getFilterOptions(): array
    {
        return [
            'term' => [
                'email' => static fn(Builder $query, string $word)
                    => $query->where('email', 'LIKE', "%{$word}%"),
                'name' => static fn(Builder $query, string $word)
                    => $query->where('name', 'LIKE', "%{$word}%"),
                'role' => static fn(Builder $query, string $word)
                    => $query->where('role', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    // ใช้ email เป็น code
    #[\Override()]
    function find(string $email): User
    {
        $user = $this->getQuery()->where('email', $email)->firstOrFail();
        assert($user instanceof User);

        return $user;
    }

    private function currentUser(): User
    {
        $user = Auth::user();
        assert($user instanceof User);

        return $user;
    }

    // ==========================================
    // Administrator: จัดการ User ทุกคน
    // ==========================================

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', User::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('users.list', [
            'criteria' => $criteria,
            'users' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $email): View
    {
        $user = $this->find($email);
        Gate::authorize('view', $user);

        // bookmarks ของ route users.view
        session()->put('bookmarks.users.update-form', url()->full());
        session()->put(
            'bookmarks.users.delete',
            session()->get('bookmarks.users.view'),
        );
        session()->put(
            'bookmarks.users.update',
            session()->get('bookmarks.users.view'),
        );

        return view('users.view', [
            'user' => $user,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', User::class);

        return view('users.create-form', [
            'roles' => User::ROLES,
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $data = $request->getParsedBody();

        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(User::ROLES)],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput(Arr::except($data, ['password']))
                ->withErrors($validator);
        }

        $user = new User();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = $data['password']; // cast 'hashed' จัดการให้
        $user->role = $data['role'];
        $user->save();

        // Forward: bookmarks.users.create -> bookmarks.users.view
        if (session()->has('bookmarks.users.create')) {
            session()->put(
                'bookmarks.users.view',
                session()->get('bookmarks.users.create'),
            );
        }
        session()->forget('bookmarks.users.create');

        return redirect()
            ->route('users.view', [
                'user' => $user->email,
            ])
            ->with('status', "User {$user->email} was created.");
    }

    function showUpdateForm(string $email): View
    {
        $user = $this->find($email);
        Gate::authorize('update', $user);

        return view('users.update-form', [
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    function update(
        string $email,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $user = $this->find($email);
        Gate::authorize('update', $user);

        $data = $request->getParsedBody();
        $canChangeRole = Gate::allows('updateRole', $user);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string'],
        ];
        if ($canChangeRole) {
            $rules['role'] = ['required', Rule::in(User::ROLES)];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput(Arr::except($data, ['password']))
                ->withErrors($validator);
        }

        // email แก้ไม่ได้ / role ของตัวเองแก้ไม่ได้ (ไม่อ่านค่าจากฟอร์มเลย)
        $user->name = $data['name'];
        if (filled($data['password'] ?? null)) {
            $user->password = $data['password'];
        }
        if ($canChangeRole) {
            $user->role = $data['role'];
        }
        $user->save();

        // Forward: bookmarks.users.update -> bookmarks.users.view
        if (session()->has('bookmarks.users.update')) {
            session()->put(
                'bookmarks.users.view',
                session()->get('bookmarks.users.update'),
            );
        }
        session()->forget('bookmarks.users.update');

        return redirect()
            ->route('users.view', [
                'user' => $user->email,
            ])
            ->with('status', "User {$user->email} was updated.");
    }

    function delete(string $email): RedirectResponse
    {
        $user = $this->find($email);
        Gate::authorize('delete', $user); // ลบตัวเองไม่ได้

        $email = $user->email;
        $user->delete();

        return redirect(
            session()->get('bookmarks.users.delete') ?? route('users.list')
        )->with('status', "User {$email} was deleted.");
    }

    // ==========================================
    // Self: ผู้ใช้ทุกคนดู/แก้ข้อมูลตัวเอง (ไม่ผ่าน policy)
    // ==========================================

    function viewSelf(): View
    {
        $user = $this->currentUser();

        session()->put('bookmarks.users.selves.update-form', url()->full());
        session()->put(
            'bookmarks.users.selves.update',
            session()->get('bookmarks.users.selves.view'),
        );

        return view('users.selves.view', [
            'user' => $user,
        ]);
    }

    function showUpdateSelfForm(): View
    {
        $user = $this->currentUser();

        return view('users.selves.update-form', [
            'user' => $user,
        ]);
    }

    function updateSelf(ServerRequestInterface $request): RedirectResponse
    {
        $user = $this->currentUser();
        $data = $request->getParsedBody();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput(Arr::except($data, ['password']))
                ->withErrors($validator);
        }

        // แก้ได้แค่ name กับ password เท่านั้น (ไม่แตะ email และ role)
        $user->name = $data['name'];
        if (filled($data['password'] ?? null)) {
            $user->password = $data['password'];
        }
        $user->save();

        // Forward: bookmarks.users.selves.update -> bookmarks.users.selves.view
        if (session()->has('bookmarks.users.selves.update')) {
            session()->put(
                'bookmarks.users.selves.view',
                session()->get('bookmarks.users.selves.update'),
            );
        }
        session()->forget('bookmarks.users.selves.update');

        return redirect()
            ->route('users.selves.view')
            ->with('status', 'Your information was updated.');
    }
}