<?php

function run()
{
    extract($_GLOBALS);
    
    foreach (($fld = ["users", "target", "archive"]) as $elm)
	if (!isset($input[$elm]))
	    wjson(["result" => "ko", "message" => "field $elm is missing"], 1);
    foreach ($input["users"] as $trg)
	if (!preg_match('/[a-zA-Z\.\-_\/0-9]+/', $trg))
	    wjson(["result" => "ko", "message" => "$trg is not a valid username"], 1);
    if (!preg_match('/[a-zA-Z\.\-_\/0-9]+/', $input["target"]))
	wjson(["result" => "ko", "message" => "{$input["target"]} is not a valid directory"], 1);
    $source = $input["target"];
    
    if (($input["archive"] = base64_decode($input["archive"])) == false)
	wjson(["result" => "ko", "message" => "cannot decode archive from b64"]);
    $target = sys_get_temp_dir()."/".uniqid()."/";
    if (file_put_contents($target."program.tar.gz", $input["archive"]) === false)
	wjson(["result" => "ko", "message" => "cannot write archive to disk"]);

    $response = [];
    foreach ($input["users"] as $student)
    {
	$src = "/home/users/$student/$source";
	$dir = $target.$student;
	ob_start();
    	system(
	    "cp -r $src $dir ; ".
	    "(cd $dir && ".
	    "tar xvfz ../program.tar.gz >& /dev/null && ".
	    "./evaluate)"
	);
	$response[] = [
	    "login" => $student,
	    // Pas sur que ca soit la bonne méthode
	    // Il faudrait voir avec crawler et evaluator.
	    "trace" => ob_get_clean(),
	];
    }
    system("rm -rf $target");
    wjson(["result" => "ok", "content" => $response]);
}

$command["run"] = "run";
