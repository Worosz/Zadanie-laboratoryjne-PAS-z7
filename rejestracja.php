<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />	
	<title>Woroszyło</title>		
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
</head>
<body>
<!--Tworzymy formularz rejestracji-->
<h1> Zarejestruj się: </h2>
<form method="POST">
<br>
Login:<input type='text' name='user_login' id="user_login" required><br>
Haslo:<input type='password' name='password_v1' id="password_v1" required><br>
Powtorz haslo:<input type="password" name="password_v2" id="password_v2" required><br><br>
<input type="submit" value="Zarejestruj"/>
</form>

<?php
// Ustanawiamy połączenie z bazą danych w celu przesłania danych nowego użytkownika
$dbhost="mysql01.jakwor001.beep.pl";
$dbuser="zad7user";
$dbpassword="zad7haslo";
$dbname="zadanie_7";
    $polaczenie = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
	if ($polaczenie->connect_error) die($polaczenie->connect_error);
	

    $user_login = $_POST['user_login'];
    $password_v1 = $_POST['password_v1'];
    $password_v2 = $_POST['password_v2'];
    // Sprawdzamy, czy wszystkie dane zostały podane
        if ($user_login != NULL && $password_v1 != NULL && $password_v2 != NULL)
        {
    // Sprawdzamy, czy login nie jest zajęty
    $selectLogin = "SELECT login FROM `users` WHERE login='$user_login'";
    $dodajLogin = $polaczenie->query($selectLogin);
    $wpisy = $dodajLogin->num_rows;
	if($wpisy>0) 
        {
            echo "Spróbuj inny login";
	}
    // Sprawdzamy, czy oba hasła są poprawne
	else {if ($password_v1 == $password_v2) 
	{
    // Dodajemy użytkownika do bazy dancyh i tworzymy folder z nazwą użytkownika oraz kopiujemy odpowiedni plik index
            $dodajLoginHaslo = "INSERT INTO `users` (login, haslo) VALUES ('$user_login', '$password_v1')";
            $wyslijLoginHaslo = $polaczenie->query($dodajLoginHaslo);
			$locationMain = getcwd();
			$locationCreate = $locationMain."/".$user_login;
			mkdir("$locationCreate");
			$sourceFile = $locationMain."/"."index.php";
			$destFile = $locationMain."/".$user_login."/"."index.php";
			copy($sourceFile,$destFile);
            mysqli_close($polaczenie);
            if($wyslijLoginHaslo == TRUE)
		{
                    echo "Użytkownik został zarejestrowany, możesz się zalogować"."<br/>";
		}
		else 
                {
                    echo "Rejestracaja nie powiodła się"."<br/>";}
		} 
                else 
                {
                    echo "Hasla musza byc takie same"."<br/>";}
	}	}
?>
<a href='/zad_7/login.php'> Powrot </a>
</body>
</html>