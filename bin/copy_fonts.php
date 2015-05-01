<?php

$app_name = $argv[1];
$cwd      = __DIR__;
$root     = $cwd.'/../';
$app      = $root.'apps/'.$app_name;

# this script basically only exists to load the dependencies config file, and then call 
# the lucid script in the right branch directory. I couldn't think of a better way of handling this :/
include($app.'/etc/dependencies.php');
$lucid = $root.'lib/lucid/'.$dependencies['lib/lucid']['branch'].'/';

$final_cmd = 'php -f '.$lucid.'bin/copy_fonts.php '.$app_name.' '.$app;
echo shell_exec($final_cmd);

?>