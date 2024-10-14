<?php

class ControllerAttributes {

    /*=============================================
    CREATE ATTRIBUTE
    =============================================*/

    static public function ctrCreateAttribute() {
        if (isset($_POST['newAttribute']) && isset($_POST['newSymbol'])) {

            // Validate attribute name and symbol
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["newAttribute"]) && preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ]+$/', $_POST["newSymbol"])) {

                $table = 'attributes'; // Change to your attributes table name

                $data = array(
                    'attributes' => $_POST['newAttribute'], // Updated field name
                    'symbol' => $_POST['newSymbol']         // Updated field name
                );

                $answer = AttributesModel::mdlAddAttribute($table, $data); // Update the model method to add attributes

                if ($answer == 'ok') {
                    echo '<script>
                        swal({
                            type: "success",
                            title: "Attribute has been successfully saved",
                            showConfirmButton: true,
                            confirmButtonText: "Close"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "attributes"; // Redirect to the attributes page
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "No special characters or blank fields",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "attributes"; // Redirect to the attributes page
                        }
                    });
                </script>';
            }
        }
    }

    /*=============================================
    SHOW ATTRIBUTES
    =============================================*/

    static public function ctrShowAttributes($item, $value) {
        $table = "attributes"; // Change to your attributes table name
        $answer = AttributesModel::mdlShowAttributes($table, $item, $value); // Update the model method to show attributes
        return $answer;
    }

    /*=============================================
    EDIT ATTRIBUTE
    =============================================*/

    static public function ctrEditAttribute() {
        if (isset($_POST["editAttribute"]) && isset($_POST["editSymbol"])) {

            // Validate attribute name and symbol
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editAttribute"]) && preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ]+$/', $_POST["editSymbol"])) {

                $table = "attributes"; // Change to your attributes table name

                $data = array(
                    "attributes" => $_POST["editAttribute"], // Updated field name
                    "symbol" => $_POST["editSymbol"],        // Updated field name
                    "id" => $_POST["idAttribute"]             // Ensure this corresponds to the attribute ID
                );

                $answer = AttributesModel::mdlEditAttribute($table, $data); // Update the model method to edit attributes

                if ($answer == "ok") {
                    echo '<script>
                        swal({
                            type: "success",
                            title: "Attribute has been successfully saved",
                            showConfirmButton: true,
                            confirmButtonText: "Close"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "attributes"; // Redirect to the attributes page
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "No special characters or blank fields",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "attributes"; // Redirect to the attributes page
                        }
                    });
                </script>';
            }
        }
    }

    /*=============================================
    DELETE ATTRIBUTE
    =============================================*/

    static public function ctrDeleteAttribute() {
        if (isset($_GET["idAttribute"])) {
            $table = "attributes"; // Change to your attributes table name
            $data = $_GET["idAttribute"];

            $answer = AttributesModel::mdlDeleteAttribute($table, $data); // Update the model method to delete attributes

            if ($answer == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "The attribute has been successfully deleted",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "attributes"; // Redirect to the attributes page
                        }
                    });
                </script>';
            }
        }
    }
}
