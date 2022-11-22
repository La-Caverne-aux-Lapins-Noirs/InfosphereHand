<?php

function newuser()
{
    extract($_GLOBALS);

    // Ajouter l'utilisateur au LDAP
    $login = $input["user"];
    $uid = shell_exec("grep 'uidNumber:' users.ldif | cut -d ':' -f 2 | sort -br | head -n 1 | xars") + 1;
    if (!preg_match('/[a-zA-Z\.\-_\/0-9]+/', $login))
	wjson(["result" => "ko", "message" => "$login is not a valid username"], 1);
    if (!isset($input["first_name"]) || !isset($input["last_name"]))
    {
	$tmp = explode(".", $login);
	$first_name = $tmp[0];
	$last_name = $tmp[1];
    }
    else
    {
	$first_name = $input["first_name"];
	$last_name = $input["last_name"];
	if (!preg_match('/[a-zA-Z\.\-_\/0-9]+/', $first_name))
	    wjson(["result" => "ko", "message" => "$first_name is not a recordable first name"], 1);
	if (!preg_match('/[a-zA-Z\.\-_\/0-9]+/', $last_name))
	    wjson(["result" => "ko", "message" => "$last_name is not a recordable last name"], 1);
    }
    $mail = $input["mail"];
    if (filter_var($mail, FILTER_VALIDATE_EMAIL))
	wjson(["result" => "ko", "message" => "$mail is not a valid mail"], 1);	
    $password = escapeshellarg($input["password"])
    ;
    $password = shell_exec("slappasswd -s $password");
    $home = "/home/users/$login";
    ob_start();
?>
dn: cn=<?=$login; ?>,ou=people,dc=<?=$school; ?>,dc=fr
cn: <?="$login\n"; ?>
displayName: <?=ucfirst($first_name); ?> <?="$last_name\n"; ?>
gidNumber: = 984
objectClass: top
objectClass: nsPerson
objectClass: nsAccount
objectClass: nsOrgPerson
objectClass: posixAccount
uid: <?="$login\n"; ?>
mail: <?="$mail\n"; ?>
userPassword: <?=$password; ?>
structuralObjectClass: inetOrgPerson
loginShell: /bin/bash
uidNumber: <?="$uid\n"; ?>
homeDirectory: <?="$home\n"; ?>
    <?php
    $out = ob_get_clean();
    $out = explode("\n", $out);
    foreach ($out as &$o)
	$o = trim($o);
    system("cp -r ~/DefaultHome/* $home");
    system("chmod 740 -R .fluxbox/*");
    system("chmod 740 -R .fluxbox/*");
    system("chown $uid:users .fluxbox/my_menu .fluxbox/keys .bashrc .emacs .emacs.d");
    
    $dirs = [
	["", "751", "$uid:33"],
	["public", "755", "$uid:users"],
	["private", "700", "$uid:users"],
	["work", "751", "$uid:users"],
    ];
    $home = "/srv/nfs/users/$login";
    foreach ($dirs as $dir)
	system("mkdir -p $home/{$dir[0]} && chmod {$dir[1]} $home/{$dir[0]} && chown {$dir[2]} $home/{$dir[0]}");
}

$command["newuser"] = "newuser";
