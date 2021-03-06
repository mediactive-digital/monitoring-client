<?php

namespace MediactiveDigital\MonitoringClient\Standalone\Checks\Checks;

use MediactiveDigital\MonitoringClient\Standalone\Checks\Check;


class EmptyCheck extends Check
{

    private $initialized = false;

    public function getName():string{
        return 'EmptyCheck';
    }
    
    public function setConfiguration($configuration): Check
    {
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
       
        
        //return $this->ok($this->formatResponse($diskUsage));
        //return  $this->alert($this->formatResponse());
        //return $this->warn($this->formatResponse());
        
    }



    private function formatResponse()
    {
        return array(
            'message' => ''
        );
    }
}
