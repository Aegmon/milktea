<?php

class ControllerIngredients {

    /*=============================================
    CREATE INGREDIENT
    =============================================*/

    static public function ctrCreateIngredient() {
        if (isset($_POST['newIngredient']) && isset($_POST['newQuantity']) && isset($_POST['newSize'])
        && isset($_POST['newPrice'])
        && isset($_POST['stockalert'])
        && isset($_POST['newMeasurement'])) {

            // Validate ingredient name, quantity, and size
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["newIngredient"]) && 
                is_numeric($_POST["newQuantity"]) &&
                in_array($_POST["newSize"], ['grams', 'kilograms', 'liters', 'mililiters'])) {

                $table = 'ingredients'; // Change to your ingredients table name

                $data = array(
                    'ingredient' => $_POST['newIngredient'], // Updated field name
                    'quantity'   => $_POST['newQuantity'],   // Updated field name
                    'size'       => $_POST['newSize'] ,
                    'addons_price'       => $_POST['newPrice'] , 
                    'addons_measurement'     => $_POST['newMeasurement'] ,
                    'stockalert'       => $_POST['stockalert']           
                );

                $answer = IngredientsModel::mdlAddIngredient($table, $data); // Update the model method to add ingredients

                if ($answer == 'ok') {
                    echo '<script>
                        swal({
                            type: "success",
                            title: "Ingredient has been successfully saved",
                            showConfirmButton: true,
                            confirmButtonText: "Close"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "ingredients"; // Redirect to the ingredients page
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "Invalid input. Check ingredient name, quantity, or size.",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ingredients"; // Redirect to the ingredients page
                        }
                    });
                </script>';
            }
        }
    }

    /*=============================================
    SHOW INGREDIENTS
    =============================================*/

    static public function ctrShowIngredients($item, $value) {
        $table = "ingredients"; // Change to your ingredients table name
        $answer = IngredientsModel::mdlShowIngredients($table, $item, $value); // Update the model method to show ingredients
        return $answer;
    }

    /*=============================================
    EDIT INGREDIENT
    =============================================*/

  /*=============================================
EDIT INGREDIENT
=============================================*/

static public function ctrEditIngredient() {
    if (isset($_POST["editIngredient"]) && isset($_POST["editQuantity"]) && isset($_POST["editSize"]) && isset($_POST["idIngredient"])) {

        // Log the received data for debugging
        error_log("Editing ingredient data: " . print_r($_POST, true));

        // Validate ingredient name, quantity, and size
        if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editIngredient"]) && 
            is_numeric($_POST["editQuantity"]) &&
            in_array($_POST["editSize"], ['grams', 'kilograms', 'liters', 'mililiters'])) {

            $table = "ingredients"; // Change to your ingredients table name

            $data = array(
                "ingredient" => $_POST["editIngredient"], // Updated field name
                "quantity"   => $_POST["editQuantity"],   // Updated field name
                "size"       => $_POST["editSize"],       // Updated field name
                "id"         => $_POST["idIngredient"]    // Ensure this corresponds to the ingredient ID
            );

            $answer = IngredientsModel::mdlEditIngredient($table, $data); // Update the model method to edit ingredients

            if ($answer == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "Ingredient has been successfully saved",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ingredients"; // Redirect to the ingredients page
                        }
                    });
                </script>';
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "An error occurred while updating the ingredient.",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ingredients"; // Redirect to the ingredients page
                        }
                    });
                </script>';
            }
        } else {
            echo '<script>
                swal({
                    type: "error",
                    title: "Invalid input. Check ingredient name, quantity, or size.",
                    showConfirmButton: true,
                    confirmButtonText: "Close"
                }).then(function(result){
                    if (result.value) {
                        window.location = "ingredients"; // Redirect to the ingredients page
                    }
                });
            </script>';
        }
    }
}


    /*=============================================
    DELETE INGREDIENT
    =============================================*/

    static public function ctrDeleteIngredient() {
        if (isset($_GET["idIngredient"])) {
            $table = "ingredients"; // Change to your ingredients table name
            $data = $_GET["idIngredient"];

            $answer = IngredientsModel::mdlDeleteIngredient($table, $data); // Update the model method to delete ingredients

            if ($answer == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "The ingredient has been successfully deleted",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ingredients"; // Redirect to the ingredients page
                        }
                    });
                </script>';
            }
        }
    }
}
