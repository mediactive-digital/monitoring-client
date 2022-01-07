<?php

namespace MediactiveDigital\MonitoringClient\Checks\Checks;

use MediactiveDigital\MonitoringClient\Checks\Check;

class EnvironmentCheck extends Check
{

    private $initialized = false;
    private $env_should_be = 'UNKNOWN';

    public function getName(): string
    {
        return 'Environment';
    }

    public function setConfiguration($configuration): Check
    {
        $this->initialized = true;
        $this->env_should_be = isset($configuration['should_be']) ? $configuration['should_be'] : '';
        return $this;
    }

    public function run(): array
    {
        if ($this->initialized) {

            return $this->test();
        } else {
            return $this->failed("Failed to initialized");
        }
    }


    private function test(): array
    {
        $type = self::identifyEnvironment();
        switch ($type) {
            case Check::TYPE_LARAVEL:

                $currentMode = config('app.env');
                if ($this->env_should_be != $currentMode) { //Mode réel != du mode attendu !
                    return $this->alert($this->formatResponse($currentMode,  config('app.debug') ) );
                } else {  //env OK, check si debug

                    if ($currentMode == 'production' && config('app.debug') == true) {  //si DEBUG activé en prod => ERREUR !
                        return $this->alert($this->formatResponse($currentMode,  config('app.debug') ) );
                    } else {
                        return $this->ok($this->formatResponse($currentMode,  config('app.debug') ) );
                    }
                }
                break;
            case Check::TYPE_MEDIACTIVE:
                $currentMode = APP_MODE;
                if ($this->env_should_be != $currentMode) { //Mode réel != du mode attendu !
                    return $this->alert($this->formatResponse($currentMode, null ) );
                }else{
                    return $this->ok($this->formatResponse($currentMode, null ) );
                }

                break;
            case Check::TYPE_WORDPRESS:

                if (defined(APP_MODE)) {
                    $currentMode = APP_MODE;
                    $debugMode = ( defined( WP_DEBUG) ? WP_DEBUG : null );
                    if ($this->env_should_be != $currentMode) { //Mode réel != du mode attendu !
                        return $this->alert($this->formatResponse( $currentMode, $debugMode ) );

                    }elseif( defined('WP_DEBUG') ){

                        if(defined('MODE_PRODUCTION') && $currentMode == MODE_PRODUCTION  && $debugMode ) {  //si DEBUG activé en prod => ERREUR !
                            return $this->alert($this->formatResponse($currentMode, $debugMode) );
                        } else {
                            return $this->ok($this->formatResponse( $currentMode, $debugMode) );
                        } 

                    }else{
                        return $this->ok($this->formatResponse( $currentMode, null ) );
                    }
                }

                break;
        }


        return $this->failed("Env type not defined");
        //return $this->ok($this->formatResponse($diskUsage));
        //return  $this->alert($this->formatResponse());
        //return $this->warn($this->formatResponse());

    }



    private function formatResponse( $currentMode, $debugMode=null)
    {
        return [
            'mode' => $currentMode,
            'should_be' => $this->env_should_be,
            'debug' => $debugMode,
            'message' => "Attendu: ".$this->env_should_be." | Actuel: ".$currentMode." | debug: ".(string)$debugMode
        ];
    }

}
