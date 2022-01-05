<?php

/*
 * You can place your custom package configuration in here.
 */

use MediactiveDigital\MonitoringClient\Checks\Checks\DiskUsageCheck;
use MediactiveDigital\MonitoringClient\Checks\Checks\EnvironmentCheck;
use MediactiveDigital\MonitoringClient\Checks\Checks\SslVerificationCheck;


return [

    /**
     * Activer le package
     */
    'enabled' => true,

    /**
     * Route retournant le json 
     * ATTENTION : l'exclure du référencement dans le robot  (& htaccess ? )
     */
    'route' => '/health',

    /**
     * Filtrage IP
     * Obligatoire
     */
    'ip_filter' => [
        '127.0.0.1'
    ],

    /**
     * Checks à réaliser
     */
    'checks' => [
        [
            'check'     => DiskUsageCheck::class,
            'warnLevel'      => 70,
            'alertLevel'     => 90
        ],
        [
            'check'     => SslVerificationCheck::class,
            'domain' => 'https://mediactive-digital.com'
        ],
        [
            'check'     => EnvironmentCheck::class,
            'should_be' => 'local'
        ]
    ]
        
    

];