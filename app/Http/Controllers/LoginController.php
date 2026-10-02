<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    function showForm(): View
    {
        return view('logins.form');
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse
    {
        // รับ credentials จากฟอร์ม
        $validator = Validator::make(
            $request->getParsedBody(),
            [
                'email' => 'required',
                'password' => 'required',
            ],
        );

        if ($validator->passes()) {
            if (Auth::attempt($validator->safe()->only(['email', 'password']))) {
                // สร้าง session ID ใหม่
                session()->regenerate();

                // กลับไป URL ที่ขอไว้ ถ้าไม่มีไปที่ products.list
                return redirect()->intended(route('products.list'));
            }

            $validator
                ->errors()
                ->add(
                    'credentials',
                    'The provided credentials do not match our records.',
                );
        }

        // ล็อกอินไม่ผ่าน กลับไปหน้าฟอร์มพร้อมข้อความ error
        return redirect()
            ->back()
            ->withErrors($validator);
    }

    function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();

        // สร้าง CSRF token ใหม่
        session()->regenerateToken();

        return redirect()->route('login');
    }
}