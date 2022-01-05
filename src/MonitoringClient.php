<?php

namespace MediactiveDigital\MonitoringClient;

class MonitoringClient
{


    /**
     * Execute Check and return json formatted response
     *
     * @return void
     */    
    public function get(){
        $health = self::check();
        return response()->json($health);   

    }


    /**
     * Execute All Checks
     *
     * @return array
     */
    public static function check():array{
        
        $health =[];
        $config = self::getConfig();
  
        $checkList = $config['checks'];
        foreach( $checkList as $checkInfo ){
            $check = $checkInfo['check'];
            $health[] =( new $check() )->setConfiguration( $checkInfo )->run();
        }
        return $health;
    }



    private static function getConfig(){
        //@todo Check si laravel ou non
        return config('monitoring-client');
    }
}
