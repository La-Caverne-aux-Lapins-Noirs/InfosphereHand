<?php

function newteam()
{
    extract($_GLOBALS);

    system("addgroup");
    // On crée un groupe et on ajoute des membres dedans.
    // Il nous faut la relation UID-compte intra
    // On crée le dossier qu'il convient d'avoir dans le dossier de l'admin... et on fait des liens symboliques
    // chez les autres
}

$command["newteam"] = "newteam";
