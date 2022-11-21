#!/usr/bin/php
<?php
/*
** Jason Brillante "Damdoshi"
** Hanged Bunny Studio 2014-2021
** EFRITS SAS 2022
** Pentacle Technologie 2008-2022
**
** I HIT NFS:
** Infosphere Hand In The Network File System
**
** Ce logiciel est conçu pour être utilisé comme shell
** de reception par sshd pour un utilisateur consacré.
*/

function wjson($cmd, $exit = -1)
{
    echo json_encode($cmd, JSON_UNESCAPED_SLASHES);
    if ($exit != -1)
	exit($exit);
}

function now()
{
    $dt = new DateTime("now");
    return ($dt->getTimestamp() + $dt->getOffset());
}

$command = [];
if ($argc < 2)
   $INTRA = "https://intra.efrits.fr/api/albedo";
else
   $INTRA = $argv[1];
$LOGS = sys_get_temp_dir()."/infosphere_hand.log";

if (is_dir(__DIR__."/src/"))
{
    foreach (scandir(__DIR__."/src/") as $sc)
    {
	if ($sc[0] == '.') continue ;
	require ("src/$sc");
    }
}
else
{
    foreach (scandir("/etc/infosphere_hand/") as $sc)
    {
	if ($sc[0] == '.') continue ;
	require ("/etc/infosphere_hand/$sc");
    }
}

// On récupère ce qui est envoyé par le client par SSH.
$input = [];
while (($data = fread(STDIN, 1024)))
{
    if (trim($data) == "stop")
	exit(0);
    $input = json_decode($data, true);

    if (!isset($input["command"]))
    {  
	wjson(["result" => "ko", "message" => "no command specified"]);
	continue ;
    }
    if (!isset($command[$input["command"]]))
	continue ;

    if ($input["command"] == "log")
    {
	// Cette commande est envoyée par les machines du parc
	$command[$input["command"]]();
	continue ;
    }

    // Ici commencent les commandes de l'Infosphère
    $code = shell_exec("curl $INTRA 2> /dev/null");
    // L'infosphère est censé établir un code jetable utilisable une seule fois.
    // Ce coté "une seule fois" n'est pas implémentée.
    if (!isset($input["code"]) || trim($code) != trim($input["code"]))
    {
	wjson(["result" => "ko", "message" => "unknown command"]); // On ment.
	file_put_contents(
	    "/tmp/ihlog",
	    "[".date("d/m/Y H:i:s", now())."] Unrecognized connection with: $data\n",
	    FILE_APPEND
	);
        exit(1); // Ce n'est pas Albedo. On arrête tout de suite, c'est une attaque.
    }

    $command[$input["command"]]();
}

