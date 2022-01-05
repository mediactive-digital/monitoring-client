<?php

namespace MediactiveDigital\MonitoringClient;

class MonitoringClient
{


    
    public function get(){

        //security passed, do the logic

        $health = self::check();
        return response()->json($health);

    }


    // Build your next great package.

    public static function check(){
        
        $checkList = config('monitoring-client.checks');
        foreach( $checkList as $checkInfo ){

            $check = $checkInfo['check'];
            ( new $check() )->setConfiguration( $checkInfo )->run();
        }
    }
}
