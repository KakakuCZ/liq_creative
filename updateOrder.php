<?php

header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$orderLogic = \Classes\Logic\OrderLogic::getInstance();
$allGet = $_GET;
$orderID = $allGet["options-select"];
unset($allGet["options-select"]);
$order = $formManager->createOrderBySelectedOptions($allGet);
$orderLogic->updateOrder($order, $orderID);
//header("Location: index.php");
