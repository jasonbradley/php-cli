<?php

require_once(dirname(__FILE__).'/../PhpCli.class.php');

class PhpCliTest extends PHPUnit_Framework_TestCase
{
    public $testArgs = array();
    
    public function __construct()
    {
        $this->testArgs = array('file.php');
    }
    
    public function testHasArg()
    {
        $options = array(array('date', 'Date of Report (YYYY-MM-DD)', true));
        array_push($this->testArgs, '--date=12345');
        
        $phpCli = new PhpCli($this->testArgs, $options, "This is an example script.");
        
        $this->assertEquals(true, $phpCli->hasArg('date'));
        $this->assertEquals(false, $phpCli->hasArg('notreal'));
    }
    
    public function testGetArgValue()
    {
        $options = array(array('date', 'Date of Report (YYYY-MM-DD)', true));
        array_push($this->testArgs, '--date=12345');
        
        $phpCli = new PhpCli($this->testArgs, $options, "This is an example script.");
        
        $this->assertEquals('12345', $phpCli->getArgValue('date'));
    }
    
    public function testPrintLineWithoutVerbose()
    {
        $options = array(array('date', 'Date of Report (YYYY-MM-DD)', true));
        array_push($this->testArgs, '--date=12345');
        
        $phpCli = new PhpCli($this->testArgs, $options, "This is an example script.");
        $output = $phpCli->returnLine('I am printing a line.');
        
        $this->assertEquals(null, $output);
    }
    
    public function testPrintLineWithVerbose()
    {
        $options = array();
        array_push($this->testArgs, '-v');
        
        $phpCli = new PhpCli($this->testArgs, $options, "This is an example script.");
        $output = $phpCli->returnLine('I am printing a line.');
        
        $this->assertRegExp("/\w.*\\r\\n/", $output);
    }
    
    /**
     * @expectedException PhpCliException
     */
    public function testFlockThrowsException()
    {
        $options = array();
        array_push($this->testArgs, '-v');
        
        $phpCli = new PhpCli($this->testArgs, $options, "This is an example script.");        
        $phpCli = new PhpCli($this->testArgs, $options, "This is an example script.");
    }
}
