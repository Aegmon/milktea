<?php

require_once "connection.php";

class IngredientsModel {

    /*=============================================
    CREATE INGREDIENT
    =============================================*/

    static public function mdlAddIngredient($table, $data) {
        // Use a prepared statement to insert ingredient, quantity, and size
        $stmt = Connection::connect()->prepare("INSERT INTO $table (ingredient, quantity, size) VALUES (:ingredient, :quantity, :size)");

        // Bind parameters for ingredient, quantity, and size
        $stmt->bindParam(":ingredient", $data['ingredient'], PDO::PARAM_STR);
        $stmt->bindParam(":quantity", $data['quantity'], PDO::PARAM_INT);  // Assuming quantity is an integer
        $stmt->bindParam(":size", $data['size'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }

        $stmt->close();
        $stmt = null;
    }

    /*=============================================
    SHOW INGREDIENTS
    =============================================*/

    static public function mdlShowIngredients($table, $item, $value) {
        if ($item != null) {
            // If an item is provided (e.g., id), fetch the specific ingredient
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

            $stmt->bindParam(":" . $item, $value, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();
        } else {
            // Otherwise, fetch all ingredients
            $stmt = Connection::connect()->prepare("SELECT * FROM $table");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        $stmt->close();
        $stmt = null;
    }

 /*=============================================
EDIT INGREDIENT
=============================================*/

static public function mdlEditIngredient($table, $data) {
    // Use a prepared statement to update ingredient, quantity, and size based on the id
    $stmt = Connection::connect()->prepare("UPDATE $table SET ingredient = :ingredient, quantity = :quantity, size = :size WHERE id = :id");

    // Bind parameters for ingredient, quantity, size, and id
    $stmt->bindParam(":ingredient", $data["ingredient"], PDO::PARAM_STR);
    $stmt->bindParam(":quantity", $data["quantity"], PDO::PARAM_INT);  // Assuming quantity is an integer
    $stmt->bindParam(":size", $data["size"], PDO::PARAM_STR);
    $stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);

    if ($stmt->execute()) {
        return "ok";
    } else {
        // Log the error message
        error_log("Failed to update ingredient: " . print_r($stmt->errorInfo(), true));
        return "error";
    }

    $stmt->close();
    $stmt = null;
}
static public function mdlSubtractData($table, $data) {
    // Prepare the statement to subtract the size from the quantity for the specified ingredient id
    $stmt = Connection::connect()->prepare("UPDATE $table SET quantity = quantity - :size WHERE id = :id");

    // Bind parameters
    $stmt->bindParam(":size", $data["size"], PDO::PARAM_INT);  // Subtract the 'size' from the 'quantity'
    $stmt->bindParam(":id", $data["ingredientId"], PDO::PARAM_INT);

    if ($stmt->execute()) {
        return "ok";
    } else {
        // Log the error message
        error_log("Failed to update ingredient: " . print_r($stmt->errorInfo(), true));
        return "error";
    }

    // Close the statement
    $stmt->close();
    $stmt = null;
}


    /*=============================================
    DELETE INGREDIENT
    =============================================*/

    static public function mdlDeleteIngredient($table, $data) {
        // Use a prepared statement to delete the ingredient based on id
        $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");

        // Bind parameter for id
        $stmt->bindParam(":id", $data, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";    
        }

        $stmt->close();
        $stmt = null;
    }
}
