<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 * @quthor Mustafa Amirbayov <amirbayovmustafa@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @var user
     */
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('view users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $users = User::with('roles')->where('is_admin', 1)->paginate(50);
        return view('backend.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $roles = Role::all();
        return view('backend.pages.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:12', // Minimum 12 simvol
                'regex:/[a-z]/', // Ən azı bir kiçik hərf
                'regex:/[A-Z]/', // Ən azı bir böyük hərf
                'regex:/[0-9]/', // Ən azı bir rəqəm
                'regex:/[@$!%*#?&]/', // Ən azı bir xüsusi simvol
                'confirmed', // Şifrə təsdiqlənməsi (password_confirmation)
            ],
            // 'password' => 'required|min:12|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%:]).*$/',
            'avatar' => 'nullable|mimes:jpg,jpeg,jpe,jif,jfif,jfi,png,svg,tiff,webp,heif,gif,bmp,ico|extensions:jpg,jpeg,jpe,jif,jfif,jfi,png,svg,tiff,webp,heif,gif,bmp,ico'
        ],
            [
                'name.required' => '"Ad Soyad" mütləqdir',
                'username.required' => '"İstifadəçi adı" mütləqdir',
                'username.unique' => '"İstifadəçi adı" artıq istifadə edilib',
                'email.unique' => '"Elektron poçt" artıq istifadə edilib',
                'email.required' => '"İstifadəçinin elektron poçtu" mütləqdir',
                'email.email' => '"Elektron poçt" formatı düzgün deyil',
                'password.required' => '"İstifadəçinin parolu" mütləqdir',
                'password.min' => '"İstifadəçinin parolu" minimum 12 simvoldan ibarət olmalıdır',
                'password.confirmed' => 'Parollar uyğun gəlmir',
                'password.regex' => '"İstifadəçinin parolu" ingilis əlifbası böyük,kiçik hərflər,simvol və rəqəmlərdən ibarət olmalıdır',
                'avatar.mimes' => '"Şəkil" jpg,jpeg,jpe,jif,jfif,jfi,png,svg,tiff,webp,heif,gif,bmp,ico formatlarında ola bilər !'
            ]
        );

        $user = new User();
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = Str::random(15) . time() . "." . $file->getClientOriginalExtension();
            $path = 'uploads/admins/avatars/' . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $user->avatar = $databasePath;
        }
        $user->name = $request->name;
        $user->username = $request->username ?? null;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_admin = 1;
        $user->save();


        if ($request->roles) {
            $user->assignRole($request->roles);
        }
        /* $permissions = Role::findByName(PermArrToStr($request->roles))->permissions;
             $user->givePermissionTo($permissions);*/

        return redirect()->route('backend.users.index')->with('success', 'Uğurla əlavə olundu');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('view users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $user = User::find($id);
        return view('backend.pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        if (!$this->user->hasRole('admin')) {
            $user = User::find($id);
            if ($user->hasRole('developer') && $this->user->id != $id) {
                return redirect()->route('backend.users.index')->with('error', 'İcazə yoxdur !!!');
            }
            $roles = Role::all();
            return view('backend.pages.users.edit', compact('user', 'roles'));
        } else {
            abort(403, 'İcazəniz yoxdur !!!');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        if (!$this->user->hasRole('admin')) {
            $this->validate($request, [

                'name' => 'required|max:50',
                'username' => 'required|max:100|unique:users,username,' . $id,
                'email' => 'required|max:180|email|unique:users,email,' . $id,
                'password' => [
                    'nullable',
                    'string',
                    'min:12', // Minimum 12 simvol
                    'regex:/[a-z]/', // Ən azı bir kiçik hərf
                    'regex:/[A-Z]/', // Ən azı bir böyük hərf
                    'regex:/[0-9]/', // Ən azı bir rəqəm
                    'regex:/[@$!%*#?&]/', // Ən azı bir xüsusi simvol
                    'confirmed', // Şifrə təsdiqlənməsi (password_confirmation)
                ],
                //  'password' => 'nullable|min:12|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%:]).*$/',
                'avatar' => 'nullable|mimes:jpg,jpeg,jpe,jif,jfif,jfi,png,svg,tiff,webp,heif,gif,bmp,ico|extensions:jpg,jpeg,jpe,jif,jfif,jfi,png,svg,tiff,webp,heif,gif,bmp,ico'
            ],
                [
                    'name.required' => '"Ad Soyad" mütləqdir',
                    'username.required' => '"İstifadəçi adı" mütləqdir',
                    'username.unique' => '"İstifadəçi adı" artıq istifadə edilib',
                    'email.unique' => '"Elektron poçt" artıq istifadə edilib',
                    'email.required' => '"İstifadəçinin elektron poçtu" mütləqdir',
                    'email.email' => '"Elektron poçt" formatı düzgün deyil',
                    //'password.required' => '"İstifadəçinin parolu" mütləqdir',
                    'password.min' => '"İstifadəçinin parolu" minimum 12 simvoldan ibarət olmalıdır',
                    'password.confirmed' => 'Parollar uyğun gəlmir',
                    'password.regex' => '"İstifadəçinin parolu" ingilis əlifbası böyük,kiçik hərflər,simvol və rəqəmlərdən ibarət olmalıdır',
                    'avatar.mimes' => '"Şəkil" jpg,jpeg,jpe,jif,jfif,jfi,png,svg,tiff,webp,heif,gif,bmp,ico formatlarında ola bilər !'
                ]
            );

            $user = User::find($id);
            if ($user->hasRole('developer') && $this->user->id != $id) {
                return redirect()->route('backend.users.index')->with('error', 'İcazə yoxdur !!!');
            }
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $fileName = Str::random(15) . time() . '.' . $file->getClientOriginalExtension();
                //$file->move(storage_path().'user/avatar/',$fileName);
                $path = 'uploads/admins/avatars/' . $fileName;
                $databasePath = 'storage/' . $path;
                Storage::disk('public')->put($path, File::get($file));
                $user->avatar = $databasePath;
                if (!empty($request->old_avatar)) {
                    $old_path = $request->old_avatar;
                    if (file_exists($old_path)) {
                        @unlink($old_path);
                    }
                }
            }
            $user->name = $request->name;
            $user->username = $request->username ?? null;
            $user->email = $request->email;
            $newPassword = $request->password;
            $hashedPassword = Hash::make($newPassword);
            $user->password = $hashedPassword;
       /*     if ($request->filled('password')) {
                $oldPasswords = PasswordHistory::where('user_id', $user->id)->pluck('password');
                foreach ($oldPasswords as $oldPassword) {
                    if (Hash::check($newPassword, $oldPassword)) {
                        return redirect()->back()->withErrors(['password' => 'Bu şifrə artıq istifadə olunub!']);
                    }
                }
                $user->password = $hashedPassword;
                $user->password_changed_at = now();

                PasswordHistory::create([
                    'user_id' => $user->id,
                    'password' => $hashedPassword
                ]);
            }*/
            $user->save();



            /*        if ($request->has('roles')) {
                        $role = Role::where('name', $request->roles)->first();
                        $user->roles()->detach();
                        $user->assignRole($role);
                        //$user->syncRole($role);
                    }*/
            /*    $permissions = Role::findByName(PermArrToStr($request->roles))->permissions;
                $user->givePermissionTo($permissions);*/

            if ($user->wasChanged('password') && $user->id == $this->user->id) {
                Auth::logout();
                request()->session()->flush();
                request()->session()->regenerate();
                return redirect()->route('backend.login')->with('error', 'Yeni parolla daxil olun');
            }

            return redirect()->back()->with('success', 'Uğurla  Yeniləndi');

        } else {
            abort(403, 'İcazəniz yoxdur !!!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $user = User::find($id);
        if ($user->hasRole('developer') && $this->user->id != $id) {
            return redirect()->route('backend.users.index')->with('error', 'İcazə yoxdur !!!');
        }
        $path = $user->avatar;
        if (file_exists($path)) {
            @unlink($path);
        }
        if ($user->delete()) {
            if ($id == auth()->user()->id) {
                request()->session()->flush();
                request()->session()->regenerate();
            }
            return redirect()->route('backend.users.index')->with('success', 'İstifadəçi uğurla silindi');
        }
        return redirect()->route('backend.users.index')->with('error', 'İstifadəçini silmək mümkün olmadı !!!');
    }


}
