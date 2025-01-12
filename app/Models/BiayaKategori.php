<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaKategori extends Model
{
    use HasFactory;
    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
        'description',
    ];

}
