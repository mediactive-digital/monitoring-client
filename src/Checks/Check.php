<?php

namespace MediactiveDigital\MonitoringClient\Checks;

abstract class Check
{
    
    const OK = 'OK';
    const WARN = 'WARNING';
    const ALERT = 'ALERT';
    const FAILED = 'FAILED';

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

