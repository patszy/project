<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/userFunctions.php';
    require '../functions/emailFunctions.php';

    function generatePassword() { return bin2hex(random_bytes(8)); }

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $connect = new Connection($db_user, $db_password, $db_name);
            $return = $connect->ConnectOpen();

            if(!isset($return["error"])) {
                $email = $connect->db_connect->real_escape_string($_POST["email"]);

                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $return["error"] = "Niewłaściwy email!";
                else {
                    $return = isUserEmail($connect, $table_users, $email);

                    if(!isset($return["error"])) {
                        $password = generatePassword();
                        $return = updateUserPassword($connect, $table_users, $email, $password);

                        if(!isset($return["error"])) $return = recoveryEmail($email, $password);
                    }
                }
            }
            $connect->ConnectClose();
        }

        if (isset($return["error"])) $return["status"] = "error";
        else $return["status"] = "success";

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>