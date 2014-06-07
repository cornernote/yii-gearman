<?php
/**
 * File contains class EGearmanApplication
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

require_once 'Interfaces.php';

/**
 * Class EGearmanApplication extends CApplication by providing functionality by gearman specific requests.
 *
 * EGearmanApplication managers contollers in MVC pattern, provides specific core components to work with
 * job queue:
 * <ul>
 *    <li>{@link gearmanWorker} EGearmanWorker, connect to job server queue, implements command routing.</li>
 *    <li>{@link gearmanRouter} EGearmanRouter, implements command to handler action routing.</li>
 * </ul>
 *
 * Example of gearman bootstrap script:
 * <code>
 * // change the following paths if necessary
 * $yii=dirname(__FILE__).'/../yii/yii.php';
 * $config=dirname(__FILE__).'/protected/config/gearman.php';
 *
 * // remove the following lines when in production mode
 * defined('YII_DEBUG') or define('YII_DEBUG', true);
 *
 * // specify how many levels of call stack should be shown in each log message
 * defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
 * require_once($yii);
 * require_once(dirname(__FILE__) . '/protected/extensions/gearman/EGearmanApplication.php');
 *
 * Yii::createApplication('EGearmanApplication', $config)->run();
 * </code>
 *
 * Example of gearman config:
 * <code>
 * return array(
 *     'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
 *     'aliases' => array(
 *         'gearman' => '/path/to/yii-gearman/gearman',
 *     ),
 *     'import' => array(
 *         'gearman.components.*',
 *     ),
 *     'components' => array(
 *         'gearmanWorker' => array(
 *             'class' => 'EGearmanWorker',
 *             'servers' => array('127.0.0.1'),
 *         ),
 *         'gearmanRouter' => array(
 *             'class' => 'EGearmanRouter',
 *             'routes' => array(
 *                 'reverse' => 'application.handlers.gearman',
 *             ),
 *         ),
 *    ),
 * );
 * </code>
 */
class EGearmanApplication extends CApplication implements IGearmanApplication
{
    /**
     * @return string the ID of the default handler. Defaults to 'gearman'.
     */
    public $defaultHandler = 'default';

    /**
     * @var bool
     */
    private $_isGearmanPecl;

    /**
     * Start gearman cycle.
     * To add custom route rules you can add in at handler.
     * <code>
     *
     * // add callback in php5.3 style
     * $app->getHandler()->setCommand('commandName', function($job){
     *      $job->setReturn($data->getMessage());
     * });
     *
     * // add callback as
     * $app->getWorker()->setCommand('commandName', array('handlerId', 'action'));
     * </code>
     */
    public function processRequest()
    {
        $routes = $this->getGearmanRouter()->getRoutes();
        $worker = $this->getGearmanWorker();

        foreach ($routes as $route) {
            $worker->setCommand($route->getCommandName(), array($this, 'runCommand'));
        }

        $worker->run();
    }

    /**
     * Get gearman worker component.
     * Also you can call $app->getComponent('gearman') or $app->worker.
     *
     * @return EGearmanWorker
     */
    public function getGearmanWorker()
    {
        return $this->getComponent('gearmanWorker');
    }

    /**
     * Set gearman worker component.
     * Also you can call $app->setWorker('router', $component) or $app->router = $component.
     *
     * @param mixed $worker
     */
    public function setGearmanWorker(IGearmanWorker $worker)
    {
        $this->setComponent('gearmanWorker', $worker);
    }

    /**
     * Get gearman route component.
     * Also you can call $app->getComponent('router') or $app->router.
     *
     * @return EGearmanRouter
     */
    public function getGearmanRouter()
    {
        return $this->getComponent('gearmanRouter');
    }

    /**
     * Set gearman route component.
     * Also you can call $app->setComponent('router', $component) or $app->router = $component.
     *
     * @param mixed $gearmanRouter
     * @return void
     */
    public function setGearmanRouter(IGearmanRouter $gearmanRouter)
    {
        $this->setComponent('gearmanRouter', $gearmanRouter);
    }

