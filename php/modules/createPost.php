<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/fileFunctions.php';
    require '../functions/postFunctions.php';
    require '../functions/userFunctions.php';

    function createDate() {
        $date = date("Y-m-d H:i:s", time());
        return $date;
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
                $date = createDate();
                $url_post_img = "";

                if(!empty($_FILES["url_post_img"]["name"])) {
                    $return = validateFile($_FILES);
                    $url_date = date("Y-m-d H-i-s", strtotime(createDate()));
                    $url_post_img = "./assets/img/posts_img/".$url_date."_post_img.jpg";

                    if(!isset($return["error"])) $return = saveFile($_FILES, $url_post_img);
                }

                if(!isset($return["error"])) {
                    if (empty($title)) { $return["error"] = "Tytuł jest pusty!"; }
                    else if (empty($category)) { $return["error"] = "kategoria jest pusta!"; }
                    else if (empty($content)) { $return["error"] = "Treść jest pusta!"; }
                    else {
                        $return = isPost($connect, $table_posts, $user_id, $title, $category);
                        if(!isset($return["error"]) && !isset($return["warning"])) {
                            $return = createPost($connect, $table_posts, $user_id, $date, $title, $category, $content, $url_post_img);

                            if(!isset($return["error"]) && !isset($return["warning"])) {
                                $return = getUserPost($connect, $table_posts, $user_id);

                                if(!isset($return["error"]) && !isset($return["warning"])) {
                                    foreach ($return["post"] as &$post) {
                                        $user = getUserData($connect, $table_users, $post["id_user"])["user"];

                                        if(empty($return["users"])) $post["user"] = $user;
                                        else foreach($return["users"] as &$item) if($item["id_user"] != $user["id_user"]) $post["user"] = $user;
                                    }

                                    $return["success"] = "Utworzono post.";
                                }
                            }
                        }
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