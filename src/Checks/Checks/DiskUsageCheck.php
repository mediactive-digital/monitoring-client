<?php

namespace MediactiveDigital\MonitoringClient\Checks\Checks;

use MediactiveDigital\MonitoringClient\Checks\Check;
use Symfony\Component\Process\Process;


class DiskUsageCheck extends Check 
{

    const DEFAULT_WARNLEVEL = 70;
    const DEFAULT_ALERTLEVEL = 90;

    private $initialized = false;    
    private $warnLevel = 70;
    private $alertLevel = 90;

        public function setConfiguration( $configuration):Check{
           
            $this->warnLevel = isset( $configuration['warn']) ? $configuration['alert'] : self::DEFAULT_WARNLEVEL;
            $this->alertLevel = isset( $configuration['warn']) ? $configuration['alert'] : self::DEFAULT_ALERTLEVEL;
            $this->initialized = true;

            return $this;
        }

        public function run(){
            if( $this->initialized ){
                $process = Process::fromShellCommandline('df -P .');
                $process->run();
                $output = $process->getOutput(); 

                dd( $output );
            }else{
                return $this->failed("Failed to initialized");
            }
          
        }


}

