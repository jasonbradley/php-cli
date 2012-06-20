<?php

    //Colors class does foreground/background colors for linux cli
    require_once 'Colors.class.php';
    
    class PhpCli
    {

        /**
         * New line characters 
         */
        const NEW_LINE = "\r\n";

        /**
         * List of options the script accepts
         */
        protected $options = array();

        /**
         * Description of what the script should do
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
         */
        protected $color = null;

        /**
         * @param array $arguments
         * @param array $options array([0] = Option, [1] = Description, [2] = Required)
         * @throws PhpCliException 
         */
        public function __construct($arguments, $options = array())
        {
            $this->addOptions($options);
            $this->setArguments($arguments);
            
            $this->color = new Colors();
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
                if (!$this->hasValidArguments())
                {
                    throw new PhpCliException("Invalid arguments provided.");
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
        
        /**
         * Add the options for the script
         * 
         * @param array $options array([0] = Option, [1] = Description, [2] = Required)
         * @throws PhpCliException 
         */
        protected function addOptions(Array $options)
        {
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
                foreach ($this->arguments as $index => $argument)
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
        public function showHelp()
        {
            echo $this->color->getColoredString(self::NEW_LINE . 'Description of "' . $this->arguments[0] . '":', 'light_green');
            echo $this->color->getColoredString(self::NEW_LINE . $this->getDescription() . self::NEW_LINE, 'light_blue');
            
            $options = $this->getOptions();

            if (is_array($options))
            {
                echo $this->color->getColoredString(self::NEW_LINE . "Below are the options for this script: " . self::NEW_LINE . self::NEW_LINE, 'light_gray');

                foreach ($options as $index => $option)
                {
                    echo $this->color->getColoredString(($index+1) . 
                         ". " . str_pad($option[0], 10, " ",STR_PAD_RIGHT) . "Description: " .
                         str_pad($option[1], 40," ", STR_PAD_RIGHT) . " Required: " . 
                         ((isset($option[2]) && $option[2] === true) ? 'Yes' : 'No'), 'yellow');
                    echo self::NEW_LINE;
                }

                echo self::NEW_LINE;
            }
            else
            {
                    echo self::NEW_LINE . "There are no options for this script." . self::NEW_LINE;
            }
        }
    }

    class PhpCliException extends Exception
    {
        public function __construct($message = null, $code = 0)
        {
            parent::__construct($message, $code);
        }

        public function __toString()
        {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
    }
