<?php

class controllerProducts {

    /*=============================================
    SHOW PRODUCTS
    =============================================*/
    static public function ctrShowProducts($item, $value, $order) {
        $table = "products";
        $answer = ProductsModel::mdlShowProducts($table, $item, $value, $order);
        return $answer;
    }


        static public function ctrShowProductsIngredients($item, $value, $order) {
        $table = "productsingredients";
        $answer = ProductsModel::mdlShowProductsWithIngredients($table, $item, $value, $order);
        return $answer;
    }
    /*=============================================
    CREATE PRODUCTS
    =============================================*/
static public function ctrCreateIngredientProduct() {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate input fields
        if (
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["newDescription"]) &&
            preg_match('/^[0-9]+$/', $_POST["newStock"]) &&
            preg_match('/^[0-9]+$/', $_POST["newSellingPrice"]) &&
            !empty($_POST["ingredients"]) &&
            !empty($_POST["sizes"])
        ) {
            // Set default image route and allowed file types
            $route = "views/img/products/default/anonymous.png"; 
            $allowedFileTypes = ['image/jpeg', 'image/png']; 
            $maxFileSize = 2 * 1024 * 1024; // Maximum file size (2MB)

            // Handle image upload if present
            if (!empty($_FILES["newProdPhoto"]["tmp_name"])) {
                // Validate the file type
                if (!in_array($_FILES["newProdPhoto"]["type"], $allowedFileTypes)) {
                    self::showAlert("Invalid file type! Only JPG and PNG files are allowed.");
                    return; // Exit the function if the file type is invalid
                }

                // Validate the file size
                if ($_FILES["newProdPhoto"]["size"] > $maxFileSize) {
                    self::showAlert("File size exceeds 2MB!");
                    return; // Exit the function if the file size is too large
                }

                list($width, $height) = getimagesize($_FILES["newProdPhoto"]["tmp_name"]);
                $newWidth = 500;
                $newHeight = 500;

                // Create folder to save the picture
                $folder = "views/img/products/" . $_POST["newCode"];
                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                // Process and save the image
                $random = mt_rand(100, 999);
                $route = "$folder/$random." . pathinfo($_FILES["newProdPhoto"]["name"], PATHINFO_EXTENSION);
                $origin = ($_FILES["newProdPhoto"]["type"] == "image/jpeg") ? 
                    imagecreatefromjpeg($_FILES["newProdPhoto"]["tmp_name"]) : 
                    imagecreatefrompng($_FILES["newProdPhoto"]["tmp_name"]);
                
                $destiny = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                ($_FILES["newProdPhoto"]["type"] == "image/jpeg") ? imagejpeg($destiny, $route) : imagepng($destiny, $route);
            }

            // Prepare product data for database insertion
            $data = array(
                "idCategory" => $_POST["newCategory"],
                "code" => $_POST["newCode"],
                "description" => $_POST["newDescription"],
                "image" => $route,
                "stock" => $_POST["newStock"],
                "sellingPrice" => $_POST["newSellingPrice"]
            );

         
            $answer = ProductsModel::mdlAddProduct("products", $data);

            if ($answer["status"] === "ok") { 
             
                $productId = $_POST["newCode"];
                error_log("Last Inserted Product ID: " . $productId);

             
                foreach ($_POST["ingredients"] as $index => $ingredientId) {
                    $size = isset($_POST["sizes"][$index]) ? $_POST["sizes"][$index] : null; 
                    
              
                    error_log("Ingredient ID: $ingredientId, Size: $size, Product ID: $productId"); 
                    $ingredientData = array(
                        "productId" => $productId,
                        "ingredientId" => $ingredientId,
                        "size" => $size
                    );

                    ProductsModel::mdlAddProductIngredient("productsingredients", $ingredientData); 
                
                }
            } else {
                error_log("Error adding product: " . $answer["errorInfo"]); // Log any errors
                self::showAlert("Error adding product!"); // Show an alert for the error
            }
        } else {
            self::showAlert("Invalid input data!"); // Show alert for invalid input
        }
    }
}
// Helper method to show alerts
private static function showAlert($message, $type = "error") {
    echo '<script>
        swal({
              type: "' . $type . '",
              title: "' . $message . '",
              showConfirmButton: true,
              confirmButtonText: "Close"
              }).then(function(result){
                        if (result.value) {
                            window.location = "products";
                        }
                    })
    </script>';
}



    /*=============================================
    EDIT PRODUCT
    =============================================*/
    static public function ctrEditProduct() {
        if (isset($_POST["editProduct"])) {
            // Validate input fields
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editProductName"])) {
                $route = $_POST["currentImage"];

                // Validate the uploaded file if a new one is being uploaded
                if (isset($_FILES["editProductImage"]["tmp_name"]) && !empty($_FILES["editProductImage"]["tmp_name"])) {
                    $allowedFileTypes = ['image/jpeg', 'image/png']; // Allowed file types
                    $maxFileSize = 2 * 1024 * 1024; // Maximum file size (2MB)

                    // Validate the file type
                    if (!in_array($_FILES["editProductImage"]["type"], $allowedFileTypes)) {
                        echo '<script>
                            swal({
                                  type: "error",
                                  title: "Invalid file type! Only JPG and PNG files are allowed.",
                                  showConfirmButton: true,
                                  confirmButtonText: "Close"
                                  }).then(function(result){
                                            if (result.value) {
                                                window.location = "products";
                                            }
                                        })
                        </script>';
                        return; // Exit the function if the file type is invalid
                    }

                    // Validate the file size
                    if ($_FILES["editProductImage"]["size"] > $maxFileSize) {
                        echo '<script>
                            swal({
                                  type: "error",
                                  title: "File size exceeds 2MB!",
                                  showConfirmButton: true,
                                  confirmButtonText: "Close"
                                  }).then(function(result){
                                            if (result.value) {
                                                window.location = "products";
                                            }
                                        })
                        </script>';
                        return; // Exit the function if the file size is too large
                    }

                    list($width, $height) = getimagesize($_FILES["editProductImage"]["tmp_name"]);
                    $newWidth = 500;
                    $newHeight = 500;

                    /*=============================================
                    CREATE FOLDER TO SAVE THE PICTURE
                    =============================================*/
                    $folder = "views/img/products/" . $_POST["editProductCode"];
                    mkdir($folder, 0755);

                    /*=============================================
                    APPLY PHP FUNCTIONS ACCORDING TO IMAGE FORMAT
                    =============================================*/
                    if ($_FILES["editProductImage"]["type"] == "image/jpeg") {
                        $random = mt_rand(100, 999);
                        $route = "views/img/products/" . $_POST["editProductCode"] . "/" . $random . ".jpg";

                        $origin = imagecreatefromjpeg($_FILES["editProductImage"]["tmp_name"]);
                        $destiny = imagecreatetruecolor($newWidth, $newHeight);
                        imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagejpeg($destiny, $route);
                    }

                    if ($_FILES["editProductImage"]["type"] == "image/png") {
                        $random = mt_rand(100, 999);
                        $route = "views/img/products/" . $_POST["editProductCode"] . "/" . $random . ".png";

                        $origin = imagecreatefrompng($_FILES["editProductImage"]["tmp_name"]);
                        $destiny = imagecreatetruecolor($newWidth, $newHeight);
                        imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagepng($destiny, $route);
                    }
                }

                /*=============================================
                EDIT PRODUCT
                =============================================*/
                $data = array(
                    "idCategory" => $_POST["editProductCategory"],
                    "code" => $_POST["editProductCode"],
                    "description" => $_POST["editProductName"],
                    "image" => $route,
                    "stock" => $_POST["editProductStock"],
                    "sellingPrice" => $_POST["editProductPrice"]
                );

                $answer = ProductsModel::mdlEditProduct("products", $data);

                if ($answer == "ok") {
                    echo '<script>
                        swal({
                              type: "success",
                              title: "Product has been successfully edited!",
                              showConfirmButton: true,
                              confirmButtonText: "Close"
                              }).then(function(result){
                                        if (result.value) {
                                            window.location = "products";
                                        }
                                    })
                    </script>';
                }
            }
        }
    }

    /*=============================================
    DELETE PRODUCT
    =============================================*/
    static public function ctrDeleteProduct() {
        if (isset($_GET["idProduct"])) {
            $data = $_GET["idProduct"];
            $answer = ProductsModel::mdlDeleteProduct("products", $data);

            if ($answer == "ok") {
                echo '<script>
                    swal({
                          type: "success",
                          title: "Product has been successfully deleted!",
                          showConfirmButton: true,
                          confirmButtonText: "Close"
                          }).then(function(result){
                                    if (result.value) {
                                        window.location = "products";
                                    }
                                })
                </script>';
            }
        }
    }

   
}
