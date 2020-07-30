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
            if(!isset($_POST["searchStr"])) $return = getPost($connect, "SELECT * FROM $table_posts");
            else {
                $searchStr = $connect->db_connect->real_escape_string($_POST["searchStr"]);
                $return = getPost($connect, "SELECT * FROM posts INNER JOIN users ON posts.id_user = users.id_user WHERE posts.date like '%$searchStr%' OR posts.title like '%$searchStr%' OR posts.category like '%$searchStr%' OR users.login like '%$searchStr%' OR users.date like '%$searchStr%' OR users.city like '%$searchStr%'");
            }

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