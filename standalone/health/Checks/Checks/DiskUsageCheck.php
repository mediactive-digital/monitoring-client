<?php

namespace MediactiveDigital\MonitoringClient\Standalone\Checks\Checks;

use MediactiveDigital\MonitoringClient\Standalone\Checks\Check;


class DiskUsageCheck extends Check
{

    const DEFAULT_WARNLEVEL = 70;
    const DEFAULT_ALERTLEVEL = 90;

    private $initialized = false;
    private $warnLevel;
    private $alertLevel;

	public function __construct(){}
	
	
    public function getName(){
        return 'DiskUsage';
    }
    
    public function setConfiguration($configuration)
    {
	
        $this->warnLevel = isset($configuration['warnLevel']) ? $configuration['warnLevel'] : self::DEFAULT_WARNLEVEL;
        $this->alertLevel = isset($configuration['alertLevel']) ? $configuration['alertLevel'] : self::DEFAULT_ALERTLEVEL;
        $this->initialized = true;
	
        return $this;
    }

    public function run()
    {
		
        if ($this->initialized) {

    
            $process = exec('df -P .', $output );
            $output = implode(' ',$output );

            preg_match('/(\d*)%/', $output, $matches);
            if (count($matches) > 0) {
                $diskUsage = $matches[0];
                return $this->test((float)$diskUsage);
            } else {
                return $this->failed("Failed to check diskUsage");
            }
        } else {
            return $this->failed("Failed to initialized");
        }
		
		
    }


    private function test($diskUsage)
    {
        if ($diskUsage < $this->warnLevel) {
            return $this->ok($this->formatResponse($diskUsage));
        } elseif ($diskUsage >= $this->alertLevel) { //alert
            return  $this->alert($this->formatResponse($diskUsage));
        } else { //warn
            return $this->warn($this->formatResponse($diskUsage));
        }
    }



    private function formatResponse($diskUsage)
    {
        return array(
            'usage'     => $diskUsage,
            'warnLevel' => $this->warnLevel,
            'alertLevel' => $this->alertLevel,
            'message' => "usage:".$diskUsage."% | warn>=".$this->warnLevel."% | alert>=".$this->alertLevel."%"
        );
    }
}
