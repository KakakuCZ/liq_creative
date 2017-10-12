<?php

header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$order = $formManager->getSpecOrder($_GET['orderID']);

$productsArray = [];
foreach ($order as $val) {
    foreach ($val as $key => $value) {
        if ($key === "product_id") {
            array_push($productsArray, $value);
        }
    }
}
//$order = $order[0];

//$order["product_id"] = $productsArray;

$output = [
    "order" => $order
];

echo(json_encode($output));
