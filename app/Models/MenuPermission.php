<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'guard_name',
        'created_at',
        'updated_at'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_permissions', 'permission_id', 'menu_id')
                    ->withTimestamps();
    }
}