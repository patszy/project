<?php

    require 'init.php';
    require 'Connection.php';

    function getPostData() {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $password = md5($_POST["password"]);
        $bdate = $_POST["bdate"];
        $city = $_POST["city"];
        $gender = ($_POST["gender"]=="male") ? 1 : 0;
        $guardian = isset($_POST["guardian"]) ? true : false;
    }

    function checkPostData() {
        if (empty($name)) {
            array_push($errors, "Name is empty!");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Wrong email!");
        }
        if (empty($message)) {
            array_push($errors, "Message is empty");
        }
        if (isset($guardian)) {
            array_push($errors, "Quardian!");
        }
    }

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$errors = [];
		$return = [];

        $connect = new Connection($db_user, $db_password, $db_name);

        $connect->ConnectOpen();

        if(count($connect->errors)) {
            $errors = $connect->errors;
            echo implode( ", ", $errors );
        } else {


            $connect->ConnectClose();
        }

		if (count($errors) > 0) { $return["errors"] = $errors; }
		else {
			$return["status"] = "ok";
			$return["text"] = "Registered properly.";
		}

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>