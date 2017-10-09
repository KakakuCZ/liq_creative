<?php
//header('Content-type: application/json');
require_once './includes/head.php';
$customersManager = new \Classes\CustomersManager();
$customer = $customersManager->createNewUser($_GET);

$output = [
    'customer' => [
        'id' => $customer->getId(),
        'name' => $customer->getFullname(),
    ],
];

echo(json_encode($output));