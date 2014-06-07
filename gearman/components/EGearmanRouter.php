<?php
/**
 * File contains class EGearmanRouter
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class EGearmanRouter contains rules to route worker methods to handler actions.
 *
 * You need configure component in your application , example:
 * <code>
 * 'components' => array(
 *     ...
 *     'gearmanRouter' => array(
 *         'class' => 'EGearmanRouter',
 *         'routes' => array(
 *             // gearman api command name => route rule
 *             'reverse' => 'defaultHandler',
 *             'myreverse' => array('StringHandler', 'reverse'),
 *             'var_dump' => new EGearmanRoute('var_dump', 'contoller', 'action'),
 *         ),
 *     ),
 * ),
 * </code>
 *
 * For get route rule object , use method getRoute($commandName), example:
 * <code>
 * $rule = Yii::app()->gearmanRouter->getRoute('strRevert');
 * $handler = Yii::app()->createHandler($rule->handlerId);
 * $handler->init();
 * $action = $handler->createAction($rule->actionId);
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
 */
class EGearmanRouter extends CApplicationComponent implements IGearmanRouter
{
    /**
     * Contains hash gearman API commmand name to route object.
     * @var CTypedMap<IGearmanRoute>
     */
    private $_routeList;

    /**
     *
     */
    public function __construct()
    {
        $this->_routeList = new CTypedMap('IGearmanRoute');
    }

    /**
     * Get route rule object by gearman API command name.
     *
     * @param IGearmanJob|string $command
     * @return IGearmanRoute
     */
    public function getRoute($command)
    {
        if ($command instanceof IGearmanJob) {
            $command = $command->getCommandName();
        }

        $command = strtolower($command);
        return $this->_routeList->itemAt($command);
    }

    /**
     * Return all registered route rules.
     *
     * @return IGearmanRoute[]
     */
    public function getRoutes()
    {
        return $this->_routeList->toArray();
    }

    /**
     * Set route rule by handler name, action or custom route object.
     *
     * @param string $commandName gearman API command name.
     * @param string|array|IGearmanRoute $route parameters or object EGearmanRoute.
     * @throws InvalidArgumentException if route is invalid.
     * @throws CException if command name already have route rule.
     */
    public function setRoute($commandName, $route)
    {
        if (!$this->_routeList->contains(strtolower($commandName))) {
            $routeObject = null;
            if (is_string($route)) {
                $routeObject = new EGearmanRoute($commandName, $route, $commandName);
            }
            elseif (is_array($route)) {
                $routeObject = new EGearmanRoute($commandName, $route[0], $route[1]);
            }
            elseif ($route instanceof IGearmanRoute) {
                $routeObject = $route;
            }
            else {
                throw new InvalidArgumentException(Yii::t(
                    'gearman',
                    'Parameter "$route" must be string, array(2) or IGearmanRoute object, {type} given',
                    array('{type}' => gettype($route))
                ));
            }

            $this->_routeList->add(strtolower($commandName), $routeObject);
        }
        else throw new CException(Yii::t('gearman', 'Command "{command}" is registered now'));
    }

    /**
     * Set map route rules. Example:
     * <code>
     *
     * array(
     *    'reverse' => 'gearmanHandler',
     *    'myreverse' => array('StringHandler', 'reverse'),
     *    'var_dump' => new EGearmanRoute('var_dump', 'contoller', 'action'),
     * )
     * </code>
     *
     * @param array $routes routes map
     */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $command => $route) {
            $this->setRoute($command, $route);
        }
    }
}

