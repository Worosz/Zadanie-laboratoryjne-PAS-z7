<?php
// Tworzenie sesji logowania
ini_set( 'display_errors', 'Off' ); 
session_start();
// Ustanawianie blokady logowania się na konto przy pomocy różnicy czasu zapisanego w bazie danych
if (isset($_SESSION["locked"]))
{
    $difference = time() - $_SESSION["locked"];
    if ($difference > 30)
    {
        unset($_SESSION["locked"]);
        unset($_SESSION["login_attempts"]);
    }
}
// Logowanie się i ustanowienie sesji
$_SESSION["zalogowany"];
if(empty($_SESSION["zalogowany"]))$_SESSION["zalogowany"]=0;


$adress_ip_hosta = $_SERVER["REMOTE_ADDR"];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Pobieranie systemu operacyjnego
function getOS() { 

    global $user_agent;

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}
// Pobieranie przeglądarki
function getBrowser() {

    global $user_agent;

    $browser        = "Unknown Browser";

    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

// Łączenie się z bazą danych
$dbhost="mysql01.jakwor001.beep.pl";
$dbuser="zad7user";
$dbpassword="zad7haslo";
$dbname="zadanie_7";

$polaczenie = @mysql_connect($dbhost, $dbuser, $dbpassword)
or die('Blad <br />Blad: '.mysql_error());
$db = @mysql_select_db($dbname, $polaczenie) 
or die('Blad <br />Blad: '.mysql_error());

// Tworzymy formularz logowania
function FormularzLogowania($formularz_logowania=""){
	echo "$formularz_logowania<br>";
	echo "<form action='/zad_7/login.php' method=post>";
	echo "Login: <input type=text name=login required> <br>";
	echo "Haslo: <input type=password name=haslo required><br><br>";
	if ($_SESSION["login_attempts"] >2) {
		$_SESSION["locked"] = time();
	echo "</form>";
	echo "<input type=submit value='Zaloguj'>";
	echo "<p style='color: red;'> Blokada Logowania </p>";
	}
	else {
		echo "<input type=submit value='Zaloguj'>";
		echo "</form>";
		}
	echo "<a href='/zd7/rejestracja.php'><br>Zarejestruj sie</a>";
    	echo "<br><br><a href='../index.php';>Powrot</a>";
}

?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta charset="utf-8" />	
	<title>Woroszyło</title>		
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
</head>
<body>
Panel logowania:<br/>
<?php
$login_user = $_POST['login'];
$haslo_user = $_POST['haslo'];
if($_GET["wyloguj"]=="tak"){
		$_SESSION["zalogowany"]=0;
		echo "Zostales wylogowany z serwisu";}
		
// Tworzymy panel logowania i dodajemy warunki sprawdzające ilość prób logowania		
if($_SESSION["zalogowany"]!=1){
	$query = "SELECT * FROM users WHERE login = '$login_user'";
	$result = mysql_query($query);
	if(!empty($login_user) &&  !empty($haslo_user)){
		if(mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_array($result)) {
			$check_pass = $row['haslo'];
			}
			if ($haslo_user == $check_pass)
			{	
				$_SESSION['login']=$login_user;
				$_SESSION["login_attempts"] = 0;
				$update = "INSERT INTO klient_logi (login) VALUES ('$login_user')";
				$send_update = mysql_query($update);
				$location = "/zd7/".$login_user."/"."index.php";
				header("Location: $location");
			}
			else
			{		
				$update1 = "INSERT INTO klient_logi (login,bledne_logowanie) VALUES ('$login_user',1)";
				$send_zupdate = mysql_query($update1);
				$_SESSION["login_attempts"] += 1;
				echo FormularzLogowania("Wprowadzone haslo jest niepoprawne");
			}
		}
		else
		{
			echo FormularzLogowania("Wprowadzony login jest niepoprawny");
	}}	
	else FormularzLogowania();
}			
else{$_SESSION["zalogowany"]=0;}
?>
</body>
</html>
<?php mysql_close();?>