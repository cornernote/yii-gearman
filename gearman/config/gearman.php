<?php
/**
 * Yii Gearman Configuration.
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
        'gearmanWorker' => array(
            'class' => 'EGearmanWorker',
            'servers' => array('127.0.0.1'),
        ),
        'gearmanRouter' => array(
            'class' => 'EGearmanRouter',
            // defines the handler and action to use for each of your jobs
            'routes' => array(
                // gearman api command name => route rule
                'reverse' => 'application.handlers.gearman',
                'myreverse' => array('StringHandler', 'reverse'),
                'var_dump' => new EGearmanRoute('var_dump', 'contoller', 'action'),
            ),
        ),
    ),
);