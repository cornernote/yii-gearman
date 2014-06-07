<?php
/**
 * File contains class EGearmanInlineAction
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class EGearmanInlineAction represents an action that is defined as a handler method.
 *
 * The method name is like 'actionXYZ' where 'XYZ' stands for the action name.
 */
class EGearmanInlineAction extends EGearmanAction
{
    /**
     * Runs handler action.
     *
     * @throws CException
     * @return array hash array(handlerId, actionName)
     */
    public function run()
    {
        if (!($this->getJob() instanceof IGearmanJob)) {
            throw new CException(Yii::t('gearman', 'Gearman job object not setted to handler action'));
        }

        $handler = $this->getHandler();
        $methodName = 'action' . $this->getId();
        $method = new ReflectionMethod($handler, $methodName);
        if ($method->getNumberOfParameters() == 1) {
            return $method->invokeArgs($handler, array(
                $this->getJob()
            ));
        }
        else
            throw new CException('Handler action must contains 1 parameter');
    }
}