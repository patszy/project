<?php

    require 'init.php';
    require 'Connection.php';

    function getPost($connect, $query) {
        $sql_posts = $query;
        $query_posts = $connect->db_connect->query($sql_posts);
        $return = [];
        $return["posts"] = [];

        if($query_posts->num_rows != 0) {
            $return["success"] = "Posty załadowane.";
            while($row = $query_posts->fetch_assoc()) array_push($return["posts"], $row);
        }
        else if($query_posts->num_rows == 0) $return["warning"] = "Brak postów. ";
        else $return["error"] = "Error: " . $sql_posts . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function getUser($connect, $table, $id) {
        $sql_users = "SELECT email, login, city, date FROM $table WHERE id_user = '$id'";
        $query_users = $connect->db_connect->query($sql_users);
        $return = [];

        if($query_users->num_rows == 1) {
            $return["success"] = "Got user.";
            while($row = $query_users->fetch_assoc()) $return["user"] = $row;
        }
        else if($query_users->num_rows != 0) $return["warning"] = "No user.";
        else $return["error"] = "Error: " . $sql_users . "<br>" . $connect->db_connect->error;

        return $return;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        $connect = new Connection($db_user, $db_password, $db_name);
        $return = $connect->ConnectOpen();

        if(!isset($return["error"])) {
            isset($_POST["rowNum"]) ? $rowNum = $connect->db_connect->real_escape_string($_POST["rowNum"]) : $rowNum = 0;
            isset($_POST["rowCount"]) ? $rowCount = $connect->db_connect->real_escape_string($_POST["rowCount"]) : $rowCount = 1;

            if(!isset($_POST["searchStr"])) $return = getPost($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content FROM $table_posts WHERE id_post<=(SELECT MAX(id_post) FROM $table_posts) - $rowNum ORDER BY id_post DESC LIMIT $rowCount");
            else {
                $searchStr = $connect->db_connect->real_escape_string($_POST["searchStr"]);
                is_numeric($searchStr) ? intval($searchStr) < 150 ? $searchStr = date("Y") - intval($searchStr) : 0 : 0;

                $return = getPost($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, login, users.date AS userDate, city FROM posts INNER JOIN users ON posts.id_user = users.id_user WHERE id_post<=(SELECT MAX(id_post) FROM posts) - $rowNum AND posts.date like '%admin%' OR posts.title like '%admin%' OR posts.category like '%admin%' OR users.login like '%admin%' OR users.date like '%admin%' OR users.city like '%admin%' ORDER BY id_post DESC LIMIT $rowCount");
            }
            // SELECT * FROM $table_posts WHERE id_post<=(SELECT MAX(id_post) FROM $table_posts) - $rowNum ORDER BY id_post DESC LIMIT 10
            // SELECT * FROM posts INNER JOIN users ON posts.id_user = users.id_user WHERE posts.date like '%%' OR posts.title like '%%' OR posts.category like '%%' OR users.login like '%%' OR users.date like '%%' OR users.city like '%%' AND id_post<=(SELECT MAX(id_post) FROM posts) - 10 ORDER BY id_post DESC LIMIT 10

            $return["users"] = [];

            foreach ($return["posts"] as &$post) {
                $user = getUser($connect, $table_users, $post["id_user"])["user"];

                if(empty($return["users"])) $post["user"] = $user;
                else foreach($return["users"] as &$item) if($item["id_user"] != $user["id_user"]) $post["user"] = $user;
            }
        }

        $connect->ConnectClose();

        if (isset($return["error"])) $return["status"] = "error";
        else if (isset($return["warning"])) $return["status"] = "warning";
        else $return["status"] = "success";

        header("Content-Type: application/json");
		echo json_encode($return);
    }

?>