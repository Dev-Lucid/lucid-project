<?php

$app_name = $argv[1];
$cwd      = __DIR__;
$root     = $cwd.'/../';
$app      = $root.'/apps/'.$app_name;

$dependencies = file_get_contents($app.'/etc/dependencies.json');
$dependencies = json_decode($dependencies,true);
switch (json_last_error())
{
    case JSON_ERROR_DEPTH:
        echo 'JSON parsing error - Maximum stack depth exceeded';
        exit();
        break;
    case JSON_ERROR_STATE_MISMATCH:
        echo 'JSON parsing error - Underflow or the modes mismatch';
        exit();
        break;
    case JSON_ERROR_CTRL_CHAR:
        echo 'JSON parsing error - Unexpected control character found';
        exit();
        break;
    case JSON_ERROR_SYNTAX:
        echo 'JSON parsing error - Syntax error, malformed JSON';
        exit();
        break;
    case JSON_ERROR_UTF8:
        echo 'JSON parsing error - Malformed UTF-8 characters, possibly incorrectly encoded';
        exit();
        break;
}

foreach($dependencies as $name=>$details)
{
    echo("  Checking ".$name."....\n");
    if (!file_exists($root.'/'.$name))
    {
        echo("      Directory does not exist, creating ".$root.$name."\n");
        mkdir($root.'/'.$name);
    }
    if (!file_exists($root.$name.'/'.$details['branch']))
    {
        echo("      Branch does not exist, cloning to ".$root.$name.'/'.$details['branch']."\n");
        $local_found = false;

        if (isset($details['local-url']) and is_array($details['local-url']))
        {
            for($i=0;($i<count($details['local-url']) and $local_found === false);$i++)
            {
                if(file_exists($details['local-url'][$i]))
                {
                    echo("      Using local clone\n");
                    shell_exec("git clone ".$details['local-url'][$i]." ".$root.$name.'/'.$details['branch']);
                    $local_found = true;
                }
            }
        }

        if ($local_found === false)
        {
            shell_exec("git clone ".$details['url']." ".$root.$name.'/'.$details['branch']);
        }
        shell_exec("cd ".$root.$name.'/'.$details['branch'].";git checkout ".$details['branch'].";");
    }
    else
    {
        echo("      Already cloned, pulling changes\n");
        shell_exec("cd ".$root.$name.'/'.$details['branch'].";git pull origin ".$details['branch'].";");
    }
}

?>
