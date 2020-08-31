<?php


    //mail
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

    //passRecovery
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

    //register
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

?>