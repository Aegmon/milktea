<?php

require_once "../../../controllers/sales.controller.php";
require_once "../../../models/sales.model.php";
require_once "../../../controllers/customers.controller.php";
require_once "../../../models/customers.model.php";
require_once "../../../controllers/users.controller.php";
require_once "../../../models/users.model.php";
require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

class printBill {
    public $code;

    public function getBillPrinting() {
        ob_start(); // Start output buffering

        // Fetch sale information
        $itemSale = "code";
        $valueSale = $this->code;
        $answerSale = ControllerSales::ctrShowSales($itemSale, $valueSale);

        $saledate = substr($answerSale["saledate"], 0, -8);
        $products = json_decode($answerSale["products"], true);
        $addons = json_decode($answerSale["addons"], true); // Assuming 'addons' is stored in JSON format
        $netPrice = number_format($answerSale["totalPrice"], 2);
        $tax = isset($answerSale["tax"]) ? number_format($answerSale["tax"], 2) : 0; // Set tax to 0 or retrieve it

        // Calculate total price including products and add-ons
        $totalProductsPrice = floatval($answerSale["totalPrice"]);
        $totalAddonsPrice = 0; // Initialize total add-ons price

        // Calculate total price for add-ons
        if (is_array($addons)) {
            foreach ($addons as $addon) {
                $totalAddonsPrice += floatval($addon["totalPrice"]); // Assuming totalPrice is in string format
            }
        }

        // Calculate final total price
        $finalTotalPrice = number_format($totalProductsPrice + $totalAddonsPrice, 2);

        // Fetch customer information
        $itemCustomer = "id";
        $valueCustomer = $answerSale["idCustomer"];
        $answerCustomer = ControllerCustomers::ctrShowCustomers($itemCustomer, $valueCustomer);

        // Fetch seller information
        $itemSeller = "id";
        $valueSeller = $answerSale["idSeller"];
        $answerSeller = ControllerUsers::ctrShowUsers($itemSeller, $valueSeller);

        // TCPDF setup
        require_once('tcpdf_include.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', 'A7');

        // Output sale and customer details
        $block1 = <<<EOF
        <table style="font-size:7px; text-align:center">
        <tr><td style="width:160px;">
            <div>Date: $saledate<br><br>Taipei Royal Tea<br>Address: Tarlac<br>Contact: 300 786 52 49<br>Invoice: $valueSale<br></div>
        </td></tr></table>
        EOF;
        $pdf->writeHTML($block1, false, false, false, false, '');

        // Output products
        if (is_array($products)) {
            foreach ($products as $item) {
                $unitValue = number_format($item["price"], 2);
                $itemTotalPrice = number_format($item["totalPrice"], 2);
                $block2 = <<<EOF
                <table style="font-size:7px;">
                <tr><td style="width:160px; text-align:left"> $item[description] </td></tr>
                <tr><td style="width:160px; text-align:right"> Php.  $unitValue Items * $item[quantity] = Php.  $itemTotalPrice<br></td></tr>
                </table>
                EOF;
                $pdf->writeHTML($block2, false, false, false, false, '');
            }
        }

        // Output add-ons
        if (is_array($addons)) {
            foreach ($addons as $addon) {
                $addonPrice = number_format($addon["price"], 2);
                $addonQuantity = $addon["quantity"];
                $totalAddonPrice = number_format($addon["totalPrice"], 2);
                $addonName = htmlspecialchars($addon["addon"]); // Get the add-on name safely
                $blockAddons = <<<EOF
                <table style="font-size:7px;">
                <tr><td style="width:160px; text-align:left"> Add-on: $addonName </td></tr>
                <tr><td style="width:160px; text-align:right"> Php.  $addonPrice Items * $addonQuantity = Php.  $totalAddonPrice<br></td></tr>
                </table>
                EOF;
                $pdf->writeHTML($blockAddons, false, false, false, false, '');
            }
        }

        // Output total price and thank you note
        $block3 = <<<EOF
        <table style="font-size:7px; text-align:right">
        <tr><td style="width:80px;"> NET: </td><td style="width:80px;"> Php.  $netPrice</td></tr>
        <tr><td style="width:160px;"> --------------------------</td></tr>
        <tr><td style="width:80px;"> TOTAL: </td><td style="width:80px;"> Php.  $netPrice</td></tr>
        <tr><td style="width:160px;"><br><br>Thank you for your purchase!</td></tr></table>
        EOF;
        $pdf->writeHTML($block3, false, false, false, false, '');

        // Output PDF
        ob_end_clean(); // Clear output buffer before PDF generation
        $pdf->Output('bill.pdf');
    }
}

$bill = new printBill();
$bill->code = $_GET["code"];
$bill->getBillPrinting();

?>
