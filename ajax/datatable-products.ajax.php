<?php

require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

require_once "../controllers/categories.controller.php";
require_once "../models/categories.model.php";

class productsTable {

    /*=============================================
    SHOW PRODUCTS TABLE
    =============================================*/ 
public function showProductsTable() {

    $item = null;
    $value = null;
    $order = "id";

    // Check if category_id is set and not empty
    if (isset($_GET["category_id"]) && !empty($_GET["category_id"])) {
        $item = "idCategory";
        $value = $_GET["category_id"];
        
        // Log the category_id being retrieved
        error_log("Retrieving products for category_id: " . $value); // Log the category_id
    }

    try {
        // Manually create and execute the query
        $conn = Connection::connect();  // Assuming you have a Connection class to handle DB connection
        $stmt = null;
        
        if ($item != null) {
            // If category is provided, filter by category ID
            $stmt = $conn->prepare("SELECT * FROM products WHERE $item = :value ORDER BY $order DESC");
            $stmt->bindParam(':value', $value, PDO::PARAM_INT);  // Assuming idCategory is an integer
        } else {
            // If no category is provided, retrieve all products
            $stmt = $conn->prepare("SELECT * FROM products ORDER BY $order DESC");
        }

        // Execute the query
        $stmt->execute();
        
        // Fetch the results
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Log the products fetched
        error_log("Products fetched: " . print_r($products, true)); // Log all fetched products
        
        // Ensure products is an array before using count()
        if (!is_array($products) || count($products) === 0) {
            $jsonData = '{"data":[]}';
            echo $jsonData;
            return;
        }

        // Process the data and generate JSON (existing code logic)
        $jsonData = '{"data":[';
        for ($i = 0; $i < count($products); $i++) {
            // Your existing processing code to prepare the JSON output
            $image = "<img src='" . $products[$i]["image"] . "' width='40px'>";
                   /*=============================================
            We bring the category
            =============================================*/
            $categoryId = $products[$i]["idCategory"];
			    error_log("Retrieving products  category_id: " . $categoryId); // Log the category_id
            $categories = ControllerCategories::ctrShowCategories("id", $categoryId);

            
            if (isset($_GET["hiddenProfile"]) && $_GET["hiddenProfile"] == "Special") {
                $buttons = "<div class='btn-group'><button class='btn btn-primary btnEditProduct' idProduct='" . $products[$i]["id"] . "' data-toggle='modal' data-target='#modalEditProduct'><i class='fa fa-pencil'></i></button></div>";
            } else {
                $buttons = "<div class='btn-group'><button class='btn btn-success btnViewProduct' idProduct='" . $products[$i]["id"] . "' data-toggle='modal' data-target='#modalViewProduct'><i class='fa fa-eye'></i></button><button class='btn btn-primary btnEditProduct' idProduct='" . $products[$i]["id"] . "' data-toggle='modal' data-target='#modalEditProduct'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnDeleteProduct' idProduct='" . $products[$i]["id"] . "' code='" . $products[$i]["code"] . "' image='" . $products[$i]["image"] . "'><i class='fa fa-trash'></i></button></div>";
            }

            $jsonData .= '[
                "' . ($i + 1) . '",
                "' . $image . '",
                "' . $products[$i]["description"] . '",
                "' . (isset($categories["Category"]) ? $categories["Category"] : "Unknown") . '",
                "₱ ' . $products[$i]["sellingPrice"] . '",
                "' . $products[$i]["date"] . '",
                "' . $buttons . '"
            ],';
        }

        $jsonData = rtrim($jsonData, ',') . ']}';
        echo $jsonData;

    } catch (PDOException $e) {
        error_log("Error executing query: " . $e->getMessage());  // Log any potential errors
        echo '{"data":[]}';  // Return an empty data structure in case of error
    }
}

}

/*=============================================
ACTIVATE PRODUCTS TABLE
=============================================*/ 
$activateProducts = new productsTable();
$activateProducts->showProductsTable();
