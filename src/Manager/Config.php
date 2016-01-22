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

use Manager\Exceptions\FileNotFoundException;

class Config implements \ArrayAccess
{
    /**
     * The config file name.
     *
     * @var string
     */
    protected $file = 'config.json';

    /**
     * The config array.
     *
     * @var array
     */
    protected $config;

    /**
     * Opens the config file and stores the data.
     *
     * @param string|null $file
     *
     * @return void
     */
    public function __construct($file = null)
    {
        if (! empty($file)) {
            $this->file = $file;
        }

        $file = MANAGER_BASE_DIR."/{$this->file}";

        if (! file_exists($file)) {
            throw new FileNotFoundException("The file {$file} could not be found.");
        }

        $this->config = json_decode(file_get_contents($file), true);
    }

    /**
     * Saves the config file.
     *
     * @return bool
     */
    public function saveConfig()
    {
        $json = json_encode($this->config);
        file_put_contents(MANAGER_BASE_DIR."/{$this->file}",    $json);

        return true;
    }

    /**
     * Sets an offset on the config file.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->config[] = $value;
        } else {
            $this->config[$offset] = $value;
        }
    }

    /**
     * Checks if an offset exists.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * Unsets an offset.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    /**
     * Gets an offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return ($this->offsetExists($offset)) ? $this->config[$offset] : null;
    }
}
