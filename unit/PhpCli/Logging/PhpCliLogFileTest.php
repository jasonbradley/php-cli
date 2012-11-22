<?php
namespace PhpCli\Logging;

class PhpCliLogFileTest extends \PHPUnit_Framework_TestCase
{    
    /**
     * @expectedException \PhpCli\Logging\PhpCliLogException
     */
    public function testInvalidBasePath()
    {
        $phpLog = new PhpCliLogFile();
        
        $phpLog->setBaseFilePath('/not/real/path');
    }
    
    public function testValidBasePath()
    {
        $phpLog = new PhpCliLogFile();
        
        //This SHOULD work for any Linux based OS
        $phpLog->setBaseFilePath('/tmp/');
        
        $path = $phpLog->getBaseFilePath();
        $this->assertEquals(true, is_string($path));
    }
    
    public function testWrite()
    {
        $phpLog = new PhpCliLogFile();
        
        //This SHOULD work for any Linux based OS
        $phpLog->setBaseFilePath('/tmp/');
        $phpLog->write("This is a log.");
        
        $this->assertEquals(true, is_file($phpLog->getLogFilePath()));
        
        //clean up
        unlink($phpLog->getLogFilePath());
    }
}
