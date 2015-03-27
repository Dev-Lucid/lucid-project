<?php
$config_path = $argv[1];
$variable    = $argv[2];
$property    = $argv[3];

include($config_path);
$final_var = $$variable;
print $final_var[$property.''];
?>