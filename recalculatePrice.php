<?php
header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$order = $formManager->createOrderBySelectedOptions(json_decode($_GET['inputs'], true));
var_dump($_GET);

$output = [
    'totalPrice' => $order->getTotalPrice(),
    'inkPrice' => $order->getInkPrice(),
    'labourPrice' => $order->getLabourPrice(),
    'totalHours' => $order->getHours()
];

echo(json_encode($output));
