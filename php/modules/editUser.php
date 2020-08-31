<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/fileFunctions.php';
    require '../functions/postFunctions.php';
    require '../functions/userFunctions.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $connect = new Connection($db_user, $db_password, $db_name);
            $return = $connect->ConnectOpen();

            if(!isset($return["error"])){
                $id_user = $connect->db_connect->real_escape_string($_POST["user_id"]);

                if(isset($_POST["delete"])) $return = deleteUser($connect, $table_users, $table_posts, $id_user);
                else {
                    $login = $connect->db_connect->real_escape_string($_POST["login"]);
                    $email = $connect->db_connect->real_escape_string($_POST["email"]);
                    $password = "";
                    if(!empty($_POST["password"])) $password = password_hash($connect->db_connect->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
                    $city = $connect->db_connect->real_escape_string($_POST["city"]);
                    $url_portrait = "";

                    if(!empty($_FILES["url_portrait"]["name"])) {
                        $return = validateFile($_FILES);
                        $url_portrait = "./assets/img/portraits/".$id_user."_portrait.jpg";

                        if(!isset($return["error"])) $return = saveFile($_FILES, $url_portrait);
                    }

                    if (empty($id_user)) { $return["error"] = "Id jest puste!"; }
                    else if(!isset($return["error"])) $return = updateUser($connect, $table_users, $id_user, $login, $email, $password, $city, $url_portrait);
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