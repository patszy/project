<?php

    require 'init.php';
    require 'Connection.php';

    function isUser($connect, $table, $email) {
        $sql_email = "SELECT * FROM $table WHERE email='$email'";
        $query_email = $connect->db_connect->query($sql_email);
        $return = [];

        if($query_email->num_rows == 1) $return["success"] = "Email jest w bazie.";
        else if($query_email->num_rows == 0) $return["error"] = "Email nie istnieje.";

        return $return;
    }

    function generatePassword() { return bin2hex(random_bytes(8)); }

    function updatePassword($connect, $table, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update_password = "UPDATE $table SET password = '$password' WHERE email = '$email';";
        $return = [];

        if ($connect->db_connect->query($sql_update_password) === TRUE) $return["success"] = "Hasło odnowione.";
        else $return["errors"] = "Error: " . $sql_update_password . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function recoveryEmail($email, $password) {
        $return = [];

        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Gejusz.pl";
        $message  = "
        <html>
            <head>
                <meta charset=\"utf-8\">
            </head>
            <body>
                <h1> Witaj $email!</h1>
                <p> Twoje nowe hasło: $password </p>
            </body>
        </html>";

        if (mail($email, "Wiadomość ze strony gejusze.pl " . date("d-m-Y"), $message, $headers)) $return["info"] = "Hasło wysłane.";
        else $return["error"] = "Hasło nie wysłane!";

        return $return;
    }

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $email = $_POST["email"];

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $return["error"] = "Niewłaściwy email!";
            else {
                $connect = new Connection($db_user, $db_password, $db_name);

                $return = $connect->ConnectOpen();

                if(!isset($return["error"])) {
                    $return = isUser($connect, $table_users, $email);

                    if(!isset($return["error"])) {
                        $password = generatePassword();
                        $return = updatePassword($connect, $table_users, $email, $password);

                        if(!isset($return["error"])) $return = recoveryEmail($email, $password);
                    }
                }

                $connect->ConnectClose();
            }
        }

        if (isset($return["error"])) $return["status"] = "error";
        else $return["status"] = "success";

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>