<?php

function getlog()
{
    extract($_GLOBALS);
    
    // Infosphère vient régulièrement chercher les logs avec Albedo
    $logs = file_get_contents($LOGS);
    $logs = explode("\n", $logs);
    $nlogs = [];
    foreach ($logs as $log)
    {
	if (!strlen(trim($log)))
	    continue ;
	$nlogs[] = json_decode($log, true);
    }
    wjson(["result" => "ok", "content" => $nlogs]);
}

$command["getlog"] = "getlog";
