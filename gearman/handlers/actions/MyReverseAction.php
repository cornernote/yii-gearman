<?php

class MyReverseAction extends EGearmanAction
{

    public function run()
    {
        $workload = $this->getJob()->getWorkload();
        $response = strrev($workload);
        $this->getJob()->sendComplete($response);
    }

}