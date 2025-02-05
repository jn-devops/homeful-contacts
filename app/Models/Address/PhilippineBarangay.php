<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Address\Classes\NeedsDefault;
use App\Models\Address\Traits\HasDefault;
use Illuminate\Database\Eloquent\Model;

class PhilippineBarangay extends Model implements NeedsDefault
{
    use HasFactory;
    use HasDefault;

    const DEFAULT_CODE = '133901061';

    protected $connection = 'address-sqlite';

    protected $fillable = [
        'barangay_code',
        'barangay_description',
        'region_code',
        'province_code',
        'city_municipality_code',
        ];

    public function getRouteKeyName(): string
    {
        return 'barangay_code';
    }

    // A barangay belongs to a city
    public function city()
    {
        return $this->belongsTo(PhilippineCity::class, 'city_municipality_code', 'city_municipality_code');
    }

    // A barangay belongs to a province
    public function province()
    {
        return $this->belongsTo(PhilippineProvince::class, 'province_code', 'province_code');
    }

    // A barangay belongs to a region
    public function region()
    {
        return $this->belongsTo(PhilippineRegion::class, 'region_code', 'region_code');
    }
}
