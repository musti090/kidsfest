<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getPermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getPermissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name','id')
            ->where('group_name',$group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role,$permissions)
    {
        $hasPermission = true;
        foreach($permissions as $permission){
            if(!$role->hasPermissionTo($permission->name)){
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }
    public function user_precinct()
    {
        return $this->belongsTo(Precinct::class,'precinct_id');
    }
    public function second_step_user_precinct()
    {
        return $this->belongsTo(SecondStepPrecinct::class,'second_step_precinct_id');
    }
    public function third_step_user_precinct()
    {
        return $this->belongsTo(ThirdStepPrecinct::class,'third_step_precinct_id');
    }
    public function user_city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
