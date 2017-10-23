<?php
header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$details = $formManager->getCustomerDetailsByID($_GET['customerId']);

$output = [
    "details" => $details
];

echo(json_encode($output));
