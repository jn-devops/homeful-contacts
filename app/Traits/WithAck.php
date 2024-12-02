<?php

namespace App\Traits;

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
