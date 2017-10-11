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
        <div class="container-fluid">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div id="menu">
                        <div class="card card-body">
                            <form id="new-order-form" onsubmit="return checkOrderForm()">
                                <div class="row">
                                    <div class="col-5">
                                        <label>Customer</label>
                                    </div>
                                    <div class="col">
                                        <select id="customer" class="custom-select">
                                            <option value="0" selected>Choose...</option>
                                            <option value="new-customer">Add customer</option>
                                            <option value="null" disabled="true"></option>
                                            <?php
                                            /** @var \Classes\Objects\Customer $customer */
                                            foreach ($customers as $customer) {
                                                echo('<option value="' . $customer->getId() . '">' . $customer->getFullname() . '</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="options">
                                    <div class="dropdown-divider"></div>
                                    <div class="row">
                                        <div class="col-5">
                                            <label>Options</label>
                                        </div>
                                        <div class="col">
                                            <select id="options-select" class="custom-select">
                                                <option value="0" selected>Choose...</option>
                                                <option value="new-order">New order</option>
                                                <option value="null" disabled="true"></option>
                                                <?php
                                                /** @var \Classes\Objects\Customer $customer */
                                                for ($i = 0; $i < 5; $i++) {
                                                    echo("<option value='" . ($i + 1) . "'>#" . ($i + 1) . " order</option>");
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="new-order-screen">
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
                                            <input type="text" value="£0.00" class="form-control" id="ink" disabled="true">
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
                                                <input type="text" value="£0.00" class="form-control" id="labour" disabled="true">
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
                                            <input type="text" value="£0.00" class="form-control" id="total-price" disabled="true">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="view-orders">
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Base media<br>(£/m)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" value="Something" class="form-control" id="rev-basemedia" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Print media<br>(£/m)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" value="Something" class="form-control" id="rev-printmedia" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Ink<br>(£14.00/m<sup>2</sup>)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" value="£0.00" class="form-control" id="rev-ink" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Finishing<br>(£/m)</label>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <input type="text" value="Something" class="form-control" id="rev-finishing" disabled="true">
                                        </div>
                                        <div>
                                            <input type="text" value="Something" class="form-control" id="rev-finishing-opt" disabled="true">
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
                                            <input type="text" value="£0.00" class="form-control" id="rev-labour" disabled="true">
                                        </div>
                                        <div>
                                            <input type="text" value="0 mins" class="form-control" id="rev-labour-time" disabled="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Shipping<br>(£10.50)</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" value="Something" class="form-control" id="rev-shipping" disabled="true">
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Total price</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" value="£0.00" class="form-control" id="rev-total-price" disabled="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="add-customer-form" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="new-customer-form" method="get" action="createNewUser.php">
                            <div class="modal-header">
                                <h5 class="modal-title">Add customer</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col">
                                        <input name="firstname" type="text" class="form-control" placeholder="First name" id="first-name">
                                    </div>
                                    <div class="col">
                                        <input name="lastname" type="text" class="form-control" placeholder="Last name" id="last-name">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <input name="email" type="text" class="form-control" placeholder="Email" id="email">
                                        <p id="email-hint" class="form-hint">Email is not valid.</p>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <input name="phone_number" type="text" class="form-control" placeholder="Phone number" id="phone">
                                        <p id="phone-hint" class="form-hint">Phone number is not valid.</p>
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