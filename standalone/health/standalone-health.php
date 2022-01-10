<?php

require_once('../includes/init.php');
require('./Checks/Check.php');

require_once('./Checks/Checks/DiskUsageCheck.php');
require_once('./Checks/Checks/EnvironmentCheck.php');
require_once('./Checks/Checks/RedisCheck.php');
require_once('./Checks/Checks/SslVerificationCheck.php');

require_once('./MonitoringClient.php');

$appModeShouldBe = "PROD";

$config = array(

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
     * Type d'environnement : standalone, laravel, wordpress
     */
    'environment' => MediactiveDigital\MonitoringClient\Standalone\Checks\Check::TYPE_MEDIACTIVE,

    /**
     * Filtrage IP
     * Obligatoire
     */
    'ip_filter' => array(
        '127.0.0.1',
		'185.32.100.45'
    ),

    /**
     * Checks à réaliser
     */
    'checks' => array(
        array(
            'check'         => "MediactiveDigital\MonitoringClient\Standalone\Checks\Checks\DiskUsageCheck", //DiskUsageCheck::class,
            // 'warnLevel'     => DiskUsageCheck::DEFAULT_WARNLEVEL,
            // 'alertLevel'    => DiskUsageCheck::DEFAULT_ALERTLEVEL
        ),
        array(
            'check'     => "MediactiveDigital\MonitoringClient\Standalone\Checks\Checks\SslVerificationCheck", //::class,
            'domain'    => "https://".$_SERVER['HTTP_HOST']
        ),
        array(
            'check'     => "MediactiveDigital\MonitoringClient\Standalone\Checks\Checks\EnvironmentCheck", //::class,
            'should_be' => $appModeShouldBe,
        ),
         array(
            'check'     => RedisCheck::class,
         //   'ip'        => RedisCheck::DEFAULT_REDIS_IP,
          //  'port'      => RedisCheck::DEFAULT_REDIS_PORT,
        )
    )


);

/***
 * Security check
 */
 
if (empty($config['ip_filter']) || !in_array($_SERVER['REMOTE_ADDR'], $config['ip_filter'])) {
   die('Forbidden '.$_SERVER['REMOTE_ADDR']);
}

header('Content-Type: application/json');
$health = new MediactiveDigital\MonitoringClient\Standalone\MonitoringClient();
echo $health->get($config);
