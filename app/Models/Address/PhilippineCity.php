<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Address\Classes\NeedsDefault;
use App\Models\Address\Traits\HasDefault;
use Illuminate\Database\Eloquent\Model;

class PhilippineCity extends Model implements NeedsDefault
{
    use HasFactory;
    use HasDefault;

    const DEFAULT_CODE = '133901';

    protected $connection = 'address-sqlite';

    protected $fillable = [
        'psgc_code',
        'city_municipality_description',
        'region_description',
        'province_code',
        'city_municipality_code',
        ];

    public function getRouteKeyName(): string
    {
        return 'city_municipality_code';
    }

    // A city belongs to a province
    public function province()
    {
        return $this->belongsTo(PhilippineProvince::class, 'province_code', 'province_code');
    }

    // A city belongs to a region
    public function region()
    {
        return $this->belongsTo(PhilippineRegion::class, 'region_code', 'region_code');
    }

    // A city has many barangays
    public function barangays()
    {
        return $this->hasMany(PhilippineBarangay::class, 'city_municipality_code', 'city_municipality_code');
    }
}