    /**
     * Default callback gearman worker.
     * It's calls when worker get new job and router have not custom callback.
     *
     * @param IGearmanJob $job
     * @throws CException
     * @throws Exception
     */
    public function runCommand(IGearmanJob $job)
    {
        try {
            $route = $this->getGearmanRouter()->getRoute($job);

            if (is_null($route)) {
                $handlerId = $this->defaultHandler;
                $actionId = $job->getCommandName();
            }
            else {
                $handlerId = $route->getHandlerId();
                $actionId = $route->getCommandName();
            }

            $handler = $this->createHandler($handlerId);
            $handler->init();

            $action = $handler->createAction($actionId);
            if ($action instanceof IGearmanAction) {
                $action->setJob($job);
                $action->run();
            }
            else {
                throw new CException(Yii::t('gearman', 'Action is not instance of IGearmanAction'));
            }
        } catch (Exception $e) {
            $job->sendException($e);
            throw $e;
        }
    }

    /**
     * Displays the captured PHP error.
     * This method displays the error in console mode when there is
     * no active error handler.
     * @param integer $code error code
     * @param string $message error message
     * @param string $file error file
     * @param string $line error line
     */
    public function displayError($code, $message, $file, $line)
    {
        echo "PHP Error[$code]: $message\n";
        echo "    in file $file at line $line\n";
        $trace = debug_backtrace();
        // skip the first 4 stacks as they do not tell the error position
        if (count($trace) > 4)
            $trace = array_slice($trace, 4);
        foreach ($trace as $i => $t) {
            if (!isset($t['file']))
                $t['file'] = 'unknown';
            if (!isset($t['line']))
                $t['line'] = 0;
            if (!isset($t['function']))
                $t['function'] = 'unknown';
            echo "#$i {$t['file']}({$t['line']}): ";
            if (isset($t['object']) && is_object($t['object']))
                echo get_class($t['object']) . '->';
            echo "{$t['function']}()\n";
        }
    }

    /**
     * Displays the uncaught PHP exception.
     * This method displays the exception in console mode when there is
     * no active error handler.
     * @param Exception $exception the uncaught exception
     */
    public function displayException($exception)
    {
        if (YII_DEBUG) {
            echo get_class($exception) . "\n";
            echo $exception->getMessage() . ' (' . $exception->getFile() . ' : ' . $exception->getLine() . "\n";
            echo $exception->getMessage() . ' (' . $exception->getFile() . ' : ' . $exception->getLine() . "\n";
            echo $exception->getTraceAsString() . "\n";
        }
        else {
            echo get_class($exception) . "\n";
            echo $exception->getMessage() . "\n";
        }
    }

    /**
     * Registers the core application components.
     * This method overrides the parent implementation by registering additional core components.
     */
    protected function registerCoreComponents()
    {
        parent::registerCoreComponents();

        $components = array(
            'gearmanWorker' => array(
                'class' => 'EGearmanWorker',
            ),
            'gearmanRouter' => array(
                'class' => 'EGearmanRouter',
            ),
        );

        $this->setComponents($components);
    }

    /**
     * Parse handler id string and return handler class instance.
     *
     * @param string $handlerId
     * @throws CException
     * @throws InvalidArgumentException
     * @return IGearmanHandler
     */
    public function createHandler($handlerId)
    {
        $handlerId = trim($handlerId);

        if (!strlen($handlerId))
            throw new InvalidArgumentException(Yii::t('gearman', 'Invalid handler id'));

        $path = null;
        $className = null;
        $classFile = null;
        if (strpos($handlerId, '.')) {
            $lastDot = strrpos($handlerId, '.');
            $path = substr($handlerId, 0, $lastDot);
            $className = $handlerId = substr($handlerId, $lastDot + 1);
        }
        else
            $className = $handlerId;

        if (!strpos($className, 'Handler')) {
            $className = ucfirst($className) . 'Handler';
        }
        if ($path)
            $classFile = $path . '.' . $className;
        else
            $classFile = $className;

        if (!class_exists($className, false)) {
            Yii::import($classFile, true);
        }

        if (class_exists($className, false)) {
            if (is_subclass_of($className, 'CController')) {
                return new $className($handlerId);
            }
            else
                throw new CException(Yii::t('gearman', 'Class "{class}" is not subclass of CController', array('{class}' => $className)));
        }
        else
            throw new CException(Yii::t('gearman', 'Class "{class}" is not found', array('{class}' => $className)));
    }

}
