<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Address\Classes\NeedsDefault;
use App\Models\Address\Traits\HasDefault;
use Illuminate\Database\Eloquent\Model;

class PhilippineProvince extends Model implements NeedsDefault
{
    use HasFactory;
    use HasDefault;

    const DEFAULT_CODE = '1339';

    protected $connection = 'address-sqlite';

    public function getRouteKeyName(): string
    {
        return 'province_code';
    }

    protected $fillable = [
        'psgc_code',
        'province_description',
        'region_code',
        'province_code',
        ];
    // A province belongs to a region
    public function region()
    {
        return $this->belongsTo(PhilippineRegion::class, 'region_code', 'region_code');
    }

    // A province has many cities
    public function cities()
    {
        return $this->hasMany(PhilippineCity::class, 'province_code', 'province_code');
    }

    // A province has many barangays
    public function barangays()
    {
        return $this->hasMany(PhilippineBarangay::class, 'province_code', 'province_code');
    }
}
