<?php
require_once './includes/head.php';
require_once __DIR__ . '/lib/mpdf/vendor/autoload.php';

use Classes\Exceptions\WrongOrderIDException;

global $formManager;
global $order;
global $total;

$formManager = new \Classes\FormManager();
$order = $formManager->getObjOrderByID($_GET["orderID"]);

if (!$order->isInitialized()) {
    throw new WrongOrderIDException("Wrong order ID.");
}

$mpdf = new \Mpdf\Mpdf();
$style = file_get_contents('./css/pdf_style.css');
$mpdf->WriteHTML($style, 1);

$mpdf->AddPageByArray(array(
    'orientation' => 'L',
    'mgl' => '7',
    'mgr' => '7',
    'mgt' => '7',
    'mgb' => '7'
));
$mpdf->SetTitle($order->getOrderName());
$mpdf->SetColumns(2, 'J', 10);

function createTotalTable() {
    global $order;
    global $total;
    $total = 0;
    for ($i = 0; $i < 4; $i++) {
        if ($i == 0) {
            echo '<tr>';
            if ($order->getBaseMedia() != null) {
                echo '<td>' . number_format($order->getBaseMediaPrice(), 2, '.', ',') . '</td>';
            } else {
                echo '<td>---</td>';
            }
            if ($order->getPrintMedia() != null) {
                echo '<td>' . number_format($order->getPrintMediaPrice(), 2, '.', ',') . '</td>';
            } else {
                echo '<td>---</td>';
            }
            if ($order->getInk()) {
                echo '<td>' . number_format($order->getInkPrice(), 2, '.', ',') . '</td>';
            } else {
                echo '<td>---</td>';
            }
            echo '<td>' . number_format($order->getFinishingPrice(), 2, '.', ',') . '</td>';
            echo '<td>' . number_format($order->getLabourPrice(), 2, '.', ',') . '</td>';
            if ($order->getShipping()) {
                echo '<td>' . number_format($order->getShippingPrice(), 2, '.', ',') . '</td>';
            } else {
                echo '<td>---</td>';
            }
            echo '<td>£' . number_format($order->getTotalPrice(), 2, '.', ',') . '</td>';
            echo '</tr>';
            $total += $order->getTotalPrice();
        } else {
            echo '<tr>';
            echo '<td></td>';
            echo '<td class="sign">+</td>';
            echo '<td></td>';
            echo '<td class="sign">+</td>';
            echo '<td></td>';
            echo '<td class="sign">+</td>';
            echo '<td></td>';
            echo '<td class="sign">+</td>';
            echo '<td></td>';
            echo '<td class="sign">+</td>';
            echo '<td></td>';
            echo '<td class="sign">+</td>';
            echo '<td></td>';
            echo '</tr>';
        }
    }
}

function columnRow($typeID, $type = null, $order_parts, $itemsPerCol) {
    if ($typeID != 3) {
        for ($i = 0; $i < $itemsPerCol; $i++) {
            echo "<tr>";
            if ($type != null && $order_parts[$i]->getId() == $type->getId()) {
                echo "<td class='first-col' style='background-color: #ccff00'>" . $order_parts[$i]->getName() . "</td>";
                echo "<td class='second-col bg-gray' style='background-color: #b8e600'>£" . number_format($order_parts[$i]->getPriceSell(), 2, '.', ',') . "</td>";
            } else {
                echo "<td class='first-col'>" . $order_parts[$i]->getName() . "</td>";
                echo "<td class='second-col bg-gray'>£" . number_format($order_parts[$i]->getPriceSell(), 2, '.', ',') . "</td>";
            }
            echo "<td class='void-col'></td>";
            if ($type != null && $order_parts[$i + $itemsPerCol]->getId() == $type->getId()) {
                echo "<td class='first-col' style='background-color: #ccff00'>" . $order_parts[$i + $itemsPerCol]->getName() . "</td>";
                echo "<td class='second-col bg-gray' style='background-color: #b8e600'>£" . number_format($order_parts[$i + $itemsPerCol]->getPriceSell(), 2, '.', ',') . "</td>";
            } else {
                echo "<td class='first-col'>" . $order_parts[$i + $itemsPerCol]->getName() . "</td>";
                echo "<td class='second-col bg-gray'>£" . number_format($order_parts[$i + $itemsPerCol]->getPriceSell(), 2, '.', ',') . "</td>";
            }
            echo "</tr>";
        }
    } else {
        for ($i = 0; $i < $itemsPerCol; $i++) {
            echo "<tr>";
            if ($order_parts[$i]->getId() == $type[0]->getId() || ($type[1] != null && $order_parts[$i]->getId() == $type[1]->getId())) {
                echo "<td class='first-col' style='background-color: #ccff00'>" . $order_parts[$i]->getName() . "</td>";
                echo "<td class='second-col bg-gray' style='background-color: #b8e600'>£" . number_format($order_parts[$i]->getPriceSell(), 2, '.', ',') . "</td>";
            } else {
                echo "<td class='first-col'>" . $order_parts[$i]->getName() . "</td>";
                echo "<td class='second-col bg-gray'>£" . number_format($order_parts[$i]->getPriceSell(), 2, '.', ',') . "</td>";
            }
            echo "<td class='void-col'></td>";
            if ($order_parts[$i + $itemsPerCol]->getId() == $type[0]->getId() || ($type[1] != null && $order_parts[$i + $itemsPerCol]->getId() == $type[1]->getId())) {
                echo "<td class='first-col' style='background-color: #ccff00'>" . $order_parts[$i + $itemsPerCol]->getName() . "</td>";
                echo "<td class='second-col bg-gray' style='background-color: #b8e600'>£" . number_format($order_parts[$i + $itemsPerCol]->getPriceSell(), 2, '.', ',') . "</td>";
            } else {
                echo "<td class='first-col'>" . $order_parts[$i + $itemsPerCol]->getName() . "</td>";
                echo "<td class='second-col bg-gray'>£" . number_format($order_parts[$i + $itemsPerCol]->getPriceSell(), 2, '.', ',') . "</td>";
            }
            echo "</tr>";
        }
    }
}

