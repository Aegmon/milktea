<?php

require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

require_once "../controllers/categories.controller.php";
require_once "../models/categories.model.php";

require_once "../controllers/ingredients.controller.php";
require_once "../models/ingredients.model.php";

class AjaxProducts {

    /*=============================================
    GENERATE CODE FROM ID CATEGORY
    =============================================*/    
    public $idCategory;

    public function ajaxCreateProductCode() {
        error_log("ajaxCreateProductCode called"); // Log for debugging
        $item = "idCategory";
        $value = $this->idCategory;
        $order = "id";

        $answer = controllerProducts::ctrShowProducts($item, $value, $order);
        echo json_encode($answer);
    }

    /*=============================================
    EDIT PRODUCT
    =============================================*/ 
    public $idProduct;
    public $getProducts;
    public $productName;

 public function ajaxEditProduct() {
    error_log("ajaxEditProduct called with idProduct: " . $this->idProduct); // Debug logging

    if ($this->getProducts === "ok") {
        error_log("Fetching all products");
        $item = null;
        $value = null;
        $order = "id";

        $answer = controllerProducts::ctrShowProducts($item, $value, $order);
        echo json_encode($answer);

    } elseif (isset($this->productName) && !empty($this->productName)) { // Updated condition
        error_log("Fetching product by name: " . $this->productName);
        $item = "description";
        $value = $this->productName;
        $order = "id";

        $answer = controllerProducts::ctrShowProducts($item, $value, $order);
        echo json_encode($answer);

    } else {
        error_log("Fetching product by ID: " . $this->idProduct);
        $item = "id";
        $value = $this->idProduct;
        $order = "id";

        $answer = controllerProducts::ctrShowProducts($item, $value, $order);
        echo json_encode($answer);
    }
}

    /*=============================================
    CREATE PRODUCT WITH INGREDIENTS
    =============================================*/ 
    public $productData; // Data for the new product

    public function ajaxCreateProductWithIngredients() {
        error_log("ajaxCreateProductWithIngredients called"); // Debug logging
        
        $productInfo = json_decode($this->productData, true);
        
        // Log the product data
        error_log("Product Data: " . print_r($productInfo, true));

        $productId = controllerProducts::ctrCreateIngredientProduct($productInfo); 
        
        if ($productId) {
            foreach ($productInfo['ingredients'] as $key => $ingredient) {
                $ingredientData = [
                    'product_id' => $productId,
                    'ingredient_id' => $ingredient['id'],
                    'size' => $productInfo['sizes'][$key]
                ];

                error_log("Inserting ingredient: " . print_r($ingredientData, true)); // Log ingredient data
                controllerProducts::ctrCreateIngredientProduct($ingredientData);
            }
            echo json_encode(["status" => "success", "productId" => $productId]);
        } else {
            error_log("Failed to create product"); // Log error
            echo json_encode(["status" => "error", "message" => "Failed to create product"]);
        }
    }
}

/*=============================================
GENERATE CODE FROM ID CATEGORY
=============================================*/    
if (isset($_POST["idCategory"])) {
    error_log("Received idCategory: " . $_POST["idCategory"]); // Log the POST data
    $productCode = new AjaxProducts();
    $productCode->idCategory = $_POST["idCategory"];
    $productCode->ajaxCreateProductCode();
}

/*=============================================
EDIT PRODUCT
=============================================*/ 
if (isset($_POST["idProduct"])) {
    error_log("Received idProduct: " . $_POST["idProduct"]); // Log the POST data
    $editProduct = new AjaxProducts();
    $editProduct->idProduct = $_POST["idProduct"];
    $editProduct->ajaxEditProduct();
}

/*=============================================
GET PRODUCTS
=============================================*/ 
if (isset($_POST["getProducts"])) {
    error_log("Received getProducts: " . $_POST["getProducts"]); // Log the POST data
    $getProducts = new AjaxProducts();
    $getProducts->getProducts = $_POST["getProducts"];
    $getProducts->ajaxEditProduct();
}

/*=============================================
GET PRODUCT NAME
=============================================*/ 
if (isset($_POST["productName"])) {
    error_log("Received productName: " . $_POST["productName"]); // Log the POST data
    $getProducts = new AjaxProducts();
    $getProducts->productName = $_POST["productName"];
    $getProducts->ajaxEditProduct();
}

/*=============================================
CREATE PRODUCT WITH INGREDIENTS
=============================================*/ 
if (isset($_POST["productData"])) {
    error_log("Received productData"); // Log that product data is received
    $createProduct = new AjaxProducts();
    $createProduct->productData = $_POST["productData"];
    $createProduct->ajaxCreateProductWithIngredients();
}
