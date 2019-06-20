<?php

namespace Helick\GTM\Contracts;

interface Bootable
{
    /**
     * Boot the service.
     *
     * @return void
     */
    public static function boot(): void;
}
