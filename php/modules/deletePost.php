<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/userFunctions.php';
    require '../functions/postFunctions.php';

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
                        $return = getUserId($connect, $table_posts, $id_post);

                        if(!isset($return["error"])) if($return["id_user"] == $id_user) $return = deletePost($connect, $table_posts, $id_post);
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