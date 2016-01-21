<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager;

class Logger
{
    const LEVEL_EMERG = 1;
    const LEVEL_ALERT = 2;
    const LEVEL_CRITICAL = 3;
    const LEVEL_ERROR = 4;
    const LEVEL_WARN = 5;
    const LEVEL_NOTICE = 6;
    const LEVEL_INFO = 7;
    const LEVEL_DEBUG = 8;

    /**
     * The friendly logger levels.
     *
     * @var array
     */
    public static $levels = [
        self::LEVEL_EMERG => 'Emergency',
        self::LEVEL_ALERT => 'Alert',
        self::LEVEL_CRITICAL => 'Critical',
        self::LEVEL_ERROR => 'Error',
        self::LEVEL_WARN => 'Warning',
        self::LEVEL_NOTICE => 'Notice',
        self::LEVEL_INFO => 'Info',
        self::LEVEL_DEBUG => 'Debug',
    ];

    /**
     * Logs a message to the console
     * and log file.
     *
     * @param string $message
     * @param int    $level
     */
    public static function log($message, $level = 6)
    {
        $currentLevel = (defined('LOGGER_LEVEL')) ? LOGGER_LEVEL : 6;

        if ($currentLevel >= $level) {
            $prefix = ($level == 0) ? '' : '['.static::$levels[$level].']';
            echo "{$prefix} {$message}".PHP_EOL;
        }
    }

    /**
     * Sets the current logging
     * level.
     *
     * @param int $level
     */
    public static function setLevel($level)
    {
        @define('LOGGER_LEVEL', $level);
    }
}
