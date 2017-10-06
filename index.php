<?php
require_once './includes/head.php';

$formManager = new \Classes\FormManager();
$form = $formManager->createNewEmptyOrder();
$order_parts = $form->getPartsForForm();

$customers = $formManager->getListOfAllCustomers();
?>

<!DOCTYPE html>
<html>
    <?php includeHead(); ?>
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
                            <form id="new-order-form" onsubmit="return checkOrderForm()">
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
                                    <div class="col">
                                        <div>
                                            <input type="number" class="form-control product-select" id="width" placeholder="Width (mm)" min="0">
                                        </div>
                                        <div>
                                            <input type="number" class="form-control product-select" id="length" placeholder="Length (mm)" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Base media<br>(£/m)</label>
                                    </div>
                                    <div class="col">
                                        <select id="basemedia" class="custom-select product-select">
                                            <option value="0" selected>Choose...</option>
                                            <?php
                                            /** @var \Classes\Objects\Product $item */
                                            foreach ($order_parts['base_media']->getItems() as $item) {
                                                echo('<option value="' . $item->getId() . '">' . $item->getName() . ' [£' . $item->getPriceSell() . ']' . '</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Print media<br>(£/m)</label>
                                    </div>
                                    <div class="col">
                                        <select id="printmedia" class="custom-select product-select">
                                            <option value="0" selected>Choose...</option>
                                            <?php
                                            /** @var \Classes\Objects\Product $item */
                                            foreach ($order_parts['print_media']->getItems() as $item) {
                                                echo('<option value="' . $item->getId() . '">' . $item->getName() . ' [£' . $item->getPriceSell() . ']' . '</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Ink<br>(£14.00/m<sup>2</sup>)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" value="£0" class="form-control" id="ink" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Finishing<br>(£/m)</label>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <select id="finishing" class="custom-select product-select">
                                                <option value="0" selected>Choose...</option>
                                                <?php
                                                /** @var \Classes\Objects\Product $item */
                                                foreach ($order_parts['finishing']->getItems() as $item) {
                                                    echo('<option value="' . $item->getId() . '">' . $item->getName() . ' [£' . $item->getPriceSell() . ']' . '</option>');
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div id="div-finishing-optional">
                                            <select id="finishing-optional" class="custom-select product-select">
                                                <option value="0" selected>Optional...</option>
                                                <?php
                                                /** @var \Classes\Objects\Product $item */
                                                foreach ($order_parts['finishing']->getItems() as $item) {
                                                    echo('<option value="' . $item->getId() . '">' . $item->getName() . ' [£' . $item->getPriceSell() . ']' . '</option>');
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Labour<br>(£30.00/hr)</label>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <input type="text" value="£0" class="form-control" id="labour" disabled="true">
                                        </div>
                                        <div>
                                            <input type="text" value="0 mins" class="form-control" id="labour-time" disabled="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Shipping<br>(£10.50)</label>
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
                                        <input type="text" value="£0" class="form-control" id="total-price" disabled="true">
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
                        <form id="new-customer-form" onsubmit="return checkNewCustomerForm()">
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
