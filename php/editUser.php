<?php

    require 'init.php';
    require 'Connection.php';

    function updateUser($connect, $table_users, $id_user, $login, $email, $password, $city) {
        $tab_query = [];

        if (!empty($login)) array_push($tab_query, "login = '$login'");
        if (!empty($email)) array_push($tab_query, "email = '$email'");
        if (!empty($password)) array_push($tab_query, "password = '$password'");
        if (!empty($city)) array_push($tab_query, "city = '$city'");

        $set_query = implode(", ", $tab_query);

        $sql_user = "UPDATE $table_users SET $set_query WHERE id_user='$id_user'";
        $query_update_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_update_user === true) {
            $return["success"] = "Zaktualizowano użytkownika.";
            $return["update"] = true;
        } else if($query_update_user === false) $return["warning"] = "Nie zaktualizowano użytkownika.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function deleteUser($connect, $table_users, $table_posts, $id_user) {
        $sql_user = "DELETE FROM $table_users WHERE id_user = $id_user";
        $query_delete_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_delete_user === true) {
            $return["success"] = "Usunięto użytkownika.";
            $return["delete"] = true;
            deletePost($connect, $table_posts, $id_user);
        } else if($query_delete_user === false) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function deletePost($connect, $table, $id_user) {
        $sql_user = "DELETE FROM $table WHERE id_user = $id_user";
        $query_delete_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_delete_user === true) $return["success"] = "Usunięto posty.";
        else if($query_delete_user === false) $return["warning"] = "Posty nie istnieją.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $connect = new Connection($db_user, $db_password, $db_name);
            $return = $connect->ConnectOpen();

            if(!isset($return["error"])){
                $id_user = $connect->db_connect->real_escape_string($_POST["user__id"]);

                if(isset($_POST["delete"])) $return = deleteUser($connect, $table_users, $table_posts, $id_user);
                else {
                    $login = $connect->db_connect->real_escape_string($_POST["login"]);
                    $email = $connect->db_connect->real_escape_string($_POST["email"]);
                    $password = password_hash($connect->db_connect->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
                    $city = $connect->db_connect->real_escape_string($_POST["city"]);

                    if (empty($id_user)) { $return["error"] = "Id jest puste!"; }
                    else $return = updateUser($connect, $table_users, $id_user, $login, $email, $password, $city);
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