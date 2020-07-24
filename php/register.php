<?php

    require 'init.php';
    require 'Connection.php';

    function isUser($connect, $table, $email) {
        $sql_email = "SELECT * FROM $table WHERE email='$email'";
        $query_email = $connect->db_connect->query($sql_email);
        $return = [];

        if($query_email->num_rows == 0) $return["success"] = "Create user is possible.";
        else if($query_email->num_rows != 0) $return["warning"] = "Email już istnieje.";
        else $return["error"] = "Error: " . $sql_email . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function createUser($connect, $table, $login, $email, $password, $date, $city) {
        $sql_create_user = "INSERT INTO $table SET id_user='', login='$login', email='$email', password='$password', date='$date', city='$city', verified = '0'";
        $return = [];

        if ($connect->db_connect->query($sql_create_user) === TRUE) $return["success"] = "Rejestracja pomyślna.";
        else  $return["errors"] = "Error: " . $sql_create_user . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function verificationEmail($email, $login) {
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
                    <h1> Witaj $login!</h1>
                    <p> Rejestracja przebiegła poprawnie. </p>
                    <p> Wiadomość wygenerowana przez system, prosimy na nią nie odpowiadać. </p>
                </body>
            </html>";

        if (mail($email, "Wiadomość ze strony gejusze.pl " . date("d-m-Y"), $message, $headers)) {
            $return["info"] = "Wysłano email weryfikacyjny.";
        } else {
            $return["error"] = "Email weryfikacyjny nie wysłany!";
        }

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
                $email = $connect->db_connect->real_escape_string($_POST["email"]);
                $password = password_hash($connect->db_connect->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
                $date = $connect->db_connect->real_escape_string($_POST["date"]);
                $city = $connect->db_connect->real_escape_string($_POST["city"]);

                if (empty($login)) { $return["error"] = "Login jest pusty!"; }
                else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $return["error"] = "Niewłaściwy email!"; }
                else if (empty($password)) { $return["error"] = "Hasło jest puste!"; }
                else if (empty($date)) { $return["error"] = "Wiek jest pusty!"; }
                else if (empty($_POST["city"])) { $return["error"] = "Miasto jest puste!"; }
                else {
                    $return = isUser($connect, $table_users, $email);

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