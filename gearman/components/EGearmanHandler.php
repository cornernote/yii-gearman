<?php
/**
 * File contains class EGearmanHandler
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class EGearmanHandler is the customized base handler class for handler.
 * All handlers must extend from this class or implements interface IGearmanHandler.
 *
 * @abstract
 */
abstract class EGearmanHandler extends CController implements IGearmanHandler
{
    /**
     * Creates the action instance based on the action name.
     * The action can be either an inline action or an object.
     * The latter is created by looking up the action map specified in {@link actions}.
     *
     * @param string $actionID ID of the action. If empty, the {@link defaultAction default action} will be used.
     * @return IHandlerAction the action instance, null if the action does not exist.
     */
    public function createAction($actionID)
    {
        if ($actionID === '')
            $actionID = $this->defaultAction;
        if (method_exists($this, 'action' . $actionID) && strcasecmp($actionID, 's')) // we have actions method
            return new EGearmanInlineAction($this, $actionID);
        else
            return $this->createActionFromMap($this->actions(), $actionID, $actionID);
    }
}   