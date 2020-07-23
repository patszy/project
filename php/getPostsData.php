<?php

    require 'init.php';
    require 'Connection.php';

    function getPost($connect, $table) {
        $sql_posts = "SELECT * FROM $table";
        $query_posts = $connect->db_connect->query($sql_posts);
        $return = [];
        $return["posts"] = [];

        if($query_posts->num_rows != 0) {
            $return["success"] = "Got posts.";
            while($row = $query_posts->fetch_assoc()) array_push($return["posts"], $row);
        }
        else if($query_posts->num_rows == 0) $return["warning"] = "No posts.";
        else $return["error"] = "Error: " . $sql_posts . "<br>" . $connect->db_connect->error;

        return $return;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        $connect = new Connection($db_user, $db_password, $db_name);
        $return = $connect->ConnectOpen();

        if(!isset($return["error"])) $return = getPost($connect, $table_posts);

        $connect->ConnectClose();

        if (isset($return["error"])) $return["status"] = "error";
        else if (isset($return["warning"])) $return["status"] = "warning";
        else $return["status"] = "success";

        header("Content-Type: application/json");
		echo json_encode($return);
    }

?>