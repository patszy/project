<?php

    require 'init.php';
    require 'Connection.php';

    function getUser($connect, $table, $login) {
        $sql_login = "SELECT * FROM $table WHERE login='$login'";
        $query_login = $connect->db_connect->query($sql_login);
        $return = [];
        $return["user"] = [];

        if($query_login->num_rows == 1) {
            $return["success"] = "Użytkownik istnieje.";
            $return["user"] = mysqli_fetch_assoc($query_login);
        } else if($query_login->num_rows == 0) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_login . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function checkData($login, $password, $userLogin, $userPassword) {
        $return = [];

        if(password_verify($password, $userPassword) && $login == $userLogin) $return["success"] = "Dane poprawne.";
        else if(!password_verify($password, $userPassword) || $login != $userLogin) $return["error"] = "Niewłaściwe dane.";
        else $return["error"] = "Error: " . $sql_login . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function login($user) {
        $retrun = [];

        session_start();
        $_SESSION["login"] = true;
        $_SESSION["id_user"] = $user["id_user"];
        $_SESSION["name"] = $user["login"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["date"] = $user["date"];
        $_SESSION["city"] = $user["city"];
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
                        $return = checkData($login, $password, $user["login"], $user["password"]);

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