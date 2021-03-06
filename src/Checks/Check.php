<?php

namespace MediactiveDigital\MonitoringClient\Checks;

class Check
{
    const MONITORING_CLIENT_VERSION = 'laravel-1.0';
    const OK = 'OK';
    const WARN = 'WARNING';
    const ALERT = 'ALERT';
    const FAILED = 'FAILED';

    const TYPE_WORDPRESS = 'wordpress';
    const TYPE_LARAVEL = 'laravel';
    const TYPE_MEDIACTIVE = 'mediactive';

    public static function identifyEnvironment(){
        if( defined('WPINC') ){
            return self::TYPE_WORDPRESS;
        }
        if( defined( 'APP_MODE') ){
            return self::TYPE_MEDIACTIVE;
        }
        if( function_exists('config') ){
            return self::TYPE_LARAVEL;
        }
        
        return false;
    }

    public function ok( $checkResult ){
        return [
            'version' => self::MONITORING_CLIENT_VERSION,
            'name' => $this->getName(),
            'status' => self::OK,
            'info' => $checkResult
        ];
    }

    public function warn( $checkResult ){
        return [
            'version' => self::MONITORING_CLIENT_VERSION,
            'name' => $this->getName(),
            'status' => self::WARN,
            'info' => $checkResult
        ];
    }

    public function alert( $checkResult ){
        return [
            'version' => self::MONITORING_CLIENT_VERSION,
            'name' => $this->getName(),
            'status' => self::ALERT,
            'info' => $checkResult
        ];
    }

    public function failed( string $reason ){
        return [
            'version' => self::MONITORING_CLIENT_VERSION,
            'status' => self::FAILED,
            'name' => $this->getName(),
            'info' => [
                'message' => $reason
            ]
        ];
    }
    abstract public function run();
    abstract public function setConfiguration( array $configuration ):Check;
    abstract public function getName():string;
}

