<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
/**
 * Class RoleController
 *
 * @package App\Http\Controllers
 * @quthor Mustafa Amirbayov <amirbayovmustafa@gmail.com>
 */
class RoleController extends Controller
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
        if (is_null($this->user) || !$this->user->can('view role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $roles = Role::with('permissions')->get();
        $permissions = Permission::with('roles')->get();
        return view('backend.pages.roles.index', compact('roles', 'permissions'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('create role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        return view('backend.pages.roles.create', compact('permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('create role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.required' => 'Ad xanası boş buraxıla bilməz!',
            'name.max' => 'Ad 100 simvoldan artıq olmamalıdır!',
            'name.unique' => 'Bu ad artıq istifadə olunub!',
        ]);

        $role = Role::create(['name' => $request->name]);

        $permissions = $request->input('permissions');


        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }


        return redirect()->route('backend.roles.index')->with('success', 'Successefly Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('view role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $role = Role::findById($id);
        return view('backend.pages.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('edit role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $role = Role::findById($id);
        if($role->name == 'developer'){
            return redirect()->route('backend.roles.index')->with('error', 'NO PERMISSION !!!');
        }
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        return view('backend.pages.roles.edit', compact('permissions', 'permission_groups', 'role'));
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
        if (is_null($this->user) || !$this->user->can('edit role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }

        $request->validate([
            'name' => 'required|max:100'
        ], [
            'name.required' => 'Ad xanası boş buraxıla bilməz!',
            'name.max' => 'Ad 100 simvoldan artıq olmamalıdır!',
        ]);
        $role = Role::findById($id);
        if($role->name == 'developer'){
            return redirect()->route('backend.roles.index')->with('error', 'İcazəniz yoxdur !!!');
        }
        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }
        return redirect()->route('backend.roles.index')->with('success', 'Successefly Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('delete role')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $role = Role::findById($id);
        if($role->name == 'developer'){
            return redirect()->route('backend.roles.index')->with('error', 'NO PERMISSION !!!');
        }
        $users = $role->users;
        if ($role->delete()) {
            if(!empty($users)) {
                foreach ($users as $user) {
                    User::where('id', $user->id)->delete();
                }
            }
            return redirect()->route('backend.roles.index')->with('success', 'Deleted Successufly!');
        }
        return redirect()->route('backend.roles.index')->with('error', 'Error');

    }
}
