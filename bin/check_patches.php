<?php

$app_name = $argv[1];
$cwd      = __DIR__;
$root     = $cwd.'/../';
$app      = $root.'apps/'.$app_name;

# this script basically only exists to load the dependencies config file, and then call 
# the lucid script in the right branch directory. I couldn't think of a better way of handling this :/
include($app.'/etc/dependencies.php');
$lucid = $root.'lib/lucid/'.$dependencies['lib/lucid']['branch'].'/';

$final_cmd = 'php -f '.$lucid.'bin/check_patches.php '.$app_name.' '.$app;
exec($final_cmd.' --report-patches',$output,$exit_status);
foreach($output as $i)
{
    echo ($i."\n");
}

#echo('exit_status: '.$exit_status."\n");
if(intval($exit_status) != 1)
{

    fwrite(STDOUT,  "Apply patches? y/n: ");


    $handle = fopen ("php://stdin","r");
    $line =  trim(fgets(STDIN)); 
    if($line  == 'y'){
        exec($final_cmd.' --do-patches',$final_output,$exit_status);  
        foreach($final_output as $i)
        {
            echo ($i."\n");
        }
    }else{
        exit(1);
    }
}
?>