<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


/**
 * Class LoginController
 *
 * @package App\Http\Controllers
 * @quthor Mustafa Amirbayov <amirbayovmustafa@gmail.com>
 */
class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showLoginForm(Request $request)
    {
        return view('backend.auth.login');
    }
    /**
     * login admin
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {

        $request->validate([
            'email_username' => 'required',
            'password' => 'required'
        ], [
            'email_username.required' => '""İstifadəçi adı" sahəsi boş buraxıla bilməz!',
            'password.required' => '"Parol" sahəsi boş buraxıla bilməz!'
        ]);
        $credentials_via_username = [
            'username' => $request->email_username,
            'password' => $request->password,
            'is_admin'=> 1
        ];

        if (Auth::attempt($credentials_via_username, $request->has('remember'))) {
            $passwordChangedAt = auth()->user()->password_changed_at;
            $user_id = auth()->user()->id;

            if (Carbon::parse($passwordChangedAt)->diffInDays(Carbon::now()) > 90) {
                return redirect()->route('backend.users.edit',$user_id)->withErrors('Şifrənizi yeniləməyin vaxtıdır !');
            }
            request()->session()->regenerate();
            session()->flash('success', 'Uğurla daxil oldunuz');
            return redirect()->intended(route('backend.dashboard.index'));
        }
        return redirect()->back()->with('error', '"İstifadəçi adı" yaxud "Parol" səhvdir !');
    }

    /**
     *  logout admin
     *
     * @return void
     */
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('backend.login')->with('success', 'Uğurlu çıxış');
    }
}
