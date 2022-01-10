<?php

namespace MediactiveDigital\MonitoringClient\Standalone\Checks;

class Check
{
    const MONITORING_CLIENT_VERSION = "standalone-1.0";

    const OK = 'OK';
    const WARN = 'WARNING';
    const ALERT = 'ALERT';
    const FAILED = 'FAILED';

    const TYPE_WORDPRESS = 'wordpress';
    const TYPE_LARAVEL = 'laravel';
    const TYPE_MEDIACTIVE = 'mediactive';
    const TYPE_STANDALONE = "standalone";


    public function ok( $checkResult ){
        return array(
            'version' => self::MONITORING_CLIENT_VERSION,
            'name' => $this->getName(),
            'status' => self::OK,
            'info' => $checkResult
        );
    }

    public function warn( $checkResult ){
        return array(
            'version' => self::MONITORING_CLIENT_VERSION,
            'name' => $this->getName(),
            'status' => self::WARN,
            'info' => $checkResult
        );
    }

    public function alert( $checkResult ){
        return array(
            'version' => self::MONITORING_CLIENT_VERSION,
            'name' => $this->getName(),
            'status' => self::ALERT,
            'info' => $checkResult
        );
    }

    public function failed( $reason ){
        return array(
            'version' => self::MONITORING_CLIENT_VERSION,
            'status' => self::FAILED,
            'name' => $this->getName(),
            'info' => array(
                'message' => $reason
            )
        );
    }
    /*
    abstract public function run();
    abstract public function setConfiguration( array $configuration ):Check;
    abstract public function getName():string;
    */
}

