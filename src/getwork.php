<?php

function getwork()
{
    extract($_GLOBALS);

    foreach (($fld = ["users", "target"]) as $elm)
	if (!isset($input[$elm]))
	    wjson(["result" => "ko", "message" => "field $elm is missing"], 1);
    if (!preg_match('/[a-zA-Z\.\-_\/0-9]+/', $input["target"]))
	wjson(["result" => "ko", "message" => "{$input["target"]} is not a valid directory"], 1);
    foreach ($input["users"] as $trg)
	if (!preg_match('/[a-zA-Z\.]+/', $trg))
	    wjson(["result" => "ko", "message" => "$trg is not a valid username"], 1);
    $source = "/home/users/";
    $target = sys_get_temp_dir()."/".uniqid();
    if (mkdir($target, 0777, true) === false)
	wjson(["result" => "ko", "message" => "cannot create directory $target"], 1);
    $lst = [];
    foreach ($input["users"] as $trg)
    {
	$src = $source.$trg."/".$input["target"];
	if (!file_exists($src))
	    continue ;
	$dir = $target."/".$trg;
	system("cp -r $src $dir");
	$lst[] = substr($dir, strlen($target) + 1);
    }
    $collection = implode(" ", $lst);
    $targetfile = $target."/work.tar.gz";
    system($cmd = "(cd $target && tar cfz work.tar.gz $collection)");
    $archive = file_get_contents($targetfile);
    wjson(["result" => "ok", "content" => base64_encode($archive)]);
    unlink($targetfile);
    system("rm -rf $target");
}

$command["getwork"] = "getwork";
