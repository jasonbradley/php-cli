<?php

require_once(dirname(__FILE__).'/../logging/PhpCliLog.class.php');

class PhpCliLogFile extends PhpCliLog
{
    private $base_path;
    private $file_path;
    
    const file_prefix = 'cli_log';
    const file_extension = 'txt';
    const file_date_format = 'Ymd_His';
    
    public function write($msg)
    {
        $this->writeToLogFile($msg);
    }
    
    public function getBaseFilePath()
    {
        return $this->base_path;
    }
    
    public function setBaseFilePath($base_path)
    {
        if ($base_path == '' || !is_dir($base_path))
        {
            throw new PhpCliLogException("File path for log file does not exist. \"$base_path\"");
        }
        
        $this->base_path = $base_path;
        
        $this->setFilePath();
    }
    
    private function getFileName()
    {
        return self::file_prefix . '_' . date(self::file_date_format) . '.' . self::file_extension;
    }
    
    public function getLogFilePath()
    {
        return $this->file_path;
    }
    
    private function setFilePath()
    {
        $this->file_path = $this->base_path . '/' . $this->getFileName();
    }
    
    private function writeToLogFile($msg)
    {
        $date = $this->getLogDate();
        $log_msg = sprintf("%s - %s", $date, $msg);
        
        $bytes_written = file_put_contents($this->file_path, $log_msg . PHP_EOL, FILE_APPEND);
        
        if ($bytes_written === false)
        {
            throw new PhpCliLogException("Unable to write to log file. \"{$this->file_path}\"");
        }
    }
}