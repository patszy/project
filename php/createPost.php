<?php

    require 'init.php';
    require 'Connection.php';

    function createDate() {
        $date = date("Y-m-d H:i:s", time());
        return $date;
    }

    function isPost($connect, $table, $user_id, $title, $category) {
        $sql_post = "SELECT * FROM $table WHERE id_user='$user_id' AND title='$title' AND category='$category'";
        $query_post = $connect->db_connect->query($sql_post);
        $return = [];

        if($query_post->num_rows == 0) { $return["success"] = "Create post is possible."; }
        else if($query_post->num_rows != 0) {
            while($row = $query_post->fetch_assoc()) {
                $return["warning"] = "Na tym konie już istnieje podobny post.";
            }
        } else {
            $return["error"] = "Error: " . $sql_post . "<br>" . $connect->db_connect->error;
        }

        return $return;
    }

    function createPost($connect, $table, $id, $date, $title, $category, $content, $url_post_img) {
        $url_img = "";
        if (!empty($url_portrait)) $url_img = $url_portrait;

        $sql_create_post = "INSERT INTO $table SET id_post='', id_user='$id', date='$date', title='$title', category='$category', content='$content', url_post_img='$url_img'";
        $return = [];

        if ($connect->db_connect->query($sql_create_post) === TRUE) $return["success"] = "Utworzono post.";
        else {
            $return["errors"] = "Error: " . $sql_create_post . "<br>" . $connect->db_connect->error;
        }

        return $return;
    }

    function validateFile($file) {
        $return = [];

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

        if ($_FILES["url_portrait"]["type"] != 'image/jpeg') $return["error"] = "Zdjęcie jest za duże";

        if(!isset($return["error"])) $return["success"] = "Zdjęcie prawidłowe.";

        return $return;
    }

    function saveFile($file, $url) {
        $return = [];

        if(is_uploaded_file($file["url_portrait"]["tmp_name"])) {
            if(!move_uploaded_file($_FILES["url_portrait"]["tmp_name"], $url)) $return["warning"] = "Nie skopiowano zdjęcia do katalogu.";
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
                $user_id = $connect->db_connect->real_escape_string($_POST["user__id"]);
                $title = $connect->db_connect->real_escape_string($_POST["title"]);
                $category = $connect->db_connect->real_escape_string($_POST["category"]);
                $content = $connect->db_connect->real_escape_string( $_POST["content"]);

                if (empty($title)) { $return["error"] = "Tytuł jest pusty!"; }
                else if (empty($category)) { $return["error"] = "kategoria jest pusta!"; }
                else if (empty($content)) { $return["error"] = "Treść jest pusta!"; }
                else {
                    $return = isPost($connect, $table_posts, $user_id, $title, $category);

                    if(!isset($return["error"]) && !isset($return["warning"])) { $return = createPost($connect, $table_posts, $user_id, createDate(), $title, $category, $content); }
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