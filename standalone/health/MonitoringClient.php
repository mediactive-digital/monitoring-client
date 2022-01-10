<?php

namespace MediactiveDigital\MonitoringClient\Standalone;
use MediactiveDigital\MonitoringClient\Standalone\Checks\Check;

class MonitoringClient
{

	public function __construct(){
	
	}

    /**
     * Execute Check and return json formatted response
     *
     * @return void
     */    
    public function get( $config ){

        $health = self::check($config);
        return json_encode( $health );  //on fait un json_encode manuel volontairement, pour la compatibilité avec le non-laravel
    }   
    


    public static function check($config = null){
       

        $health =array();

        $checkList = $config['checks'];
        foreach( $checkList as $checkInfo ){
            $check = $checkInfo['check'];
            $checkInfo['environment'] = $config['environment'];

            $thisCheck = new $check();
			
            $health[] = $thisCheck->setConfiguration( $checkInfo )->run();
			
			
        }
        return $health;
    }

    private static function getConfig(){
        //@todo Check si laravel ou non
        return config('monitoring-client');
    }


    
}
