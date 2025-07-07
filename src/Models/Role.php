<?php

namespace Budhaspec\Rolepermission\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function permissions() {
        return $this->hasMany(Permission::class, 'role_id');
    }

    public function modules() {
        return $this->belongsToMany(Module::class, 'permissions');
    }
}
