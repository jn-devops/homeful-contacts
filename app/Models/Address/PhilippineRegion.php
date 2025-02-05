<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Address\Classes\NeedsDefault;
use App\Models\Address\Traits\HasDefault;
use Illuminate\Database\Eloquent\Model;

class PhilippineRegion extends Model implements NeedsDefault
{
    use HasFactory;
    use HasDefault;

    const DEFAULT_CODE = '13';

    protected $connection = 'address-sqlite';

    protected $fillable = [
        'psgc_code',
        'region_description',
        'region_code',
        ];

    public function getRouteKeyName(): string
    {
        return 'region_code';
    }

    // A region has many provinces
    public function provinces()
    {
        return $this->hasMany(PhilippineProvince::class, 'region_code', 'region_code');
    }

    // A region has many cities
    public function cities()
    {
        return $this->hasMany(PhilippineCity::class, 'region_code', 'region_code');
    }

    // A region has many barangays
    public function barangays()
    {
        return $this->hasMany(PhilippineBarangay::class, 'region_code', 'region_code');
    }
}
