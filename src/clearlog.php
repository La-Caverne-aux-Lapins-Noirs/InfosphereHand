<?php

function clearlog()
{
    extract($_GLOBALS);

    unlink($LOGS);
    wjson(["result" => "ok"]);
}

$command["clearlog"] = "clearlog";
