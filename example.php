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
    $phpCli->printLine("This script is using ". $phpCli->getMemoryUsage() . " of memory.");
    
    echo "\r\n";
    echo "Progress Bar Example:";
    $phpCli->progressStart(50);
    $i = 0;
    while($i < 50)
    {
        $i++;
        $phpCli->progressNext();
        usleep(100000);
    }
    $phpCli->progressStop();
    
    //prompt the user for input
    $this_is_my_name = $phpCli->promptInput("What is your name?");
    echo "Hello, " . $this_is_my_name . '!' . PHP_EOL . PHP_EOL;