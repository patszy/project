<?php

    require 'init.php';
    require 'Connection.php';

    function checkRegisterData($return) {
        if(isset($_POST["guardian"])) {
            $return["errors"] = "Guardian!";
        } else {
            if (empty($_POST["login"])) { $return["errors"] = "Login is empty!"; }
            else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) { $return["errors"] = "Wrong email!"; }
            else if (empty($_POST["password"])) { $return["errors"] = "Password is empty"; }
            else if (empty($_POST["city"])) { $return["errors"] = "City is empty"; }
        }

        return $return;
    }

    function isUser($connect, $return) {
        $sql_email = "SELECT * FROM users WHERE email='" . "$_POST[email]}";
        $query_email = $connect->query($sql_email);

        if($query_email->num_rows == 0) {
            $return["text"] = "Create user!";
        } else if($check_email->num_rows != 0) {
            while($row = $check_email->fetch_assoc()) {
                $return["text"] = "Email exists.";
                echo "User with same email exists: <br>";
                echo "Id_user: " . $row["id_user"] . " | Login: " . $row["login"] . " | Email: " . $row["email"] . "<br>";
            }
        } else {
            $return["errors"] = "Failed query!";
            echo "Error: " . $sql_email . "<br>" . $this->db_connect->error;
        }

        return $return;
    }

    // function createUser($login, $email, $password, $date, $city) {
    //     $sql_create_user = "INSERT INTO users (id_user, login, email, password, date, city) VALUES ('', '$login', '$email', '$password', '$date', '$city')";

    //     if ($this->db_connect->query($sql_create_user) === TRUE) echo "New record created successfully";
    //     else echo "Error: " . $sql_create_user . "<br>" . $this->db_connect->error;
    // }

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $errors = [];
        $return = [];

        $connect = new Connection($db_user, $db_password, $db_name);

        $connect->ConnectOpen();

        if(isset($_POST["guardian"])) {
            $return["errors"] = "Guardian!";
        } else {
            if (empty($_POST["login"])) { $return["errors"] = "Login is empty!"; }
            else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) { $return["errors"] = "Wrong email!"; }
            else if (empty($_POST["password"])) { $return["errors"] = "Password is empty"; }
            else if (empty($_POST["city"])) { $return["errors"] = "City is empty"; }
            else {
                $sql_email = "SELECT * FROM users WHERE email='{$_POST['email']}'";
                $query_email = $connect->db_connect->query($sql_email);

                if($query_email->num_rows == 0) {
                    $sql_create_user = "INSERT INTO users (id_user, login, email, password, date, city) VALUES ('', '{$_POST['login']}', '{$_POST['email']}', '{$_POST['password']}', '{$_POST['date']}', '{$_POST['city']}')";

                    if ($connect->db_connect->query($sql_create_user) === TRUE) echo "New record created successfully";
                    else echo "Error: " . $sql_create_user . "<br>" . $connect->db_connect->error;
                } else if($query_email->num_rows != 0) {
                    while($row = $query_email->fetch_assoc()) {
                        echo "User with same email exists: <br>";
                        echo "Id_user: " . $row["id_user"] . " | Login: " . $row["login"] . " | Email: " . $row["email"] . "<br>";
                    }
                } else echo "Error: " . $sql_email . "<br>" . $connect->db_connect->error;
            }
        }

        $connect->ConnectClose();

		if (count($errors) > 0) { $return["errors"] = $errors[array_key_first($errors)]; }
		else {
			$return["status"] = "ok";
			$return["text"] = "User Registered";
		}

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>