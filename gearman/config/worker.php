<?php
/**
 * Yii Gearman Worker Configuration.
 *
 * You chould copy this file to your protected/config folder (the same folder as your config/main.php)
 */
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'aliases' => array(
        'gearman' => YII_GEARMAN_PATH,
    ),
    'import' => array(
        'gearman.components.*',
    ),
    'components' => array(
        'workerDaemon' => array(
            'class' => 'GearmanWorkerDaemon',
            'servers' => array('127.0.0.1'),
        ),
        'workerRouter' => array(
            'class' => 'GearmanWorkerRouter',
            // defines the controller and action to use for each of your jobs
            'routes' => array(
                'reverse' => 'application.controllers.gearman',
            ),
        ),
    ),
);