<?php

require_once 'connection.php';

class ProductsModel {
     
    /*=============================================
    SHOWING PRODUCTS
    =============================================*/

    static public function mdlShowProducts($table, $item, $value, $order) {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item ORDER BY id DESC");
            $stmt->bindParam(":".$item, $value, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
                        error_log("Fetching products from table '$table' where $item = '$value'"); // Log the query information
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $order DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

       static public function mdlShowProductsbydate($table, $item, $value, $order, $dateQuery) {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item AND $dateQuery ORDER BY $order DESC");
            $stmt->bindParam(":".$item, $value, PDO::PARAM_STR);
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $dateQuery ORDER BY $order DESC");
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

	static public function mdlGetIngredientsByProductId($table, $productId) {
    $stmt = Connection::connect()->prepare("SELECT ingredient_id, quantity FROM $table WHERE product_id = :product_id");
    $stmt->bindParam(":product_id", $productId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
     	static public function mdlShowProductsWithIngredients($table, $item, $value){

		if($item != null){

			$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Connection::connect()->prepare("SELECT * FROM $table");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}
    /*=============================================
    ADDING PRODUCT
    =============================================*/
static public function mdlAddProduct($table, $data) {
    // Prepare the SQL statement
    $stmt = Connection::connect()->prepare("INSERT INTO $table (idCategory, code, description, image, stock, size, sellingPrice) VALUES (:idCategory, :code, :description, :image, :stock, :size, :sellingPrice)");

    // Bind the parameters
    $stmt->bindParam(":idCategory", $data["idCategory"], PDO::PARAM_INT);
    $stmt->bindParam(":code", $data["code"], PDO::PARAM_STR);
    $stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
    $stmt->bindParam(":image", $data["image"], PDO::PARAM_STR);
    $stmt->bindParam(":stock", $data["stock"], PDO::PARAM_INT);
    $stmt->bindParam(":size", $data["size"], PDO::PARAM_STR);
    $stmt->bindParam(":sellingPrice", $data["sellingPrice"], PDO::PARAM_STR);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Return the last inserted ID along with a success status
        return [
            "status" => "ok", 
            "lastInsertId" => Connection::connect()->lastInsertId() // Retrieve the last inserted product ID
        ];
    } else {
        return [
            "status" => "error", 
            "errorInfo" => $stmt->errorInfo() // Get error information for debugging
        ];
    }

    // Close the statement (cleanup)
    $stmt = null; // Cleanup
}


    public static function mdlGetLastInsertedId() {
        return Connection::connect()->lastInsertId();
    }

     
    /*=============================================
    EDITING PRODUCT
    =============================================*/
    static public function mdlEditProduct($table, $data) {
        $stmt = Connection::connect()->prepare("UPDATE $table SET idCategory = :idCategory, description = :description, image = :image, stock = :stock, sellingPrice = :sellingPrice WHERE code = :code");
        $stmt->bindParam(":idCategory", $data["idCategory"], PDO::PARAM_INT);
        $stmt->bindParam(":code", $data["code"], PDO::PARAM_STR);
        $stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
        $stmt->bindParam(":image", $data["image"], PDO::PARAM_STR);
        $stmt->bindParam(":stock", $data["stock"], PDO::PARAM_STR);
        $stmt->bindParam(":sellingPrice", $data["sellingPrice"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

     
    /*=============================================
    DELETING PRODUCT
    =============================================*/
    static public function mdlDeleteProduct($table, $data) {
        $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(":id", $data, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

     
    /*=============================================
    UPDATE PRODUCT
    =============================================*/
    static public function mdlUpdateProduct($table, $item1, $value1, $value) {
        $stmt = Connection::connect()->prepare("UPDATE $table SET $item1 = :$item1 WHERE id = :id");
        $stmt->bindParam(":".$item1, $value1, PDO::PARAM_STR);
        $stmt->bindParam(":id", $value, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

     
    /*=============================================
    SHOW ADDING OF THE SALES
    =============================================*/	
    static public function mdlShowAddingOfTheSales($table) {
        $stmt = Connection::connect()->prepare("SELECT SUM(sales) as total FROM $table");
        $stmt->execute();
        return $stmt->fetch();
        $stmt->close();
        $stmt = null;
    }

public static function mdlAddProductIngredient($table, $data) {
    // Prepare the SQL statement with the correct column names
    $stmt = Connection::connect()->prepare("INSERT INTO $table (product_id, ingredient_id, size) VALUES (:productId, :ingredientId, :size)");

    // Bind the parameters to the SQL query
    $stmt->bindParam(":productId", $data["productId"], PDO::PARAM_INT);
    $stmt->bindParam(":ingredientId", $data["ingredientId"], PDO::PARAM_INT);
    $stmt->bindParam(":size", $data["size"], PDO::PARAM_INT);

    // Execute the query and return success or error response
    if ($stmt->execute()) {
        return "ok"; // Return a success response
    } else {
        return "error"; // Return an error response
    }
}


}
