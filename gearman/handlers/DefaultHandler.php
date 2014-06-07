<?php

/**
 * DefaultHandler
 *
 * You chould copy this file to your protected folder (application.handlers).
 */
class DefaultHandler extends EGearmanHandler
{

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'myreverse' => array(
                'class' => 'application.handlers.actions.MyReverseAction',
            ),
        );
    }

    /**
     * @param EGearmanJob $job
     */
    public function actionReverse(EGearmanJob $job)
    {
        $workload = $task->getWorkload();
        $response = strrev($workload);
        $task->sendComplete($response);
    }
} 