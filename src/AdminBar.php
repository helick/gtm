<?php

namespace Helick\GTM;

use Helick\GTM\Contracts\Bootable;

final class AdminBar implements Bootable
{
    /**
     * Boot the service.
     *
     * @return void
     */
    public static function boot(): void
    {
        $self = new static;
    }
}
