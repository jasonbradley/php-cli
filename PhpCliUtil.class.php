<?php

    class PhpCliUtil
    {
        public function getMemoryUsage()
        {
            $size = memory_get_usage(true);
            $unit=array('b','kb','mb','gb','tb','pb');
            return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        }
    }