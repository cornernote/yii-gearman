@echo off

rem -------------------------------------------------------------
rem  Yii gearman worker script for Windows.
rem  This is the bootstrap script for running yiig on Windows.
rem -------------------------------------------------------------

@setlocal

set BIN_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%BIN_PATH%yiig.php" %*

@endlocal