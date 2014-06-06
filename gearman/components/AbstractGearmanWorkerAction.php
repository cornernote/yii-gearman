<?php
/**
 * File contains class AbstractGearmanWorkerAction
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class AbstractWorkerAction is realisation of IWorkerAction interface, the base class for all worker
 * controller action classes.
 *
 * Component extends basic CAction for work with job object as action parameter.
 *
 * @abstract
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
abstract class AbstractGearmanWorkerAction extends CAction implements IGearmanWorkerAction
{
    /**
     * @var GearmanJob
     */
    private $_job;

    /**
     * Set job to work in action.
     *
     * @param GearmanJob $job
     */
    public function setJob($job)
    {
        $this->_job = $job;
    }

    /**
     * Get job to work in action.
     *
     * @return GearmanJob
     */
    public function getJob()
    {
        return $this->_job;
    }
}