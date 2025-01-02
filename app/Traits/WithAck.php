<?php

namespace App\Traits;

/**
 * @deprecated
 */
trait WithAck
{
    public function with(): array
    {
        return [
            'author' => 'LB Hurtado',
            'copyright' => 'Raemulan Lands, Inc. 2024'
        ];
    }
}
