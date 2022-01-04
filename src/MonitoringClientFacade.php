<?php

namespace MediactiveDigital\MonitoringClient;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MediactiveDigital\MonitoringClient\Skeleton\SkeletonClass
 */
class MonitoringClientFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'monitoring-client';
    }
}
