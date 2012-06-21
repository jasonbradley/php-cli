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
        
        /**
         * Initialize a progress bar
         * 
         * @param mixed $total   number of times we're going to call set
         * @param int   $message message to prefix the bar with
         * @param int   $options overrides for default options
         * 
         * @static
         */
        public function progressStart($total, $message = null, $options = null)
        {
            echo ProgressBar::start($total, $message = null, $options = null);
        }
        
        /**
         * Increment the progress bar 
         */
        public function progressNext()
        {
            echo ProgressBar::next();
        }
        
        /**
         * Start the progress bar indicator
         */
        public function progressStop()
        {
            echo ProgressBar::finish();
        }
    }