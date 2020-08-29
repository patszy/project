<?php

    require 'init.php';
    require 'Connection.php';

    function getPost($connect, $table, $id_post) {
        $sql_posts = "SELECT id_user FROM $table WHERE id_post = '$id_post'";
        $query_posts = $connect->db_connect->query($sql_posts);
        $return = [];

        if($query_posts->num_rows == 1) {
            $return["success"] = "Posty załadowane.";
            while($row = $query_posts->fetch_assoc()) $return["id_user"] = $row["id_user"];
        }
        else if($query_posts->num_rows == 0) $return["warning"] = "Brak postów. ";
        else $return["error"] = "Error: " . $sql_posts . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function getUserPermission($connect, $table, $id) {
        $sql_users = "SELECT permission FROM $table WHERE id_user = '$id'";
        $query_users = $connect->db_connect->query($sql_users);
        $return = [];

        if($query_users->num_rows == 1) {
            $return["success"] = "Got permission.";
            while($row = $query_users->fetch_assoc()) $return["permission"] = $row["permission"];
        }
        else if($query_users->num_rows != 0) $return["warning"] = "No permission.";
        else $return["error"] = "Error: " . $sql_users . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function deletePost($connect, $table, $id_post) {
        $sql_post = "DELETE FROM $table WHERE id_post = $id_post";
        $query_delete_post = $connect->db_connect->query($sql_post);
        $return = [];

        if($query_delete_post === true) $return["success"] = "Usunięto post.";
        else if($query_delete_post === false) $return["warning"] = "Nie usunięto postu.";
        else $return["error"] = "Error: " . $sql_post . "<br>" . $connect->db_connect->error;

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
                $id_post = $connect->db_connect->real_escape_string($_POST["post_id"]);
                $id_user = $connect->db_connect->real_escape_string($_POST["user_id"]);

                $return = getUserPermission($connect, $table_users, $id_user);

                if(!isset($return["error"])){
                    if($return["permission"] == 1) $return = deletePost($connect, $table_posts, $id_post);
                    else {
                        $return = getPost($connect, $table_posts, $id_post);

                        if(!isset($return["error"])) if($return["id_user"] == $id_user) $return = deletePost($connect, $table_posts, $id_post);
                    }
                }
            }

            $connect->ConnectClose();
        }

        header('Location: ../index.html');
    }

?>