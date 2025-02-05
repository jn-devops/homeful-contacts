<?php

namespace App\Models\Address\Traits;

trait HasDefault
{
    public static function default(): ?static
    {
        return static::where(app(static::class)->getRouteKeyName(), self::DEFAULT_CODE)->first();
    }

    public function getIdentifierAttribute(): string
    {
        return $this->getAttribute($this->getRouteKeyName());
    }
}
