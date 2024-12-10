<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo'];

    public static function getLayoutData()
    {
        $layout = DB::table('layouts')->get();
        return $layout;
    }

    public static function getLayout()
    {
        $layout = DB::table('layouts')
            ->join('layout_settings', 'layouts.id', '=', 'layout_settings.layout_id')
            ->select('layouts.id as layout_id', 'layouts.name', 'layout_settings.*')
            ->get();
        return $layout;
    }
    public static function saveLayoutPreference($storeId, $layoutId)
    {
        $storeLayout = DB::table('store_layout_preferences')
            ->where('store_id', $storeId)
            ->first();
    
        if ($storeLayout) {
            DB::table('store_layout_preferences')
                ->where('store_id', $storeId)
                ->update([
                    'layout_id' => $layoutId,
                    'sidebar_color' => null,
                    'menu_link_color' => null,
                    'menu_link_hover_color' => null,
                    'app_brand_color' => null,
                    'navbar_color' => null,
                    'button_color' => null,
                    'button_hover_color' => null,
                    'fonts' => null
                ]);
        } else {
            DB::table('store_layout_preferences')->insert([
                'store_id' => $storeId,
                'layout_id' => $layoutId,
                'sidebar_color' => null,
                'menu_link_color' => null,
                'menu_link_hover_color' => null,
                'app_brand_color' => null,
                'navbar_color' => null,
                'button_color' => null,
                'button_hover_color' => null,
                'fonts' => null
            ]);
        }
        
    }
}
