<?php

    //editUser
    function validateFile($file) {
        $return = [];
        $max_size = $_POST["MAX_FILE_SIZE"]/1000000;

        if ($file["url_portrait"]["error"] > 0) {
        switch ($file["url_portrait"]["error"]) {
            case 1: $return["error"] = "Max wielkość zdjęcia ".$max_size."MB";
                break;
            case 2: $return["error"] = "Max wielkość zdjęcia ".$max_size."MB";
                break;
            case 3: $return["error"] = "Zdjęcie wysłane częściowo.";
                break;
            case 4: $return["error"] = "Nie wysłano zdjęcia.";
                break;
            default: $return["error"] = "Błąd podczas wysyłania.";
            break;
        }
        } else if ($file["url_portrait"]["type"] != "image/jpeg") $return["error"] = "Dopuszczalne rozszeczenie zdjęcia .jpg/.jpeg.";

        if(!isset($return["error"])) $return["success"] = "Zdjęcie prawidłowe.";

        return $return;
    }

    function saveFile($file, $url) {
        $return = [];

        if(is_uploaded_file($file["url_portrait"]["tmp_name"])) {
            if(!move_uploaded_file($_FILES["url_portrait"]["tmp_name"], ".".$url)) $return["warning"] = "Nie skopiowano zdjęcia do katalogu.";
        } else $return["error"] = "Nie zapisano zdjęcia.";

        if(!isset($return["error"])) $return["success"] = "Zapisano zdjęcie.";

        return $return;
    }

?>