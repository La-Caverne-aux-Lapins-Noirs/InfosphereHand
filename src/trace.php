<?php

function trace()
{
    extract($_GLOBALS);

    if (!isset($input["content"]) || !is_array($input["content"]))
	wjson(["result" => "ko", "message" => "bad trace"]);
    foreach ($input["content"] as $cnt)
    {
	if (!isset($cnt["login"]) || !isset($cnt["trace_name"]) || !isset($cnt["trace"]))
	    wjson(["result" => "ko", "message" => "incomplete_trace"]);
	$login = $cnt["login"];
	$file = $cnt["trace_name"];
	$target = "/srv/nfs/users/$login/trace/$file";
	$trace = base64_decode($cnt["trace"]);
	if (file_put_contents($target, $trace) == false)
	    wjson(["result" => "ko", "message" => "cannot save trace for $login"]);
    }
    wjson(["result" => "ok", "message" => "traces delivered"]);
}

$command["trace"] = "trace";
