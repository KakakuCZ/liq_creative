<?php
require_once './includes/head.php';

$formManager = new \Classes\FormManager();
$form = $formManager->createNewOrder();
$order_parts = $form->getPartsForForm();

$customers = $formManager->getListOfAllCustomers();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>liq_creative</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- styles -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap-4.0.0-beta.min.css">
        <link rel="stylesheet" type="text/css" href="css/mycss.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!-- scripts -->
        <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="js/popper-1.12.5.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-4.0.0-beta.min.js"></script>
        <script type="text/javascript" src="js/myjs.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col">
                    <a class="btn btn-primary" data-toggle="collapse" href="#menu" id="menu-button">Order</a>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-lg-5">
                    <div class="collapse" id="menu">
                        <div class="card card-body">
                            <form>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Customer</label>
                                    </div>
                                    <div class="col">
                                        <select id="customer" class="custom-select">
                                            <option value="0" selected>Choose...</option>
                                            <?php
                                            /** @var \Classes\Objects\Customer $customer */
                                            foreach ($customers as $customer) {
                                                echo('<option value="' . $customer->getId() . '">' . $customer->getFullname() . '</option>');
                                            }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-primary" id="add-customer" data-toggle="modal" data-target="#add-customer-form"><i class="material-icons" id="add-customer-icon">person_add</i></button>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Size</label>
                                    </div>
                                    <div class="row col">
                                        <div class="col">
                                            <input type="number" class="form-control product-select" id="width" placeholder="Width" min="0">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control product-select" id="length" placeholder="Length" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Base media (£/m)</label>
                                    </div>
                                    <div class="col">
                                        <select id="basemedia" class="custom-select product-select">
                                            <option value="0" selected>Choose...</option>
                                            <?php
                                            /** @var \Classes\Objects\Product $item */
                                            foreach ($order_parts['base_media']->getItems() as $item) {
                                                echo('<option value="' . $item->getId() . '">' . $item->getName() . '</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Print media (£/m)</label>
                                    </div>
                                    <div class="col">
                                        <select id="printmedia" class="custom-select product-select">
                                            <option value="0" selected>Choose...</option>
                                            <?php
                                            /** @var \Classes\Objects\Product $item */
                                            foreach ($order_parts['print_media']->getItems() as $item) {
                                                echo('<option value="' . $item->getId() . '">' . $item->getName() . '</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Ink (£14.00/m<sup>2</sup>)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" id="ink" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Finishing (£/m)</label>
                                    </div>
                                    <div class="col">
                                        <select id="finishing" class="custom-select product-select">
                                            <option value="0" selected>Choose...</option>
                                            <?php
                                            /** @var \Classes\Objects\Product $item */
                                            foreach ($order_parts['finishing']->getItems() as $item) {
                                                echo('<option value="' . $item->getId() . '">' . $item->getName() . '</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Labour (£30.00/hr)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" id="labour" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Shipping (£10.50)</label>
                                    </div>
                                    <div class="col">
                                        <select id="shipping" class="custom-select product-select">
                                            <option value="1">Yes</option>
                                            <option selected value="2">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-block btn-success">Save</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-block btn-info">Add</button>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" id="labour" disabled="true">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="add-customer-form" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <h5 class="modal-title">Add customer</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="First name" id="first-name">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Last name" id="last-name">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <input type="email" class="form-control" placeholder="Email" id="email">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <input type="number" class="form-control" placeholder="Phone number" id="phone">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
