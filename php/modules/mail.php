<?php

    require '../functions/emailFunctions.php';

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $email = $_POST["email_recipient"];
            $login = $_POST["login"];
            $userTitle = $_POST["title"];
            $userEmail = $_POST["email_addressee"];
            $userMessage = $_POST["message"];

            if (empty($login)) { $return["error"] = "Login jest pusty!"; }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) { $return["error"] = "Niewłaściwy email!"; }
            else if (empty($userTitle)) { $return["error"] = "Tytuł jest pusty!"; }
            else if (empty($userMessage)) { $return["error"] = "Wiadomośc jest pusta!"; }
            else { $return = contactEmail($email, $login, $userTitle, $userEmail, $userMessage); }
        }

        if (isset($return["error"])) { $return["status"] = "error"; }
        else { $return["status"] = "success"; }

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>