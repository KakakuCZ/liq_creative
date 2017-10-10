<?php
require_once './includes/head.php';
?>

<!DOCTYPE html>
<html>
    <?php includeHead(); ?>
    <body>
        <div class="container">
            <div>
                <table class="table table-striped table-sm">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total price</th>
                            <th>Profit</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</td>
                            <td>Minarski Benjamin</td>
                            <td>10/10/2017 - 15:17</td>
                            <td>£123.45</td>
                            <td>£54.30</td>
                            <td><a href="viewSpecificOrder.php"><i class="material-icons">keyboard_arrow_right</i></a></td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>Rover Bob</td>
                            <td>05/07/2017 - 15:03</td>
                            <td>£321.00</td>
                            <td>£78.30</td>
                            <td><a href="viewSpecificOrder.php"><i class="material-icons">keyboard_arrow_right</i></a></td>
                        </tr>
                        <tr>
                            <td scope="row">3</td>
                            <td>Torrisi Pino</td>
                            <td>09/10/2017 - 10:04</td>
                            <td>£543.21</td>
                            <td>£45.78</td>
                            <td><a href="viewSpecificOrder.php"><i class="material-icons">keyboard_arrow_right</i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
