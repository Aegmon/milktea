<?php

require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";
require_once "../controllers/customers.controller.php";
require_once "../models/customers.model.php";

class productsTableSales{

    /*=============================================
     SHOW PRODUCTS TABLE
    =============================================*/ 
    public function showProductsTableSales() {

        $item = null;
        $value = null;
        $order = "id";

        // Get the products from the controller
        $products = ControllerProducts::ctrShowProducts($item, $value, $order);

        // If no products, return empty data
        if (count($products) == 0) {
            $jsonData = '{"data":[]}';
            echo $jsonData;
            return;
        }

        // Organize products by name to consolidate rows with different sizes
        $productGroups = [];
        foreach ($products as $product) {
            $name = $product["description"];
            if (!isset($productGroups[$name])) {
                $productGroups[$name] = [];
            }
            $productGroups[$name][] = $product;
        }

        // Start building JSON data
        $jsonData = '{"data":[';

        // Loop through grouped products and build rows
        $index = 1;
        foreach ($productGroups as $name => $productGroup) {

            /*=============================================
            Image
            =============================================*/
            $image = "<img src='" . $productGroup[0]["image"] . "' width='40px'>";

            /*=============================================
            Size Buttons
            =============================================*/
            $sizeButtons = "";
            foreach ($productGroup as $product) {
                $sizeLabel = ($product["size"] == "Large") ? "L" : "S";
                $sizeButtons .= "<button class='btn btn-info btn-size' data-size='" . $product["size"] . "' idProduct='" . $product["id"] . "'>" . $sizeLabel . "</button> ";
            }

      

            /*=============================================
            ACTION BUTTON
            =============================================*/
            $buttons = "<div class='btn-group'><button class='btn btn-primary addProductSale recoverButton' idProduct='' data-name='" . $name . "'><i class='fa fa-plus'></i></button></div>";

            // Add product row to JSON
            $jsonData .= '[
                "' . ($index++) . '",
                "' . $image . '",
                "' . $name . '",
                "' . $sizeButtons . '",
                "' . $buttons . '"
            ],';
        }

        // Remove trailing comma and close the JSON array
        $jsonData = rtrim($jsonData, ',');
        $jsonData .= ']}';

        // Output the JSON data
        echo $jsonData;
    }
}

/*=============================================
ACTIVATE PRODUCTS TABLE
=============================================*/ 
$activateProductsSales = new productsTableSales();
$activateProductsSales->showProductsTableSales();
?>
