<?php

    require '../init.php';
    require '../Connection.php';
    require '../functions/userFunctions.php';
    require '../functions/postFunctions.php';

    function countDate($date) {
        $dateTab = explode(",", $_POST["date"]);

        foreach($dateTab as &$date) $date = date("Y")-$date;
        $dateTab = array_reverse($dateTab);

        return $dateTab;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        $connect = new Connection($db_user, $db_password, $db_name);
        $return = $connect->ConnectOpen();

        if(!isset($return["error"])) {
            isset($_POST["rowNum"]) ? $rowNum = $connect->db_connect->real_escape_string($_POST["rowNum"]) : $rowNum = 0;
            isset($_POST["rowCount"]) ? $rowCount = $connect->db_connect->real_escape_string($_POST["rowCount"]) : $rowCount = 1;

            if(empty($_POST["searchStr"])) {
                if(isset($_POST["postId"]) || isset($_POST["login"]) || isset($_POST["city"]) || isset($_POST["dateFrom"]) || isset($_POST["dateTo"]) || isset($_POST["title"]) || isset($_POST["category"])){
                    $tab_query = [];
                    $year = date("Y");

                    if (isset($_POST["postId"])) array_push($tab_query, "posts.id_post = '".$connect->db_connect->real_escape_string($_POST["postId"])."'");
                    if (isset($_POST["login"])) array_push($tab_query, "users.login like '%".$connect->db_connect->real_escape_string($_POST["login"])."%'");
                    if (isset($_POST["city"])) array_push($tab_query, "users.city like '%".$connect->db_connect->real_escape_string($_POST["city"])."%'");
                    if (isset($_POST["dateFrom"])) $date_to = $year - $connect->db_connect->real_escape_string($_POST["dateFrom"]);
                    else $date_to = $year - 16;
                    if (isset($_POST["dateTo"])) $date_from = $year - $connect->db_connect->real_escape_string($_POST["dateTo"]);
                    else $date_from = $year - 100;
                    array_push($tab_query, 'users.date BETWEEN '.$date_from.' AND '.$date_to);
                    if (isset($_POST["title"])) array_push($tab_query, "posts.title like '%".$connect->db_connect->real_escape_string($_POST["title"])."%'");
                    if (isset($_POST["category"])) array_push($tab_query, "posts.category = '".$connect->db_connect->real_escape_string($_POST["category"])."'");

                    $where_query = implode(" AND ", $tab_query);

                    $return = getPosts($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, posts.url_post_img FROM $table_posts INNER JOIN users ON posts.id_user = users.id_user WHERE $where_query ORDER BY id_post DESC LIMIT $rowNum, $rowCount");
                } else {
                    $return = getPosts($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, posts.url_post_img FROM $table_posts INNER JOIN users ON posts.id_user = users.id_user ORDER BY id_post DESC LIMIT $rowNum, $rowCount");
                }
            } else {
                $searchStr = $connect->db_connect->real_escape_string($_POST["searchStr"]);

                $return = getPosts($connect, "SELECT id_post, posts.id_user, posts.date AS postDate, title, category, content, posts.url_post_img FROM $table_posts INNER JOIN users ON posts.id_user = users.id_user WHERE posts.date like '%$searchStr%' OR posts.title like '%$searchStr%' OR posts.category like '%$searchStr%' OR users.login like '%$searchStr%' OR users.date like '%$searchStr%' OR users.city like '%$searchStr%' ORDER BY id_post DESC LIMIT $rowNum, $rowCount");
            }

            if(!empty($return["posts"])) {
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