<?php

namespace App\Models;

use Homeful\References\Models\Reference as BaseReference;
use Homeful\Contacts\Classes\ContactMetaData;

class Reference extends BaseReference
{
    public function getContactAttribute(): ContactMetaData
    {
        return ContactMetaData::from($this->getContact());
    }

    public function getContact(): Contact
    {
        return $this->getEntities(Contact::class)->first();
    }
}
