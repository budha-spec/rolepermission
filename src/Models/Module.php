<?php

namespace Budhaspec\Rolepermission\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function children() {
        return $this->hasMany(Module::class, 'parent_id');
    }
    
    public function parent() {
        return $this->belongsTo(Module::class, 'parent_id');
    }
}
