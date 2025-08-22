<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Store;


use Illuminate\Support\Facades\DB;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'address',
        'email',
        'password',
        'contact_number',
        'subscriber_id',
        'region',
        'area',
        'territory',
        'distributor',
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

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public static function getpermissionsByPermissionName($group_name)
    {
        // $permissions = DB::table('permissions')
        //     ->select('permissions_name', 'name', 'name', 'permission_type', 'id')
        //     ->where('group_name', $group_name)
        //     ->get();
        // // dd($permissions);
        // return $permissions;
        $permissions = DB::table("permissions")
            ->select("permissions_name")
            ->addSelect(DB::raw("max(if(`permission_type` = 'create', name, null)) `p_create`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'create', id, null)) `p_create_id`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'edit', name, null)) `p_edit`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'edit', id, null)) `p_edit_id`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'view', name, null)) `p_view`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'view', id, null)) `p_view_id`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'destroy', name, null)) `p_destroy`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'destroy', id, null)) `p_destroy_id`"))
            // ->addSelect(DB::raw("max(if(`permission_type` = 'destroy', name, null))`p_destroy`, max(if(`permission_type` = 'destroy', id, null)) `p_destroy_id`"))
            ->where("group_name", "=", $group_name)
            ->groupBy("permissions_name")
            ->get();
        // print_r($permissions);
        return $permissions;
    }

    public function regionRelation()
    {
        return $this->belongsTo(Region::class, 'region', 'id')->select('id', 'region_name');
    }
    public function areaRelation()
    {
        return $this->belongsTo(Area::class, 'area', 'id')->select('id', 'area_name');
    }
    public function territoryRelation()
    {
        return $this->belongsTo(Territory::class, 'territory', 'id')->select('id', 'territory_name');
    }
    public function distributorRelation()
    {
        return $this->belongsTo(Distributor::class, 'distributor', 'id')->select('id', 'distributor_name', 'district_id', 'contact_no', 'email');
    }

}
