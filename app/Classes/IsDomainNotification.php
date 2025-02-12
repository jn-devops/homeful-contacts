<?php

namespace App\Classes;

interface IsDomainNotification
{
    function getNotificationChannelsVia(object $notifiable): array;
}
