<?php

header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$orderLogic = \Classes\Logic\OrderLogic::getInstance();
$allGet = $_GET;
unset($allGet["options-select"]);
$order = $formManager->createOrderBySelectedOptions($allGet);
$orderLogic->saveOrder($order);
header("Location: index.php");
