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
            'status' => self::OK,
            'check' => $checkResult
        ];
    }

    public function warn( $checkResult ){
        return [
            'status' => self::WARN,
            'check' => $checkResult
        ];
    }

    public function alert( $checkResult ){
        return [
            'status' => self::ALERT,
            'check' => $checkResult
        ];
    }

    public function failed( string $reason ){
        return [
            'status' => self::FAILED,
            'reason' => $reason
        ];
    }
    abstract public function run();
    abstract public function setConfiguration( array $configuration ):Check;

}

