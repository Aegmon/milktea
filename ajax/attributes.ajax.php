<?php

require_once "../controllers/attributes.controller.php";
require_once "../models/attributes.model.php";

class AjaxAttributes {

    /*=============================================
    EDIT ATTRIBUTE
    =============================================*/    

    public $idAttribute;

    public function ajaxEditAttribute() {
        $item = "id"; // Assuming 'id' is the name of the column in the database
        $value = $this->idAttribute;

        // Call the controller to get the attribute details
        $answer = ControllerAttributes::ctrShowAttributes($item, $value);

        // Check if attribute data was returned
        if ($answer) {
            // Return the attribute data as JSON
            echo json_encode($answer);
        } else {
            // Return an error message if the attribute was not found
            echo json_encode(["error" => "Attribute not found."]);
        }
    }
}

/*=============================================
EDIT ATTRIBUTE
=============================================*/    
if (isset($_POST["idAttribute"])) {
    $attribute = new AjaxAttributes();
    $attribute->idAttribute = $_POST["idAttribute"];
    $attribute->ajaxEditAttribute();
} else {
    // Return an error if 'idAttribute' is not set
    echo json_encode(["error" => "No attribute ID provided."]);
}