function createTable($typeID) {
    global $formManager;
    global $order;
    $order_parts = $formManager->getProductsBytType($typeID);
    $itemsPerCol = count($order_parts) / 2;
    switch ($typeID) {
        case 1:
            columnRow($typeID, $order->getBaseMedia(), $order_parts, $itemsPerCol);
            break;
        case 2:
            columnRow($typeID, $order->getPrintMedia(), $order_parts, $itemsPerCol);
            break;
        case 3:
            columnRow($typeID, $order->getFinishing(), $order_parts, $itemsPerCol);
            break;
    }
}

function addBgColor($var, $colorCode) {
    if ($var) {
        echo "style='background-color: " . $colorCode . "'";
    }
}

function getSizeMultiplier() {
    global $order;
    if ($order->getRoleMetres() == $order->getLength()) {
        $multiplier = floor(145 / $order->getLength());
    } else {
        $multiplier = floor(425 / $order->getWidth());
    }
    while (($order->getLength() * $multiplier) > 145 || ($order->getWidth() * $multiplier) > 425) {
        $multiplier--;
    }
    return $multiplier;
}

// first column
ob_start();
?>
<div class="box" id="title-box"><?php echo $order->getOrderName() ?></div>
<div class="box" id="calc-box">
    <div id="sketch">
        <table id="sketch-table">
            <tr>
                <td id="print" style="width: <?php echo ($order->getWidth() * getSizeMultiplier()) . "px" ?>; height: <?php echo ($order->getLength() * getSizeMultiplier()) . "px" ?>"></td>
                <td class="print-size" id="print-length"><?php echo ($order->getLength() * 1000) . "mm" ?></td>
            </tr>
            <tr>
                <td class="print-size" id="print-width"><?php echo ($order->getWidth() * 1000) . "mm" ?></td>
            </tr>
        </table>
    </div>
    <div id="calc">
        <ol>
            <li>
                BASE MEDIA
                <?php if ($order->getBaseMedia() != null) { ?>
                    <ul>
                        <li><?php echo $order->countRoleMetres() . "m x £" . $order->getBaseMedia()->getPriceSell() . " = £" . number_format($order->getBaseMediaPrice(), 2, '.', ',') ?></li>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <li>No</li>
                    </ul>
                <?php } ?>
            </li>
            <li class="li-title">
                PRINT MEDIA
                <?php if ($order->getPrintMedia() != null) { ?>
                    <ul>
                        <li><?php echo "(" . $order->countRoleMetres() . "m + 0.25m) x £" . $order->getPrintMedia()->getPriceSell() . " = £" . number_format($order->getPrintMediaPrice(), 2, '.', ',') ?></li>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <li>No</li>
                    </ul>
                <?php } ?>
            <li class="li-title">
                INK
                <?php if ($order->getInk()) { ?>
                    <ul>
                        <li><?php echo $order->getSquareMetres() . "m<sup>2</sup> x £" . $order::PRICE_INK . " = £" . number_format($order->getInkPrice(), 2, '.', ',') ?></li>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <li>No</li>
                    </ul>
                <?php } ?>
            <li class="li-title">
                FINISHING
                <?php
                foreach ($order->getFinishing() as $finishing) {
                    if ($finishing == null) {
                        continue;
                    }
                    if ($finishing->getName() == 'Banner Eyelet Set (SPW)') {
                        ?>
                        <ul>
                            <li><?php echo $order->getNumberOfEyelet() . " eyelets x £" . $finishing->getPriceSell() . " = £" . number_format($order->getNumberOfEyelet() * $finishing->getPriceSell(), 2, '.', ',') ?></li>
                        </ul>
                        <?php
                        continue;
                    }
                    ?>
                    <ul>
                        <li><?php echo "(" . $order->countRoleMetres() . "m + 0.25m) x £" . $finishing->getPriceSell() . " = £" . number_format($finishing->getPriceSell() * ($order->countRoleMetres() + 0.25), 2, '.', ',') ?></li>
                    </ul>
                <?php } ?>
            </li>
            <li class="li-title">
                LABOUR
                <ul>
                    <li><?php echo $order->getHours() . "hr x £" . $order::PRICE_LABOUR . " = £" . number_format($order->getLabourPrice(), 2, '.', ',') ?></li>
                </ul>
            </li>
            <li class="li-title">
                SHIPPING
                <?php if ($order->getShipping()) { ?>
                    <ul>
                        <li><?php echo "Yes = £" . number_format($order::PRICE_SUPPLIER_SHIPING, 2, '.', ',') ?></li>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <li>No</li>
                    </ul>
                <?php } ?>
            </li>
        </ol>
    </div>
