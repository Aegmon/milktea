<?php

require_once "connection.php";

class AttributesModel {
    /* --LOG ON TO codeastro.com FOR MORE PROJECTS-- */
    /*=============================================
    CREATE ATTRIBUTE
    =============================================*/

    static public function mdlAddAttribute($table, $data) {
        // Use prepared statement to insert attribute and symbol
        $stmt = Connection::connect()->prepare("INSERT INTO $table(attributes, symbol) VALUES (:attributes, :symbol)");

        // Bind parameters for attributes and symbol
        $stmt->bindParam(":attributes", $data['attributes'], PDO::PARAM_STR);
        $stmt->bindParam(":symbol", $data['symbol'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }

        $stmt->close();
        $stmt = null;
    }

    /* --LOG ON TO codeastro.com FOR MORE PROJECTS-- */
    /*=============================================
    SHOW ATTRIBUTE 
    =============================================*/

    static public function mdlShowAttributes($table, $item, $value) {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

            $stmt->bindParam(":".$item, $value, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $table");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        $stmt->close();
        $stmt = null;
    }

    /* --LOG ON TO codeastro.com FOR MORE PROJECTS-- */
    /*=============================================
    EDIT ATTRIBUTE
    =============================================*/

    static public function mdlEditAttribute($table, $data) {
        // Update the attributes and symbol based on the id
        $stmt = Connection::connect()->prepare("UPDATE $table SET attributes = :attributes, symbol = :symbol WHERE id = :id");

        // Bind parameters for attributes, symbol, and id
        $stmt->bindParam(":attributes", $data["attributes"], PDO::PARAM_STR);
        $stmt->bindParam(":symbol", $data["symbol"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

    /* --LOG ON TO codeastro.com FOR MORE PROJECTS-- */
    /*=============================================
    DELETE ATTRIBUTE
    =============================================*/

    static public function mdlDeleteAttribute($table, $data) {
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
