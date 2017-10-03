<?php
require_once './ includes/head.php';
$formManager = new \Classes\FormManager();
$form = $formManager->createNewOrder();
$orderSectionsWithItems = $form->getPartsForForm();

$customers = $formManager->getListOfAllCustomers();

/** @var \Classes\Objects\ProductType $section */
foreach($orderSectionsWithItems as $section) {
    echo('<h2>' . $section->getName() . '</h2>');
    /** @var \Classes\Objects\Product $product */
    foreach($section->getItems() as $product) {
        echo('<p> - ' . $product->getName() . '</p>');
    }
}

/** @var \Classes\Objects\Customer $customer */
foreach($customers as $customer) {
    echo ('<p> - ' . $customer->getFullname());
}
