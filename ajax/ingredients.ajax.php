<?php

require_once "../controllers/ingredients.controller.php";
require_once "../models/ingredients.model.php";

class AjaxIngredients {

    /*=============================================
    EDIT INGREDIENT
    =============================================*/    

    public $idIngredient;

    public function ajaxEditIngredient() {
        $item = "id"; // Assuming 'id' is the name of the column in the database
        $value = $this->idIngredient;

        // Call the controller to get the ingredient details
        $answer = ControllerIngredients::ctrShowIngredients($item, $value);

        // Check if ingredient data was returned
        if ($answer) {
            // Return the ingredient data as JSON
            echo json_encode($answer);
        } else {
            // Return an error message if the ingredient was not found
            echo json_encode(["error" => "Ingredient not found."]);
        }
    }
}

/*=============================================
EDIT INGREDIENT
=============================================*/    
if (isset($_POST["idIngredient"])) {
    $ingredient = new AjaxIngredients();
    $ingredient->idIngredient = $_POST["idIngredient"];
    $ingredient->ajaxEditIngredient();
} else {
    // Return an error if 'idIngredient' is not set
    echo json_encode(["error" => "No ingredient ID provided."]);
}
