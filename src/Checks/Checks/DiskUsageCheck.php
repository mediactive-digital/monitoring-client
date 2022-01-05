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

    public function getName():string{
        return 'DiskUsage';
    }
    
    public function setConfiguration($configuration): Check
    {

        $this->warnLevel = isset($configuration['warn']) ? $configuration['alert'] : self::DEFAULT_WARNLEVEL;
        $this->alertLevel = isset($configuration['warn']) ? $configuration['alert'] : self::DEFAULT_ALERTLEVEL;
        $this->initialized = true;

        return $this;
    }

    public function run():array
    {
        if ($this->initialized) {

            /**
             * Check
             */
            $process = Process::fromShellCommandline('df -P .');
            $process->run();
            $output = $process->getOutput();

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


    private function test(float $diskUsage):array
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
        return [
            'usage'     => $diskUsage,
            'warnLevel' => $this->warnLevel,
            'alertLevel' => $this->alertLevel
        ];
    }
}
