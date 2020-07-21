<?php
	$name = $_GET['name'];
	$email = $_GET['email'];
	$tresc= $_GET['tresc'];
	$data = date("Y-m-d h:i:sa");
	$check = $_GET['ckeck'];

	if($imie and $email and $tresc and !$ckeck) {

	    $connection = @mysqli_connect('localhost', 'root', '1234')
	    or die('Brak połączenia z serwerem MySQL');
	    $db = @mysqli_select_db('post', $connection)
	    or die('Nie mogę połączyć się z bazą danych');

	    $ins = @mysqli_query("INSERT INTO post SET imie='$imie', email='$email', tresc='$tresc', data='$data'");
	    
	    if($ins) echo "Rekord został dodany poprawnie";
	    else echo "Błąd nie udało się dodać nowego rekordu";
	    
	    mysql_close($connection);
	}
?>