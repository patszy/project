<?php

    //createPost
    function isPost($connect, $table, $user_id, $title, $category) {
        $sql_post = "SELECT * FROM $table WHERE id_user='$user_id' AND title='$title' AND category='$category'";
        $query_post = $connect->db_connect->query($sql_post);
        $return = [];

        if($query_post->num_rows == 0) { $return["success"] = "Create post is possible."; }
        else if($query_post->num_rows != 0) {
            while($row = $query_post->fetch_assoc()) {
                $return["warning"] = "Na tym koncie istnieje już podobny post.";
            }
        } else {
            $return["error"] = "Error: " . $sql_post . "<br>" . $connect->db_connect->error;
        }

        return $return;
    }

    function createPost($connect, $table, $id, $date, $title, $category, $content, $url_post_img) {
        $sql_create_post = "INSERT INTO $table SET id_post='', id_user='$id', date='$date', title='$title', category='$category', content='$content', url_post_img='$url_post_img'";
        $return = [];

        if ($connect->db_connect->query($sql_create_post) === TRUE) $return["success"] = "Utworzono post.";
        else {
            $return["errors"] = "Error: " . $sql_create_post . "<br>" . $connect->db_connect->error;
        }

        return $return;
    }

    //deletePost
    function deletePost($connect, $table, $id_post) {
        $sql_post = "DELETE FROM $table WHERE id_post = $id_post";
        $query_delete_post = $connect->db_connect->query($sql_post);
        $return = [];

        if($query_delete_post === true) {
            $return["success"] = "Usunięto post.";
            $return["deletePost"] = true;
        }
        else if($query_delete_post === false) $return["warning"] = "Nie usunięto postu.";
        else $return["error"] = "Error: " . $sql_post . "<br>" . $connect->db_connect->error;

        return $return;
    }

    //editUser;
    function deleteUserPost($connect, $table, $id_user) {
        $sql_post = "DELETE FROM $table WHERE id_user = $id_user";
        $query_delete_post = $connect->db_connect->query($sql_post);
        $return = [];

        if($query_delete_post === true) $return["success"] = "Usunięto posty.";
        else if($query_delete_post === false) $return["warning"] = "Posty nie istnieją.";
        else $return["error"] = "Error: " . $sql_post . "<br>" . $connect->db_connect->error;

        return $return;
    }

    //getPostsData
    function getPosts($connect, $query) {
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
?>