<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Store;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class Subscriber extends Authenticatable
{
    use HasFactory, HasRoles;
    protected $table = 'subscribers';

    protected $fillable = [
        'org_name',
        'org_address',
        'owner_name',
        'contact_number',
        'email',
        // 'password',
        'pos_type',
        'status',
        'logo',
    ];
    public $timestamps = true;

    public function store()
    {
        return $this->hasMany(Store::class);
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function supplier()
    {
        return $this->hasMany(Supplier::class);
    }

    public function brand()
    {
        return $this->hasMany(Brand::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function role()
    {
        return $this->hasMany(Role::class);
    }
}
