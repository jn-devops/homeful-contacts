<?php

namespace App\Traits;

trait HasDomainNotification
{
    /**
     * This is convoluted but it works.
     * Please refer to config file.
     *
     * @param object $notifiable
     * @return array
     */
    public function getNotificationChannelsVia(object $notifiable): array
    {
        $channels = array_intersect(array_unique(array_merge(config('homeful-contacts.channels.default'), config('homeful-contacts.channels')[self::class])), config('homeful-contacts.channels.allowed'));
        logger('HasDomainNotification@getNotificationChannelsVia');
        logger($channels);

        return $channels;
    }
}
