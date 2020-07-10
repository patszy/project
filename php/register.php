<?php

$user = 'root';
$pass = '1234';
$db = 'project';

$db_connect =  new mysqli("localhost", $user, $pass, $db) or die ("Brak polaczenia z serwerem MySQL.");
if ($db_connect->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $db_connect->host_info . "<br>";

$sql = "INSERT INTO users (id_user, name, surname, email, password, bdate, city, gender) VALUES ('', '".$_POST["name"]."', '".$_POST["surname"]."', '".$_POST["email"]."', '".$_POST["password"]."', '".$_POST["bdate"]."', '".$_POST["city"]."', '".$_POST["gender"]."')";

if ($db_connect->query($sql) === TRUE) echo "New record created successfully";
else echo "Error: " . $sql . "<br>" . $db_connect->error;

$db_connect->close();

?>