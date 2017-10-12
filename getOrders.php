<?php
header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$orders = $formManager->getOrderBySelectedCustomer($_GET['customerId']);

$output = [
    "orders" => $orders
];

echo(json_encode($output));
