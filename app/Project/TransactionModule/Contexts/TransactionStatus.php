<?php

namespace Project\TransactionModule\Contexts;

use ReflectionClass;

class TransactionStatus
{
    const AUTHORISED = 'AUTHORISED';
    const DECLINE = 'DECLINE';
    const REFUNDED = 'REFUNDED';

    public static function listReadable(): array
    {
        return [
            self::AUTHORISED => __('status.Authorised'),
            self::DECLINE => __('status.Decline'),
            self::REFUNDED => __('status.Refunded'),
        ];
    }

    public static function get(string $status): string
    {
        return self::listReadable()[$status];
    }


}
