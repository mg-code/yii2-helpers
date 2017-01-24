<?php

namespace mgcode\helpers;

class SystemHelper
{
    const OS_WINDOWS = 'windows';
    const OS_LINUX = 'linux';

    /**
     * Runs cli command in background
     * @param $command
     */
    public static function runBackgroundCommand($command)
    {
        $os = static::getOperatingSystem();
        if ($os == static::OS_WINDOWS) {
            $execCommand = $command.' > NUL';
            $WshShell = new \COM('WScript.Shell');
            $WshShell->Run($execCommand, 0, false);
        } else if ($os == static::OS_LINUX) {
            $execCommand = 'bash -c "exec nohup setsid '.$command.' > /dev/null 2>&1 &"';
            exec($execCommand);
        }
    }

    /**
     * Returns current OS type
     * @static
     * @return string
     */
    public static function getOperatingSystem()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return static::OS_WINDOWS;
        } else {
            return static::OS_LINUX;
        }
    }
}