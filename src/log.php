<?php

function logfunc()
{
    extract($_GLOBALS);

    if (file_exists($LOGS) && filesize($LOGS) > 1024 * 1024 * 16) // 16Mo
	wjson(["result" => "ko", "message" => "logfile is full"]);
    
    // Les machines du réseau mettent au courant InfosphereHand de l'actu
    foreach (($fld = ["mac", "name", "lock", "users"]) as $elm)
	if (!isset($input[$elm]))
	    wjson(["result" => "ko", "message" => "field $elm is missing"], 1);
    if (count($fld) + 1 != count($input))
	wjson(["result" => "ko", "message" => "too many fields"]);
    
    if (!is_array($input["users"]))
	$input["users"] = [$input["users"]];
    foreach ($input["users"] as $user)
	if (!preg_match('/[a-zA-Z\.]+/', $user))
	    wjson(["result" => "ko", "message" => "$user is not a valid username"], 1);
    
    $data = [
	"mac" => $input["mac"], // Adresse MAC de la machine
	"name" => $input["name"], // Nom de la machine
	"date" => now(), // Date de l'émission originale
	"lock" => $input["lock"], // Est ce que le poste est verrouillé ou non?
    ];
    foreach ($input["users"] as $user)
	file_put_contents($LOGS, json_encode(array_merge($data, ["login" => $user]), JSON_UNESCAPED_SLASHES)."\n", FILE_APPEND);
    wjson(["result" => "ok"]);
}

$command["log"] = "logfunc";
