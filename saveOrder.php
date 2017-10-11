<?php
header('Content-type: application/json');
require_once './includes/head.php';
$formManager = new \Classes\FormManager();
$orderLogic = \Classes\Logic\OrderLogic::getInstance();
$order = $formManager->createOrderBySelectedOptions($_GET);
$orderLogic->saveOrder($order);

