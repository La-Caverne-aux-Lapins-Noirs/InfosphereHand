<?php

function ping()
{
    extract($_GLOBALS);

    // Vérification.
    if (!isset($input["content"]))
	wjson(["result" => "ko", "message" => "bad ping"]);
    $cnt = $input["content"];
    if (substr($cnt, 0, 4) == "b64:")
	$cnt = base64_decode(substr($cnt, 4));
    else
	$cnt = "b64:".base64_encode($cnt);
    if ($cnt === false)
	wjson(["result" => "ko", "message" => "invalid data format"]);
    else
	wjson(["result" => "ok", "content" => $cnt]);
}

$command["ping"] = "ping";
