<?php
/**
 * File contains class EGearmanAction
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class EGearmanAction is realisation of IHandlerAction interface, the base class for all handler action classes.
 *
 * Component extends basic CAction for work with job object as action parameter.
 *
 * @abstract
 */
abstract class EGearmanAction extends CAction implements IGearmanAction
{
    /**
     * @var EGearmanJob
     */
    private $_job;

    /**
     * Set job to work in action.
     *
     * @param EGearmanJob $job
     */
    public function setJob($job)
    {
        $this->_job = $job;
    }

    /**
     * Get job to work in action.
     *
     * @return EGearmanJob
     */
    public function getJob()
    {
        return $this->_job;
    }

    /**
     * @return EGearmanHandler
     */
    public function getHandler()
    {
        return parent::getController();
    }

}