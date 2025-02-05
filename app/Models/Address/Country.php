<?php

namespace App\Models\Address;

use App\Models\Address\Classes\NeedsDefault;
use App\Models\Address\Traits\HasDefault;
use Illuminate\Database\Eloquent\Model;

class Country extends Model implements NeedsDefault
{
    use HasDefault;

    const DEFAULT_CODE = 'PH';

    protected $connection = 'address-sqlite';

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
