<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public function components() 
    {
        return $this->hasMany("App\Models\Component");
    }

    public function profile()
    {
        return $this->belongsTo("App\Models\Profile");
    }

    public function type()
    {
        return $this->belongsTo("App\Models\ModuleType");
    }
}
