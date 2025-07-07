<?php

namespace Budhaspec\Rolepermission\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['role_id', 'module_id', 'slug'];

    public function modules() {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
