<?php

namespace Polirium\Impersonate;

use Illuminate\Support\Facades\Facade;
use Polirium\Impersonate\Services\ImpersonateManager;

class Impersonate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ImpersonateManager::class;
    }
}
