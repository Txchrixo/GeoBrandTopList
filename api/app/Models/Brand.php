<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands';

    protected $primaryKey = 'brand_id';
    protected $fillable = [
        'brand_name', 
        'brand_url',
        'brand_img_url', 
        'brand_rating',
        'brand_country_id',
        'is_active'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'brand_country_id', 'country_id');
    }
}
