<?php
/**
 * Yii Gearman Worker.
 *
 * This script is meant to be run on command line to execute Gearman handler actions.
 *
 * You chould copy this file to your protected folder (the same folder as your yiic.php)
 */

/**
 * Gets the application start timestamp.
 */
defined('YII_BEGIN_TIME') or define('YII_BEGIN_TIME', microtime(true));

/**
 * This constant defines whether the application should be in debug mode or not. Defaults to false.
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);

/**
 * Defines the Yii framework installation path.
 */
defined('YII_PATH') or define('YII_PATH', 'vendor/yiisoft/yii/framework');

/**
 * Defines the Yii framework installation path.
 */
defined('YII_GEARMAN_PATH') or define('YII_GEARMAN_PATH', 'vendor/cornernote/yii-gearman/gearman');

/**
 * Path to your config file
 */
$config = 'config/gearman.php';

/**
 * run the Yii app (Yii-Haw!)
 */
require_once(YII_PATH . '/yii.php');
require_once(YII_GEARMAN_PATH . '/components/EGearmanApplication.php');
Yii::createApplication('EGearmanApplication', $config)->run();
