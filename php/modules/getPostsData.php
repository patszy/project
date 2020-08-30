<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/userFunctions.php';
    require '../functions/postFunctions.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        $connect = new Connection($db_user, $db_password, $db_name);
        $return = $connect->ConnectOpen();

        if(!isset($return["error"])) {
            isset($_POST["rowNum"]) ? $rowNum = $connect->db_connect->real_escape_string($_POST["rowNum"]) : $rowNum = 0;
            isset($_POST["rowCount"]) ? $rowCount = $connect->db_connect->real_escape_string($_POST["rowCount"]) : $rowCount = 1;

            if(!isset($_POST["searchStr"])) $return = getPosts($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, posts.url_post_img FROM $table_posts ORDER BY id_post DESC LIMIT $rowNum, $rowCount");
            else {
                $searchStr = $connect->db_connect->real_escape_string($_POST["searchStr"]);
                is_numeric($searchStr) ? intval($searchStr) < 150 ? $searchStr = date("Y") - intval($searchStr) : 0 : 0;

                $return = getPosts($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, posts.url_post_img, login, users.date AS userDate, city FROM $table_posts INNER JOIN users ON posts.id_user = users.id_user WHERE posts.date like '%$searchStr%' OR posts.title like '%$searchStr%' OR posts.category like '%$searchStr%' OR users.login like '%$searchStr%' OR users.date like '%$searchStr%' OR users.city like '%$searchStr%' ORDER BY id_post DESC LIMIT $rowNum, $rowCount");

                foreach ($return["posts"] as &$post) {
                    $user = getUserData($connect, $table_users, $post["id_user"])["user"];

                    if(empty($return["users"])) $post["user"] = $user;
                    else foreach($return["users"] as &$item) if($item["id_user"] != $user["id_user"]) $post["user"] = $user;
                }
            }

            // SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, login, users.date AS userDate, city FROM posts INNER JOIN users ON posts.id_user = users.id_user WHERE posts.date like '%patszy%' OR posts.title like '%patszy%' OR posts.category like '%patszy%' OR users.login like '%patszy%' OR users.date like '%patszy%' OR users.city like '%patszy%' ORDER BY id_post DESC LIMIT 0, 5
            // SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content FROM posts ORDER BY id_post DESC LIMIT 5, 5
        }

        $connect->ConnectClose();

        if (isset($return["error"])) $return["status"] = "error";
        else if (isset($return["warning"])) $return["status"] = "warning";
        else $return["status"] = "success";

        header("Content-Type: application/json");
		echo json_encode($return);
    }

?>