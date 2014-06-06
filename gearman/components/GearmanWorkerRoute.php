<?php
/**
 * File contains class WorkerRoute
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class GearmanWorkerRoute. Contains worker API command name, and route to controller action.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @see WorkerRouter
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
class GearmanWorkerRoute extends CComponent implements IGearmanWorkerRoute
{
    /**
     * @var string
     */
    private $_commandName;

    /**
     * @var string
     */
    private $_actionId;

    /**
     * @var string
     */
    private $_controllerId;

    /**
     * @param string $commandName
     * @param string $controllerId
     * @param string $actionId
     */
    public function __construct($commandName, $controllerId, $actionId)
    {
        $this->_commandName = (string)$commandName;
        $this->_controllerId = (string)$controllerId;
        $this->_actionId = (string)$actionId;
    }

    /**
     * Get controller action id.
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->_actionId;
    }

    /**
     * Get controller id.
     * @return string
     */
    public function getControllerId()
    {
        return $this->_controllerId;
    }

    /**
     * Get worker API command name.
     *
     * @return string
     */
    public function getCommandName()
    {
        return $this->_commandName;
    }
}