<?php

/**
 * Copyright 2012 Jason Bradley
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace PhpCli;

use PhpCli\Decoration\Colors;

class PhpCli extends PhpCliUtil
{

    /**
     * List of options the script accepts
     *
     * @var Array
     */
    protected $options = array();

    /**
     * Description of what the script should do
     *
     * @var String
     */
    protected $description = '';

    /**
     * An array of arguments called from command line
     *
     * @var Array
     */
    protected $arguments = array();

    /**
     * Instance of Color
     *
     * @var Color
     */
    protected $color = null;

    /**
     * @param array $arguments
     * @param array $options array([0] = Option, [1] = Description, [2] = Required)
     * @param string Script Description
     * @throws PhpCliException
     */
    public function __construct($arguments, $options = array(), $description = '')
    {
        $this->obtainLock($_SERVER['SCRIPT_FILENAME']);

        $this->setDescription($description);
        $this->addOptions($options);
        $this->setArguments($arguments);

        $this->color = new Colors();

        //display help
        if ($this->hasArg('h'))
        {
            $this->showHelp();
        }
    }

    protected function setOptions(Array $options)
    {
        $this->options = $options;
    }

    protected function setDescription($description)
    {
        $this->description = $description;
    }

    protected function setArguments($arguments)
    {
        if (is_array($arguments))
        {
            $this->arguments = $arguments;

            //validate that the arguments passed in match what options are available
            if (!$this->hasArg('h') && !$this->hasValidArguments())
            {
                throw new PhpCliException("Invalid arguments provided. Run with -h to see available arguments.");
            }
        }
        else
        {
            throw new PhpCliException("Arguments should be an array.");
        }
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Determines if the arguments passed in match
     * what was expected in the list of options
     *
     * @returns boolean
     */
    public function hasValidArguments()
    {
        if (!is_array($this->arguments))
        {
            return false;
        }
        else
        {
            //did we get all the required arguments?
            foreach ($this->options as $option)
            {
                if ((boolean)$option[2] === true)
                {
                    if (!$this->hasArg($option[0]))
                    {
                        return false;
                    }
                }
            }

            //look for extra arguments
            foreach ($this->arguments as $index => $argument)
            {
                if ($index > 0) //0 is the script
                {
                    if ($this->hasArg($argument) === false)
                    {
                        return false;
                    }
                }
            }

            return true;
        }
    }

    protected function addHelpOption(&$options)
    {
        array_push($options, array('h','Displays Help',false));
    }

    protected function addVerboseOption(&$options)
    {
        array_push($options, array('v','Show Verbose Messages',false));
    }

    /**
     * Add the options for the script
     *
     * @param array $options array([0] = Option, [1] = Description, [2] = Required)
     * @throws PhpCliException
     */
    protected function addOptions(Array $options)
    {
        //h should always be an option
        $this->addHelpOption($options);

        //v should always be an option
        $this->addVerboseOption($options);

        foreach ($options as $option)
        {
            if (!is_array($option) || count($option) == 1)
            {
                throw new PhpCliException("Options are not valid.");
            }
        }

        $this->setOptions($options);
    }

    /**
     * Adds a description of the script to display
     * in PhpCli::showHelp()
     *
     * @param type $description
     */
    public function addDescription($description)
    {
        $description = ($description == NULL || !is_string($description)) ? '' : $description;

        $this->setDescription($description);
    }

    /**
     * Does the argument exist?
     *
     * @param string $option
     * @return boolean
     */
    public function hasArg($arg)
    {
        if (is_string($arg) === false || trim($arg) == '')
        {
            return false;
        }
        else
        {
            $arg = $this->getArgKey($arg);

            $exists = false;
            foreach ($this->arguments as $argument)
            {
                if ($this->getArgKey($argument) == $arg)
                {
                    $exists = true;
                }
            }

            if (!$exists)
            {
                return false;
            }

            foreach ($this->options as $index => $option)
            {
                if ($option[0] == $arg)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Remove -, = and value and return
     * just the key
     *
     * @param string $arg
     * @return string
     */
    protected function getArgKey($arg)
    {
        if (substr($arg, 0, 2) == '--')
        {
            $arg_explode = explode("=", $arg);
            if (is_array($arg_explode) && count($arg_explode) == 2)
            {
                $arg = $arg_explode[0];
            }
        }

        $arg = str_replace("-", "", $arg);

        return $arg;
    }

    /**
     * Return the value of the argument (if applicable)
     *
     * @param mixed $arg
     */
    public function getArgValue($arg)
    {
        if ($this->hasArg($arg))
        {
            foreach ($this->arguments as $argument)
            {
                if ($this->getArgKey($argument) == $arg)
                {
                    if (substr($argument, 0, 2) == '--')
                    {
                        $arg_explode = explode("=", $argument);
                        if (is_array($arg_explode) && count($arg_explode) == 2)
                        {
                            return $arg_explode[1];
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Displays the description of the script and the
     * options for running the script
     */
    protected function showHelp()
    {
        echo $this->color->getColoredString(PHP_EOL . 'Description of "' . $this->arguments[0] . '":', 'light_green');
        echo $this->color->getColoredString(PHP_EOL . $this->getDescription() . PHP_EOL, 'light_blue');

        $options = $this->getOptions();

        if (is_array($options))
        {
            echo $this->color->getColoredString(PHP_EOL . "Below are the options for this script: " . PHP_EOL . PHP_EOL, 'light_gray');

            foreach ($options as $index => $option)
            {
                //show the number of "-"
                $option[0] = ((strlen($option[0]) == 1) ? '-' : '--') . $option[0];
                echo $this->color->getColoredString(($index+1) .
                     ". " . str_pad($option[0], 20, " ",STR_PAD_RIGHT) . "Description: " .
                     str_pad($option[1], 40," ", STR_PAD_RIGHT) . " Required: " .
                     ((isset($option[2]) && $option[2] === true) ? 'Yes' : 'No'), 'yellow');
                echo PHP_EOL;
            }

            echo PHP_EOL;
        }
        else
        {
                echo PHP_EOL . "There are no options for this script." . PHP_EOL;
        }

        exit(); //kill the script since the user requested help
    }

    /**
     * Outputs a message with optional foreground/background colors
     * if -v is passed in to the script
     *
     * @param string $msg
     * @param string $foreground_color
     * @param string $background_color
     */
    public function printLine($msg, $foreground_color = '', $background_color = '')
    {
        echo $this->formatLine($msg, $foreground_color, $background_color);
    }

    public function returnLine($msg, $foreground_color = '', $background_color = '')
    {
        return $this->formatLine($msg, $foreground_color, $background_color);
    }

    private function formatLine($msg, $foreground_color, $background_color)
    {
        //don't print if they don't have verbose message set
        if ($this->hasArg('v') === false || trim($msg) == '')
        {
            return;
        }

        if (in_array($foreground_color, $this->color->getForegroundColors()) === false)
        {
            $foreground_color = null;
        }

        if (in_array($background_color, $this->color->getBackgroundColors()) === false)
        {
            $background_color = null;
        }

        return $this->color->getColoredString($msg, $foreground_color, $background_color) . PHP_EOL;
    }

}

