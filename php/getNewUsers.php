<?php

    require 'init.php';
    require 'Connection.php';

    function getUsers($connect, $table) {
        $sql_user = "SELECT login, url_portrait FROM $table ORDER BY id_user DESC LIMIT 5";
        $query_user = $connect->db_connect->query($sql_user);
        $return = [];
        $return["users"] = [];

        if($query_user->num_rows != 0) {
            $return["success"] = "Użytkownik istnieje.";
            while($row = $query_user->fetch_assoc()) array_push($return["users"], $row);
        }
        else if($query_user->num_rows == 0) $return["warning"] = "Brak użytkowników.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    }

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $return = [];

        if(isset($_POST["guardian"])) {
            $return["success"] = "Guardian!";
        } else {
            $connect = new Connection($db_user, $db_password, $db_name);
            $return = $connect->ConnectOpen();

            if(!isset($return["error"])) $return = getUsers($connect, $table_users);
            // print_r();
            $connect->ConnectClose();
        }

        if (isset($return["error"])) { $return["status"] = "error"; }
        else if (isset($return["warning"])) { $return["status"] = "warning"; }
        else { $return["status"] = "success"; }

		header("Content-Type: application/json");
		echo json_encode($return);
	}

?>