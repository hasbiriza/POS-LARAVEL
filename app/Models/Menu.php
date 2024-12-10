<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url', 'parent_id', 'icon', 'sort_order'];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
    
    public function MenuPermission()
    {
        return $this->belongsToMany(MenuPermission::class, 'menu_permissions', 'menu_id', 'permission_id')
                    ->withTimestamps();
    }
}
