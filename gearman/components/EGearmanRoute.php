<?php
/**
 * File contains class EGearmanRoute
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class EGearmanRoute. Contains gearman API command name, and route to handler action.
 */
class EGearmanRoute extends CComponent implements IGearmanRoute
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
    private $_handlerId;

    /**
     * @param string $commandName
     * @param string $handlerId
     * @param string $actionId
     */
    public function __construct($commandName, $handlerId, $actionId)
    {
        $this->_commandName = (string)$commandName;
        $this->_handlerId = (string)$handlerId;
        $this->_actionId = (string)$actionId;
    }

    /**
     * Get handler action id.
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->_actionId;
    }

    /**
     * Get handler id.
     * @return string
     */
    public function getHandlerId()
    {
        return $this->_handlerId;
    }

    /**
     * Get gearman API command name.
     *
     * @return string
     */
    public function getCommandName()
    {
        return $this->_commandName;
    }
}