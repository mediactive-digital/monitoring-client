<?php

namespace MediactiveDigital\MonitoringClient;

use MediactiveDigital\MonitoringClient\Checks\Check;

class MonitoringClient
{



    /**
     * Execute Check and return json formatted response
     *
     * @return void
     */    
    public function get(){

        $health = self::check();
        return json_encode( $health );  //on fait un json_encode manuel volontairement, pour la compatibilité avec le non-laravel
    }   
    


    /**
     * Execute All Checks
     *
     * @return array
     */
    public static function check($config = null):array{
        

        $health =[];
        if( $config === null ){
            $config = self::getConfig();
        }
  
        $checkList = $config['checks'];
        foreach( $checkList as $checkInfo ){
            $check = $checkInfo['check'];
            $checkInfo['environment'] = $config['environment'];
            $health[] =( new $check() )->setConfiguration( $checkInfo )->run();
        }
        return $health;
    }



    private static function getConfig(){
        //@todo Check si laravel ou non
        return config('monitoring-client');
    }
}
