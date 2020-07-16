<?php

    require 'init.php';
    require 'Connection.php';

    function isUser($connect, $table, $email) {
        $sql_email = "SELECT * FROM $table WHERE email='$email'";
        $query_email = $connect->db_connect->query($sql_email);
        $return = [];

        if($query_email->num_rows == 0) { $return["success"] = "Create user is possible."; }
        else if($query_email->num_rows != 0) {
            while($row = $query_email->fetch_assoc()) {
                $return["warning"] = "Email exists.";
                echo "Id_user: " . $row["id_user"] . " | Login: " . $row["login"] . " | Email: " . $row["email"] . "<br>";
            }
        } else {
            $return["error"] = "Wrong SQL query!";
            echo "Error: " . $sql_email . "<br>" . $connect->db_connect->error;
        }

        return $return;
    }

    function createUser($connect, $table, $login, $email, $password, $date, $city) {
        $sql_create_user = "INSERT INTO $table SET id_user='', login='$login', email='$email', password='$password', date='$date', city='$city', verified = '0'";
        $return = [];

        if ($connect->db_connect->query($sql_create_user) === TRUE) $return["success"] = "Registered successfully.";
        else {
            $return["errors"] = "Wrong SQL query!";
            echo "Error: " . $sql_create_user . "<br>" . $connect->db_connect->error;
        }

        return $return;
    }

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $errors = [];
        $return = [];

        $connect = new Connection($db_user, $db_password, $db_name);

        $return = $connect->ConnectOpen();

        if(isset($return["error"])){
            echo "Error";
        }


        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $login = $_POST["login"];
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $date = $_POST["date"];
            $city = $_POST["city"];

            if (empty($login)) { $return["error"] = "Login is empty!"; }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $return["error"] = "Wrong email!"; }
            else if (empty($password)) { $return["error"] = "Password is empty!"; }
            else if (empty($date)) { $return["error"] = "Age is empty!"; }
            else if (empty($_POST["city"])) { $return["error"] = "City is empty!"; }
            else {
                $sql_email = "SELECT * FROM users WHERE email='$email'";
                $query_email = $connect->db_connect->query($sql_email);

                $return = isUser($connect, $table_users, $email);

                if(isset($return["success"])) { $return = createUser($connect, $table_users, $login, $email, $password, $date, $city); }
            }
        }

        $connect->ConnectClose();

		if (isset($return["error"])) { $return["status"] = "error"; }
		else if (isset($return["warning"])) { $return["status"] = "warning"; }
        // else { $return["status"] = "ok"; }

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>