<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $primaryKey = 'country_id';
    protected $fillable = [
        'country_name', 
        'country_code_cca2',
        'country_flag_url',
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class, 'brand_country_id', 'country_id');
    }
}
