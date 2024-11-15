<?php

declare(strict_types=1); // Enforce strict typing

require_once "connection.php";

class UsersModel {

    /*=============================================
    SHOW USER 
    =============================================*/

    public static function MdlShowUsers(string $tableUsers, ?string $item, $value = null) {

        if ($item !== null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tableUsers WHERE $item = :$item");
            $stmt->bindParam(":".$item, $value, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tableUsers");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Clean up
        $stmt = null;

        return $result;
    }

    /*=============================================
    ADD USER 
    =============================================*/

    public static function mdlAddUser(string $table, array $data): string {

        $stmt = Connection::connect()->prepare(
            "INSERT INTO $table(name, user, password, profile, photo) 
            VALUES (:name, :user, :password, :profile, :photo)"
        );

        $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindParam(":user", $data["user"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
        $stmt->bindParam(":profile", $data["profile"], PDO::PARAM_STR);
        $stmt->bindParam(":photo", $data["photo"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = 'ok';
        } else {
            $result = 'error';
        }

        // Clean up
        $stmt = null;

        return $result;
    }

    /*=============================================
    EDIT USER 
    =============================================*/

    public static function mdlEditUser(string $table, array $data): string {

        $stmt = Connection::connect()->prepare(
            "UPDATE $table SET name = :name, password = :password, profile = :profile, photo = :photo 
            WHERE user = :user"
        );

        $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindParam(":user", $data["user"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
        $stmt->bindParam(":profile", $data["profile"], PDO::PARAM_STR);
        $stmt->bindParam(":photo", $data["photo"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = 'ok';
        } else {
            $result = 'error';
        }

        // Clean up
        $stmt = null;

        return $result;
    }

    /*=============================================
    UPDATE USER 
    =============================================*/

public static function mdlUpdateUser(string $table, string $item1, $value1, string $item2, $value2): string {
    // Prepare the SQL statement for updating the OTP or other user data
    $stmt = Connection::connect()->prepare(
        "UPDATE $table SET $item1 = :$item1 WHERE $item2 = :$item2"
    );

    // Bind the parameters for the statement
    $stmt->bindParam(":".$item1, $value1, PDO::PARAM_STR);
    $stmt->bindParam(":".$item2, $value2, PDO::PARAM_STR);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $result = 'ok'; // If update is successful
    } else {
        $result = 'error'; // If update fails
    }

    // Clean up
    $stmt = null;

    return $result;
}


    /*=============================================
    DELETE USER 
    =============================================*/

    public static function mdlDeleteUser(string $table, $data): string {

        $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(":id", $data, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = 'ok';
        } else {
            $result = 'error';
        }

        // Clean up
        $stmt = null;

        return $result;
    }
}
