<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    // If you're using a custom table name, you can define it here.
    protected $table = 'roles'; // This is optional if you're following the default Spatie table names

    protected $fillable = ['name', 'guard_name']; // Add any necessary fillable fields here

    // Optionally, if you have custom settings, you can define them here.
}
