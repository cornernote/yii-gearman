<?php
/**
 * File contains all interfaces to gearman components.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Interface of gearman application.
 */
interface IGearmanApplication
{
    /**
     * @abstract
     * @return IGearmanWorker
     */
    public function getGearmanWorker();

    /**
     * @abstract
     * @param IGearmanWorker $worker
     */
    public function setGearmanWorker(IGearmanWorker $worker);

    /**
     * @abstract
     * @return IGearmanRouter
     */
    public function getGearmanRouter();

    /**
     * @abstract
     * @param IGearmanRouter $router
     */
    public function setGearmanRouter(IGearmanRouter $router);

    /**
     * @abstract
     * @param IGearmanJob $command
     */
    public function runCommand(IGearmanJob $command);
}

/**
 * Interface of gearman worker component.
 */
interface IGearmanWorker extends IApplicationComponent
{
    /**
     * @abstract
     */
    public function run();

    /**
     * Set worker activity. If it started, this method is stopped it after cycle complete.
     *
     * @abstract
     * @param bool $active
     */
    public function setActive($active);

    /**
     * @abstract
     * @param string $commandName
     * @param mixed $callback
     */
    public function setCommand($commandName, $callback);

    /**
     * @abstract
     * @param  $commandName
     * @return void
     */
    public function removeCommand($commandName);
}

/**
 * Interface of gearman job object.
 */
interface IGearmanJob
{
    /**
     * @abstract
     * @return string
     */
    public function getCommandName();

    /**
     * @abstract
     * @return string
     */
    public function getIdentifier();

    /**
     * @abstract
     * @return string
     */
    public function getWorkload();

    /**
     * Sends result data and the complete status update for this job.
     *
     * @param string $result
     * @return bool
     */
    public function sendComplete($data);

    /**
     * @abstract
     * @param Exception $exception
     * @return void
     */
    public function sendException($exception);
}

/**
 * Interface of gearman router component.
 */
interface IGearmanRouter extends IApplicationComponent
{
    /**
     * @abstract
     * @param array $routes
     */
    public function setRoutes(array $routes);

    /**
     * @abstract
     * @param string $commandName
     * @param array|IGearmanRoute $route
     * @return void
     */
    public function setRoute($commandName, $route);

    /**
     * @abstract
     * @return IGearmanRoute[]
     */
    public function getRoutes();

    /**
     * @param IGearmanJob|string $command
     * @return IGearmanRoute
     */
    public function getRoute($command);
}

/**
 * Interface of gearman router route rule.
 */
interface IGearmanRoute
{
    /**
     * @abstract
     * @param string $commandName
     * @param string $handlerId
     * @param string $actionId
     */
    public function __construct($commandName, $handlerId, $actionId);

    /**
     * @abstract
     * @return string
     */
    public function getCommandName();

    /**
     * @abstract
     * @return string
     */
    public function getHandlerId();

    /**
     * @abstract
     * @return string
     */
    public function getActionId();
}

/**
 * Interface of gearman abstract action component.
 */
interface IGearmanAction extends IAction
{
    /**
     * @abstract
     * @param IGearmanJob $job
     */
    public function setJob($job);

    /**
     * @abstract
     * @return IGearmanJob
     */
    public function getJob();

    /**
     * @abstract
     * @return IGearmanHandler
     */
    public function getHandler();
}

/**
 * Interface of handler.
 */
interface IGearmanHandler
{
    /**
     * @abstract
     * @param string $actionId
     * @return IGearmanAction
     */
    public function createAction($actionId);
}
