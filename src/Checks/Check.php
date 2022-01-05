<?php

namespace MediactiveDigital\MonitoringClient\Checks;

abstract class Check
{
    
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
            'name' => $this->getName(),
            'status' => self::OK,
            'check' => $checkResult
        ];
    }

    public function warn( $checkResult ){
        return [
            'name' => $this->getName(),
            'status' => self::WARN,
            'check' => $checkResult
        ];
    }

    public function alert( $checkResult ){
        return [
            'name' => $this->getName(),
            'status' => self::ALERT,
            'check' => $checkResult
        ];
    }

    public function failed( string $reason ){
        return [
            'status' => self::FAILED,
            'name' => $this->getName(),
            'reason' => $reason
        ];
    }
    abstract public function run();
    abstract public function setConfiguration( array $configuration ):Check;
    abstract public function getName():string;
}

