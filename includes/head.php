<?php
function loadClass($class)
{
    $parts = explode('\\', $class);
    $requireString = '.';
    foreach($parts as $part) {
        $requireString .= '/' . $part;
    }
    require $requireString . '.php';
}
spl_autoload_register("loadClass");