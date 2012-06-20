#!/usr/bin/php
<?php
    ini_set('display_errors', 1);

    include('PhpCli.class.php');

    $options = array(array('v', 'Verbose Message', false),
                     array('date', 'Date of Report (YYYY-MM-DD)', true));
    
    $phpCli = new PhpCli($argv, $options);
    
    $phpCli->addDescription("This is an example script.");
    $phpCli->showHelp();

    echo "\r\nHas Option v? " . serialize($phpCli->hasArg('v'));
    echo "\r\nHas Option date? " . serialize($phpCli->hasArg('v'));
    echo "\r\nHas Option notreal? " . serialize($phpCli->hasArg('notreal'));
    echo "\r\n";
    
    echo "\r\nArg value for v? " . serialize($phpCli->getArgValue('v'));
    echo "\r\nArg value for date? " . serialize($phpCli->getArgValue('date'));
    echo "\r\nArg value for notreal? " . serialize($phpCli->getArgValue('notreal'));
    echo "\r\n";
    
