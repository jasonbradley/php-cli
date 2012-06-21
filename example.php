#!/usr/bin/php
<?php
    ini_set('display_errors', 1);

    include('PhpCli.class.php');

    $options = array(array('v', 'Verbose Message', false),
                     array('date', 'Date of Report (YYYY-MM-DD)', true));
    
    $phpCli = new PhpCli($argv, $options, "This is an example script.");
    
    echo "\r\nHas Option v? " . serialize($phpCli->hasArg('v'));
    echo "\r\nHas Option date? " . serialize($phpCli->hasArg('date'));
    echo "\r\nHas Option notreal? " . serialize($phpCli->hasArg('notreal'));
    echo "\r\n";
    
    echo "\r\nArg value for v? " . serialize($phpCli->getArgValue('v'));
    echo "\r\nArg value for date? " . serialize($phpCli->getArgValue('date'));
    echo "\r\nArg value for notreal? " . serialize($phpCli->getArgValue('notreal'));
    echo "\r\n";
    
    echo "\r\n";
    $phpCli->printLine("Messsage with no special color.");
    $phpCli->printLine("Messsage with special color.", 'blue', 'light_gray');
    
    echo "\r\n";
    echo $phpCli->printLine("This script is using ". $phpCli->getMemoryUsage() . " mb of memory.");