<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/userFunctions.php';
    require '../functions/emailFunctions.php';

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $connect = new Connection($db_user, $db_password, $db_name);
            $return = $connect->ConnectOpen();

            if(!isset($return["error"])){
                $login = $connect->db_connect->real_escape_string($_POST["login"]);
                $email = $connect->db_connect->real_escape_string($_POST["email"]);
                $password = password_hash($connect->db_connect->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
                $date = $connect->db_connect->real_escape_string($_POST["date"]);
                $city = $connect->db_connect->real_escape_string($_POST["city"]);

                if (empty($login)) { $return["error"] = "Login jest pusty!"; }
                else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $return["error"] = "Niewłaściwy email!"; }
                else if (empty($password)) { $return["error"] = "Hasło jest puste!"; }
                else if (empty($date)) { $return["error"] = "Wiek jest pusty!"; }
                else if (empty($city)) { $return["error"] = "Miasto jest puste!"; }
                else {
                    $return = isUserLoginOrEmail($connect, $table_users, $email, $login);

                    if(!isset($return["error"]) && !isset($return["warning"])) {
                        $return = createUser($connect, $table_users, $login, $email, $password, $date, $city);

                        if(!isset($return["error"])) { $return = verificationEmail($email, $login); }
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