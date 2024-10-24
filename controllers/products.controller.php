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


static public function ctrShowProductDetails($productId) {
    $pdo = Connection::connect();

    $query = "
        SELECT 
            id, 
            description, 
            sellingPrice, 
            date
        FROM products 
        WHERE id = :product_id"; // Filter by product ID

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC); // Return product details
}

static public function ctrShowProductsIngredients($item, $value, $order) {
    $pdo = Connection::connect();

    $query = "
        SELECT 
            pi.ingredient_id, 
            i.ingredient, 
            pi.size AS ingredient_needed, 
            i.size AS measurement,
            i.quantity AS ingredient_quantity
        FROM productsingredients pi
        INNER JOIN products p ON pi.product_id = p.id
        INNER JOIN ingredients i ON pi.ingredient_id = i.id
        WHERE p.id = :product_id"; // Filter by product ID

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $value, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the list of ingredients
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

                // Set the new image dimensions and folder
                list($width, $height) = getimagesize($_FILES["newProdPhoto"]["tmp_name"]);
                $newWidth = 500;
                $newHeight = 500;
                $folder = "views/img/products/" . $_POST["newDescription"];
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
                "description" => $_POST["newDescription"],
                "image" => $route,
                "sellingPrice" => $_POST["newSellingPrice"]
            );

            try {
                // Get the database connection and prepare the SQL statement
                $db = Connection::connect();
                $stmt = $db->prepare("INSERT INTO products (idCategory, description, image, sellingPrice) 
                                      VALUES (:idCategory, :description, :image, :sellingPrice)");

                // Bind parameters
                $stmt->bindParam(":idCategory", $data["idCategory"], PDO::PARAM_INT);
                $stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
                $stmt->bindParam(":image", $data["image"], PDO::PARAM_STR);
                $stmt->bindParam(":sellingPrice", $data["sellingPrice"], PDO::PARAM_STR);

                // Execute the statement
                if ($stmt->execute()) {
                    $productId = $db->lastInsertId(); // Get the last inserted ID
                    error_log("Product inserted with ID: $productId");

                    // Add ingredients and sizes for the product
                    foreach ($_POST["ingredients"] as $index => $ingredientId) {
                        $size = isset($_POST["sizes"][$index]) ? $_POST["sizes"][$index] : null;
                        error_log("Ingredient ID: $ingredientId, Size: $size, Product ID: $productId");

                        // Prepare ingredient data
                        $ingredientData = array(
                            "productId" => $productId,
                            "ingredientId" => $ingredientId,
                            "size" => $size
                        );

                        // Insert the ingredient data
                        ProductsModel::mdlAddProductIngredient("productsingredients", $ingredientData);
                    }
                    self::showAlert("Product added successfully!", "success");
                } else {
                    error_log("Error executing product insertion query: " . implode(" ", $stmt->errorInfo()));
                    self::showAlert("Error adding product!");
                }
            } catch (Exception $e) {
                error_log("Exception caught: " . $e->getMessage());
                self::showAlert("Error adding product due to an exception.");
            } finally {
                // Close the statement
                $stmt = null;
            }
        } else {
            self::showAlert("Invalid input data!");
        }
    }
}



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
                    $folder = "views/img/products/" . $_POST["editProductName"];
                    mkdir($folder, 0755);

                    /*=============================================
                    APPLY PHP FUNCTIONS ACCORDING TO IMAGE FORMAT
                    =============================================*/
                    if ($_FILES["editProductImage"]["type"] == "image/jpeg") {
                        $random = mt_rand(100, 999);
                        $route = "views/img/products/" . $_POST["editProductName"] . "/" . $random . ".jpg";

                        $origin = imagecreatefromjpeg($_FILES["editProductImage"]["tmp_name"]);
                        $destiny = imagecreatetruecolor($newWidth, $newHeight);
                        imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagejpeg($destiny, $route);
                    }

                    if ($_FILES["editProductImage"]["type"] == "image/png") {
                        $random = mt_rand(100, 999);
                        $route = "views/img/products/" . $_POST["editProductName"] . "/" . $random . ".png";

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

   	/*=============================================
	SHOW ADDING OF THE SALES
	=============================================*/

	static public function ctrShowAddingOfTheSales(){

		$table = "products";

		$answer = ProductsModel::mdlShowAddingOfTheSales($table);

		return $answer;

	}
}
