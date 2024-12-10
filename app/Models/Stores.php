<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    use HasFactory;
    protected $fillable = ['store_name', 'email','phone','address','logo'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_product');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user', 'store_id', 'user_id');
    }
}
