<?php

class Connection {
  public $db_user;
  public $db_password;
  public $db_name;
  public $db_connect;
  public $name;
  public $surname;
  public $email;
  public $password;
  public $bdate;
  public $city;
  public $gender;
  public $guardian;

  function __construct($user, $password, $db) {
    $this->db_user = $user;
    $this->db_password = $password;
    $this->db_name = $db;
  }

  function ConnectionOpen() {
    $this->db_connect = new mysqli("localhost", $this->db_user, $this->db_password, $this->db_name) or die ("Brak polaczenia z serwerem MySQL.");

    if ($this->db_connect->connect_errno) echo "Failed to connect to MySQL: (" . $this->db_connect->connect_errno . ") " . $this->db_connect->connect_error;
  }

  function ConnectionClose() {$this->db_connect->close();}

  function getPostData() {
    $this->name = $_POST["name"];
    $this->surname = $_POST["surname"];
    $this->email = $_POST["email"];
    $this->password = md5($_POST["password"]);
    $this->bdate = $_POST["bdate"];
    $this->city = $_POST["city"];
    $this->gender = ($_POST["gender"]=="male") ? 1 : 0;
    $this->guardian = isset($_POST["guardian"]) ? true : false;
  }

  function createUser($name, $surname, $email, $password, $bdate, $city, $gender) {
    $sql_create_user = "INSERT INTO users (id_user, name, surname, email, password, bdate, city, gender) VALUES ('', '$name', '$surname', '$email', '$password', '$bdate', '$city', '$gender')";

    if ($this->db_connect->query($sql_create_user) === TRUE) echo "New record created successfully";
    else echo "Error: " . $sql_create_user . "<br>" . $this->db_connect->error;
  }

  function checkEmail() {
    $sql_email = "SELECT * FROM users WHERE email='$this->email'";
    $check_email = $this->db_connect->query($sql_email);

    if($check_email->num_rows == 0) {
      $this->createUser($this->name, $this->surname, $this->email, $this->password, $this->bdate, $this->city, $this->gender);
    } else if($check_email->num_rows != 0) {
      while($row = $check_email->fetch_assoc()) {
        echo "User with same email exists: <br>";
        echo "Id_user: " . $row["id_user"] . " | Name: " . $row["name"] . " | Surname: " . $row["surname"] . "<br>";
      }
    } else echo "Error: " . $sql_email . "<br>" . $this->db_connect->error;
  }

  function init() {
    $this->getPostData();

    if(!$this->guardian) {
      $this->ConnectionOpen();
      $this->checkEmail();
      $this->ConnectionClose();
    }
  }
}

$user = "root";
$password = "1234";
$db = "project";

$connect = new Connection($user, $password, $db);

$connect->init();

?>