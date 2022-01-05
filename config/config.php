<?php

/*
 * You can place your custom package configuration in here.
 */

use MediactiveDigital\MonitoringClient\Checks\Checks\DiskUsageCheck;

return [
    'enabled' => true,
    'route' => '/health',
    'laravel' => true,
    'ip_filter' => [
        '127.0.0.1'
    ],
    'checks' => [
        [
            'check'     => DiskUsageCheck::class,
            'warnLevel'      => 70,
            'alertLevel'     => 90
        ]
        
    ]

];