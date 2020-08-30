<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/userFunctions.php';

    function login($user) {
        $retrun = [];

        session_start();
        $_SESSION["login"] = true;
        $_SESSION["id_user"] = $user["id_user"];
        $_SESSION["name"] = $user["login"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["date"] = $user["date"];
        $_SESSION["city"] = $user["city"];
        $_SESSION["permission"] = $user["permission"];
        $_SESSION["url_portrait"] = $user["url_portrait"];
        $return["success"] = "Zalogowano.";
        $return["session"] = $_SESSION;

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
                $login = $connect->db_connect->real_escape_string($_POST["login"]);
                $password = $connect->db_connect->real_escape_string($_POST["password"]);

                if (empty($login)) { $return["error"] = "Login jest pusty!"; }
                else if (empty($password)) { $return["error"] = "Hasło jest puste!"; }
                else {
                    $return = getUser($connect, $table_users, $login);

                    if(!isset($return["error"]) && !isset($return["warning"])) {
                        $user = $return["user"];
                        $return = checkUserData($login, $password, $user["login"], $user["password"]);

                        if(!isset($return["error"]) && !isset($return["warning"])) $return = login($user);
                    }
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