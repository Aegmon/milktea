<?php

require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

require_once "../controllers/categories.controller.php";
require_once "../models/categories.model.php";

require_once "../controllers/ingredients.controller.php";
require_once "../models/ingredient.model.php";

class AjaxProducts {

    /*=============================================
    GENERATE CODE FROM ID CATEGORY
    =============================================*/    

    public $idCategory;

    public function ajaxCreateProductCode() {
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
        if ($this->getProducts === "ok") {
            $item = null;
            $value = null;
            $order = "id";

            $answer = controllerProducts::ctrShowProducts($item, $value, $order);
            echo json_encode($answer);

        } elseif ($this->productName !== "") {
            $item = "description";
            $value = $this->productName;
            $order = "id";

            $answer = controllerProducts::ctrShowProducts($item, $value, $order);
            echo json_encode($answer);

        } else {
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
        // Extract product data
        $productInfo = json_decode($this->productData, true);
        
        // Create the product
        $productId = controllerProducts::ctrCreateIngredientProduct($productInfo); 
        
        if ($productId) {
            // Insert ingredients for the product
            foreach ($productInfo['ingredients'] as $key => $ingredient) {
                $ingredientData = [
                    'product_id' => $productId,
                    'ingredient_id' => $ingredient['id'],
                    'size' => $productInfo['sizes'][$key]
                ];
                
                // Insert each ingredient
                controllerProducts::ctrCreateIngredientProduct($ingredientData);
            }
            echo json_encode(["status" => "success", "productId" => $productId]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to create product"]);
        }
    }
}

/*=============================================
GENERATE CODE FROM ID CATEGORY
=============================================*/    

if (isset($_POST["idCategory"])) {
    $productCode = new AjaxProducts();
    $productCode->idCategory = $_POST["idCategory"];
    $productCode->ajaxCreateProductCode();
}

/*=============================================
EDIT PRODUCT
=============================================*/ 

if (isset($_POST["idProduct"])) {
    $editProduct = new AjaxProducts();
    $editProduct->idProduct = $_POST["idProduct"];
    $editProduct->ajaxEditProduct();
}

/*=============================================
GET PRODUCTS
=============================================*/ 

if (isset($_POST["getProducts"])) {
    $getProducts = new AjaxProducts();
    $getProducts->getProducts = $_POST["getProducts"];
    $getProducts->ajaxEditProduct();
}

/*=============================================
GET PRODUCT NAME
=============================================*/ 

if (isset($_POST["productName"])) {
    $getProducts = new AjaxProducts();
    $getProducts->productName = $_POST["productName"];
    $getProducts->ajaxEditProduct();
}

/*=============================================
CREATE PRODUCT WITH INGREDIENTS
=============================================*/ 

if (isset($_POST["productData"])) {
    $createProduct = new AjaxProducts();
    $createProduct->productData = $_POST["productData"];
    $createProduct->ajaxCreateProductWithIngredients();
}
