<?php

function pickup()
{
    extract($_GLOBALS);

    if (!isset($input["login"]) || !isset($input["work"]))
	wjson(["result" => "ko", "message" => "bad pickup"]);
    if (!is_array($input["login"]))
	$input["login"] = [$input["login"]];
    if (!is_array($input["work"]))
	$input["work"] = [$input["work"]];
    if (count($input["login"]) < 1 || count($input["work"]) < 1)
	wjson(["result" => "ko", "message" => "nothing to pickup"]);
    $here = shell_exec("pwd");
    if (count($input["login"]) == 1 || count($input["work"]) == 1)
    {
	$login = $input["login"][0];
	$work = $input["work"][0];
	$file = "/tmp/".$work."_".$login.".tar.gz";
	$out = shell_exec("(cd /srv/nfs/users/$login/work/$work/ ; tar cvfz $file * 2>&1 )");
    }
    else
    {
	$out = "";
	$dirs = [];
	$file = uniqid()."tar.gz";
	foreach ($input["login"] as $login)
	{
	    foreach ($input["work"] as $work)
	    {
		$dirs[] = $file = "/tmp/".$work."_".$login."/";
		$out .= shell_exec("mkdir -p $file ; cp -r /srv/nfs/users/$login/work/$work/ $file");
	    }
	}
	$dirs = implode(" ", $dirs);
	$out .= shell_exec("(cd /tmp/ ; tar cvfz $file $dirs 2>&1 )");
	shell_exec("rm -rf $dirs");
    }
    $filec = file_get_contents($file);
    $filec = base64_encode($filec);
    shell_exec("rm -rf $file");
    wjson(["result" => "ok", "message" => $out, "content" => $filec]);
}

$command["pickup"] = "pickup";
