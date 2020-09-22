<?php

    //editUser
    function updateUser($connect, $table_users, $id_user, $login, $email, $password, $city, $url_portrait) {
        $tab_query = [];

        if (!empty($login)) array_push($tab_query, "login = '$login'");
        if (!empty($email)) array_push($tab_query, "email = '$email'");
        if (!empty($password)) array_push($tab_query, "password = '$password'");
        if (!empty($city)) array_push($tab_query, "city = '$city'");
        if (!empty($url_portrait)) array_push($tab_query, "url_portrait = '$url_portrait'");

        $set_query = implode(", ", $tab_query);

        $sql_user = "UPDATE $table_users SET $set_query WHERE id_user='$id_user'";
        $query_update_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_update_user === true) {
            $return["success"] = "Zaktualizowano użytkownika.";
            $return["updateUser"] = true;
        } else if($query_update_user === false) $return["warning"] = "Nie zaktualizowano użytkownika.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function deleteUser($connect, $table_users, $table_posts, $id_user) {
        $sql_user = "DELETE FROM $table_users WHERE id_user = $id_user";
        $query_delete_user = $connect->db_connect->query($sql_user);
        $return = [];

        if($query_delete_user === true) {
            $return["success"] = "Usunięto użytkownika.";
            $return["deleteUser"] = true;
            deleteUserPost($connect, $table_posts, $id_user);
        } else if($query_delete_user === false) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_user . "<br>" . $connect->db_connect->error;

        return $return;
    };

    //deletePost
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

    function getUserId($connect, $table, $id_post) {
        $sql_posts = "SELECT id_user FROM $table WHERE id_post = '$id_post'";
        $query_posts = $connect->db_connect->query($sql_posts);
        $return = [];

        if($query_posts->num_rows == 1) {
            $return["success"] = "Post usunięty.";
            while($row = $query_posts->fetch_assoc()) $return["id_user"] = $row["id_user"];
        }
        else if($query_posts->num_rows == 0) $return["warning"] = "Brak postu.";
        else $return["error"] = "Error: " . $sql_posts . "<br>" . $connect->db_connect->error;

        return $return;
    }

    //getNewUsers
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

    //getPostsData
    function getUserData($connect, $table, $id) {
        $sql_users = "SELECT email, login, city, date, url_portrait FROM $table WHERE id_user = '$id'";
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

    //login
    function getUser($connect, $table, $login) {
        $sql_login = "SELECT * FROM $table WHERE login='$login'";
        $query_login = $connect->db_connect->query($sql_login);
        $return = [];
        $return["user"] = [];

        if($query_login->num_rows == 1) {
            $return["success"] = "Użytkownik istnieje.";
            $return["user"] = mysqli_fetch_assoc($query_login);
        } else if($query_login->num_rows == 0) $return["warning"] = "Użytkownik nie istnieje.";
        else $return["error"] = "Error: " . $sql_login . "<br>" . $connect->db_connect->error;

        return $return;
    };

    function checkUserData($login, $password, $userLogin, $userPassword) {
        $return = [];

        if(password_verify($password, $userPassword) && $login === $userLogin) $return["success"] = "Dane poprawne.";
        else if(!password_verify($password, $userPassword) || $login !== $userLogin) $return["error"] = "Niewłaściwe dane.";
        else $return["error"] = "Error: " . $sql_login . "<br>" . $connect->db_connect->error;

        return $return;
    }

    //passRecovery
    function updateUserPassword($connect, $table, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update_password = "UPDATE $table SET password = '$password' WHERE email = '$email';";
        $return = [];

        if ($connect->db_connect->query($sql_update_password) === TRUE) $return["success"] = "Hasło odnowione.";
        else $return["errors"] = "Error: " . $sql_update_password . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function isUserEmail($connect, $table, $email) {
        $sql_email = "SELECT * FROM $table WHERE email='$email'";
        $query_email = $connect->db_connect->query($sql_email);
        $return = [];

        if($query_email->num_rows == 1) $return["success"] = "Email jest w bazie.";
        else if($query_email->num_rows == 0) $return["error"] = "Email nie istnieje.";

        return $return;
    }

    //register
    function isUserLoginOrEmail($connect, $table, $email, $login) {
        $sql_check = "SELECT * FROM $table WHERE login='$login' OR email='$email'";
        $query_check = $connect->db_connect->query($sql_check);
        $return = [];

        if($query_check->num_rows == 0) $return["success"] = "Email lub Login nie istnieje.";
        else if($query_check->num_rows != 0) $return["warning"] = "Email lub Login już istnieje.";
        else $return["error"] = "Error: " . $sql_check . "<br>" . $connect->db_connect->error;

        return $return;
    }

    function createUser($connect, $table, $login, $email, $password, $date, $city) {
        $url_array = ["./assets/img/portraits/undraw_male_avatar_323b.svg", "./assets/img/portraits/undraw_female_avatar_w3jk.svg"];
        $url_portrait = $url_array[array_rand($url_array, 1)];
        $sql_create_user = "INSERT INTO $table SET id_user='', login='$login', email='$email', password='$password', date='$date', city='$city', permission = '0', url_portrait='$url_portrait'";
        $return = [];

        if ($connect->db_connect->query($sql_create_user) === TRUE) $return["success"] = "Rejestracja pomyślna.";
        else  $return["errors"] = "Error: " . $sql_create_user . "<br>" . $connect->db_connect->error;

        return $return;
    }

?>