</div>
<?php
$firstColumnHtml = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($firstColumnHtml);

// second column
$mpdf->AddColumn();
ob_start();
?>
<div class="type">
    <div class="box type-title">1. BASE MEDIA (£/m)</div>
    <table>
        <?php createTable(1) ?>
    </table>
</div>
<div class="type">
    <div class="box type-title">2. PRINT MEDIA (£/m)</div>
    <table>
        <?php createTable(2) ?>
    </table>
</div>
<div class="type">
    <table class="tb-title">
        <tr>
            <td class="type-title" <?php addBgColor($order->getInk(), "#ccff00") ?>>3. INK (£/m<sup>2</sup>)</td>
            <td class="bg-gray tb-second-col" <?php addBgColor($order->getInk(), "#b8e600") ?>><?php echo "£" . number_format($order::PRICE_INK, 2, '.', ',') ?></td>
        </tr>
    </table>
</div>
<div class="type">
    <div class="box type-title">4. FINISHING (£/m)</div>
    <table>
        <?php createTable(3) ?>
    </table>
</div>
<div class="type">
    <table class="tb-title">
        <tr>
            <td class="type-title">5. LABOUR (£/hr)</td>
            <td class="bg-gray tb-second-col"><?php echo "£" . number_format($order::PRICE_LABOUR, 2, '.', ',') ?></td>
        </tr>
    </table>
</div>
<div class="type">
    <table class="tb-title">
        <tr>
            <td class="type-title" <?php addBgColor($order->getShipping(), "#ccff00") ?>>6. SUPPLIER SHIPPING</td>
            <td class="bg-gray tb-second-col" <?php addBgColor($order->getShipping(), "#b8e600") ?>><?php echo "£" . number_format($order::PRICE_SUPPLIER_SHIPING, 2, '.', ',') ?></td>
        </tr>
    </table>
</div>
<div class="type">
    <table id="tb-total">
        <tr>
            <td class="tb-number bg-gray">1</td>
            <td class="sign" rowspan="2">+</td>
            <td class="tb-number bg-gray">2</td>
            <td class="sign" rowspan="2">+</td>
            <td class="tb-number bg-gray">3</td>
            <td class="sign" rowspan="2">+</td>
            <td class="tb-number bg-gray">4</td>
            <td class="sign" rowspan="2">+</td>
            <td class="tb-number bg-gray">5</td>
            <td class="sign" rowspan="2">+</td>
            <td class="tb-number bg-gray">6</td>
            <td class="sign" rowspan="2">=</td>
            <td class="tb-number bg-black">TOTAL</td>
        </tr>
        <?php createTotalTable() ?>
        <tr>
            <td class="bg-black" colspan="12">=</td>
            <td><?php echo "£" . number_format($total, 2, '.', ',') ?></td>
        </tr>
    </table>
</div>
<?php
$secondColumnHtml = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($secondColumnHtml);

$newOrderName = strtolower($order->getOrderName());
$newOrderName = str_replace(" ", "_", $newOrderName);
$mpdf->Output($newOrderName . ".pdf", \Mpdf\Output\Destination::INLINE);
