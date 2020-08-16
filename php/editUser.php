<?php

    require 'init.php';
    require 'Connection.php';

    function updateUser($connect, $table, $id_user) {
        $sql_user = "UPDATE $table SET  WHERE id_user='$id_user'";
        $query_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_user->num_rows == 1) {
            $return["success"] = "Użytkownik istnieje.";
        } else if($query_user->num_rows == 0) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function deleteUser($connect, $table, $id_user) {
        $sql_user = "DELETE FROM $table WHERE id_user = $id_user";
        $query_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_user === true) {
            $return["success"] = "Usunięto użytkownika.";
            $return["delete"] = true;
        } else if($query_user === false) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $connect = new Connection($db_user, $db_password, $db_name);
            $return = $connect->ConnectOpen();

            if(!isset($return["error"])){
                $id_user = $connect->db_connect->real_escape_string($_POST["user__id"]);

                if(isset($_POST["delete"])) $return = deleteUser($connect, $table_users, $id_user);
                else {
                    $login = $connect->db_connect->real_escape_string($_POST["login"]);
                    $email = $connect->db_connect->real_escape_string($_POST["email"]);
                    $password = $connect->db_connect->real_escape_string($_POST["password"]);
                    $city = $connect->db_connect->real_escape_string($_POST["city"]);

                    if (empty($id_user)) { $return["error"] = "Id jest puste!"; }
                    else if (empty($login)) { $return["error"] = "Login jest pusty!"; }
                    else if (empty($email)) { $return["error"] = "Email jest pusty!"; }
                    else if (empty($password)) { $return["error"] = "Hasło jest puste!"; }
                    else if (empty($city)) { $return["error"] = "Miasto jest puste!"; }
                    else $return = updateUser($connect, $table_users, $id_user);
                }
            }

            $connect->ConnectClose();
        }

        if (isset($return["error"])) { $return["status"] = "error"; }
        else if (isset($return["warning"])) { $return["status"] = "warning"; }
        else { $return["status"] = "success"; }

        header("Content-Type: application/json");
        echo json_encode($return);
    }

?>