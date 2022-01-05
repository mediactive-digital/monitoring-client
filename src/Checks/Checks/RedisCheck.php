<?php

namespace MediactiveDigital\MonitoringClient\Checks\Checks;

use MediactiveDigital\MonitoringClient\Checks\Check;


class RedisCheck extends Check
{
    const DEFAULT_REDIS_URL = "127.0.0.1";
    const DEFAULT_REDIS_PORT = 6379;
    private $initialized = false;

    private $ip;
    private $port;

    public function getName():string{
        return 'Redis';
    }
    
    public function setConfiguration($configuration): Check
    {
        $this->ip= isset( $configuration['ip'] ) ? $configuration['ip'] : self::DEFAULT_REDIS_URL;
        $this->port= isset( $configuration['port'] ) ? $configuration['port'] : self::DEFAULT_REDIS_PORT;

        $this->initialized = true;
        return $this;
    }

    public function run():array
    {
        if ($this->initialized) {

            return $this->test();
            
        } else {
            return $this->failed("Failed to initialized");
        }
    }


    private function test():array
    {
        $vm = array(
            'host'     => $this->ip,
            'port'     => $this->port,
            'timeout' => 0.8 // (expressed in seconds) used to connect to a Redis server after which an exception is thrown.
        );
        
        $redis = new \Predis\Client($vm);
        try {
            $redis->ping();
        } catch (\Predis\Connection\ConnectionException $e) {
            return $this->alert($this->formatResponse($e->getMessage()) );
            // LOG that redis is down : $e->getMessage();
        }
        return $this->ok($this->formatResponse());
        
    }



    private function formatResponse( $message="" )
    {
        return  [
            'redis' => $this->ip.':'.$this->port,
            'message' => $message
        ];
    }
}
