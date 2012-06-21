<?php

    class PhpCliUtil
    {
        /**
         * File pointer to file being locked
         * 
         * @var type 
         */
        private $lock;
        
        public function getMemoryUsage()
        {
            $size = memory_get_usage(true);
            $unit=array('b','kb','mb','gb','tb','pb');
            return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        }
        
        /**
         * Create a file lock to prevent running on top of
         * another instance of the script
         * 
         * @param type $file 
         */
        protected function obtainLock($file)
        {
            // Don't run on top of another instance
            $this->lock = fopen($file, 'r');
            if ($this->lock === false || !flock($this->lock, LOCK_EX + LOCK_NB, $block) || $block) 
            {
                echo "Another instance is already running." . PhpCli::NEW_LINE;
                exit(1);
            }
        }
    }