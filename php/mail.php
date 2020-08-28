<?php

    function contactEmail($email, $login, $userTitle, $userEmail, $userMessage) {
        $return = [];

        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: " . $userEmail . "\r\n";
        $headers .= "Reply-to: " . $userEmail;
        $message  = "
            <html>
                <head>
                    <meta charset=\"utf-8\">
                </head>
                <body>
                    <p>
                        Wiadomość od:
                        <a href=\"mailto:$userEmail\">$userEmail</a>
                    </p>
                    <h1>
                        Dzień dobry, $login
                    </h1>
                    <p>
                        $userTitle
                    </p>
                    <p>
                        $userMessage
                    </p>
                </body>
            </html>";

        if (mail($email, "Wiadomość ze strony gejusze.pl " . date("d-m-Y"), $message, $headers)) { $return["info"] = "Wiadomość wysłana."; }
        else $return["error"] = "Wiadomość nie wysłana!";

        return $return;
    }

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