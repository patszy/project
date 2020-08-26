<?php

    require 'init.php';
    require 'Connection.php';

    function updateUser($connect, $table_users, $id_user, $login, $email, $password, $city, $url_portrait) {
        $tab_query = [];

        if (!empty($login)) array_push($tab_query, "login = '$login'");
        if (!empty($email)) array_push($tab_query, "email = '$email'");
        if (!empty($password)) array_push($tab_query, "password = '$password'");
        if (!empty($city)) array_push($tab_query, "city = '$city'");
        if (!empty($url_portrait)) array_push($tab_query, "url_portrait = '$url_portrait'");

        $set_query = implode(", ", $tab_query);

        $sql_user = "UPDATE $table_users SET $set_query WHERE id_user='$id_user'";
        $query_update_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_update_user === true) {
            $return["success"] = "Zaktualizowano użytkownika.";
            $return["update"] = true;
        } else if($query_update_user === false) $return["warning"] = "Nie zaktualizowano użytkownika.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function deleteUser($connect, $table_users, $table_posts, $id_user) {
        $sql_user = "DELETE FROM $table_users WHERE id_user = $id_user";
        $query_delete_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_delete_user === true) {
            $return["success"] = "Usunięto użytkownika.";
            $return["delete"] = true;
            deletePost($connect, $table_posts, $id_user);
        } else if($query_delete_user === false) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function deletePost($connect, $table, $id_user) {
        $sql_user = "DELETE FROM $table WHERE id_user = $id_user";
        $query_delete_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_delete_user === true) $return["success"] = "Usunięto posty.";
        else if($query_delete_user === false) $return["warning"] = "Posty nie istnieją.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function validateFile($file) {
        $return = [];
        $max_size = $_POST["MAX_FILE_SIZE"]/1000000;

        if ($_FILES["url_portrait"]["error"] > 0) {
          switch ($_FILES["url_portrait"]["error"]) {
            case 1: $return["error"] = "Zdjęcie jest za duże";
                break;
            case 2: $return["error"] = "Zdjęcie jest za duże.";
                break;
            case 3: $return["error"] = "Zdjęcie wysłane częściowo.";
                break;
            case 4: $return["error"] = "Nie wysłano zdjęcia.";
                break;
            default: $return["error"] = "Błąd podczas wysyłania.";
              break;
          }
        }

        if ($_FILES["url_portrait"]["type"] != 'image/jpeg') $return["error"] = "Zdjęcie jest większe niż 1MB";

        if(!isset($return["error"])) $return["success"] = "Zdjęcie prawidłowe.";

        return $return;
    }

    function saveFile($file, $url) {
        $return = [];

        if(is_uploaded_file($file["url_portrait"]["tmp_name"])) {
            if(!move_uploaded_file($_FILES["url_portrait"]["tmp_name"], ".".$url)) $return["warning"] = "Nie skopiowano zdjęcia do katalogu.";
        } else $return["error"] = "Nie zapisano zdjęcia.";

        if(!isset($return["error"])) $return["success"] = "Zapisano zdjęcie.";

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
                $id_user = $connect->db_connect->real_escape_string($_POST["user_id"]);

                if(isset($_POST["delete"])) $return = deleteUser($connect, $table_users, $table_posts, $id_user);
                else {
                    $login = $connect->db_connect->real_escape_string($_POST["login"]);
                    $email = $connect->db_connect->real_escape_string($_POST["email"]);
                    $password = password_hash($connect->db_connect->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
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