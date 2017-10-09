<?php
session_start();
function loadClass($class)
{
    $parts = explode('\\', $class);
    $requireString = '/..';
    foreach($parts as $part) {
        $requireString .= '/' . $part;
    }
    require __DIR__ . $requireString . '.php';
}
spl_autoload_register("loadClass");

function includeHead()
{
    require_once 'head.phtml';
}
?>

