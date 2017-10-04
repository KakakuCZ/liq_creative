<?php
header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$order = $formManager->createOrderBySelectedOptions(json_decode($_GET['inputs'], true));

$output = [
    'totalPrice' => $order->getPrice(),
    'inkPrice' => $order->getInkPrice(),
    'labourPrice' => $order->getLabourPrice(),
];

echo(json_encode($output));
