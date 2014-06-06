<?php
/**
 * File contains class GearmanWorkerRouter
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class GearmanWorkerRouter contains rules to route worker methods to controller actions.
 *
 * You need configure component in your application , example:
 * <code>
 * 'components' => array(
 *     ...
 *     'gearmanRouter' => array(
 *         'class' => 'GearmanWorkerRouter',
 *         'routes' => array(
 *             // worker api command name => route rule
 *             'strrevert' => 'workerController',
 *             'mystrrevert' => array('StrController', 'revert'),
 *         ),
 *     ),
 * ),
 * </code>
 *
 * For get route rule object , use method getRoute($commandName), example:
 * <code>
 * $rule = Yii::app()->gearmanRouter->getRoute('strRevert');
 * $controller = Yii::app()->createController($rule->controllerId);
 * $controller->init();
 * $action = $controller->createAction($rule->actionId);
 * </code>
 *
 * For use it, add Yii import rule:
 * <code>
 * // Yii application config
 * 'import' => array(
 *     ...
 *     'gearman.components.*',
 * ),
 * </code>
 *
 *
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
class GearmanWorkerRouter extends CApplicationComponent implements IGearmanWorkerRouter
{
    /**
     * Contains hash worker API commmand name to route object.
     * @var CTypedMap<IGearmanWorkerRoute>
     */
    private $_routeList;

    public function __construct()
    {
        $this->_routeList = new CTypedMap('IGearmanWorkerRoute');
    }

    /**
     * Get route rule object by worker API command name.
     *
     * @param IWorkerJob|string $command
     * @return IGearmanWorkerRoute
     */
    public function getRoute($command)
    {
        if ($command instanceof IWorkerJob)
            $command = $command->getCommandName();

        $command = strtolower($command);
        return $this->_routeList->itemAt($command);
    }

    /**
     * Return all registered route rules.
     *
     * @return IGearmanWorkerRoute[]
     */
    public function getRoutes()
    {
        return $this->_routeList->toArray();
    }

    /**
     * Set route rule by controller name, action or custom route object.
     *
     * @param string $commandName worker API command name.
     * @param string|array|IGearmanWorkerRoute $route parameters or object GearmanWorkerRoute.
     * @see setRoutes
     * @throws InvalidArgumentException if route is invalid.
     * @throws CException if command name already have route rule.
     */
    public function setRoute($commandName, $route)
    {
        if (!$this->_routeList->contains(strtolower($commandName))) {
            $routeObject = null;
            if (is_string($route)) {
                $routeObject = new GearmanWorkerRoute($commandName, $route, $commandName);
            }
            elseif (is_array($route)) {
                $routeObject = new GearmanWorkerRoute($commandName, $route[0], $route[1]);
            }
            elseif ($route instanceof IGearmanWorkerRoute) {
                $routeObject = $route;
            }
            else {
                throw new InvalidArgumentException(Yii::t(
                    'worker',
                    'Parameter "$route" must be string, array(2) or IGearmanWorkerRoute object, {type} given',
                    array('{type}' => gettype($route))
                ));
            }

            $this->_routeList->add(strtolower($commandName), $routeObject);
        }
        else throw new CException(Yii::t('worker', 'Command "{command}" is registered now'));
    }

    /**
     * Set map route rules. Example:
     * <code>
     *
     * array(
     *    'strrevert' => 'workerController',
     *    'mystrrevert' => array('StrController', 'revert'),
     *    'var_dump' => new GearmanWorkerRoute('var_dump', 'contoller', 'action'),
     * )
     * </code>
     *
     * @param array $routes routes map
     * @see setRoute
     */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $command => $route) {
            $this->setRoute($command, $route);
        }
    }
}

