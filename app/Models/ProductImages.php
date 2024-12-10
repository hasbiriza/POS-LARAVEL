<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductImages extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
      'product_pricing_id',
      'image_url'
    ];


}
