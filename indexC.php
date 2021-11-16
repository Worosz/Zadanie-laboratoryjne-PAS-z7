<!--Tworzenie sesji użytkownika-->
<?php
session_start();
ini_set( 'display_errors', 'Off' ); 
$locationName = $_SESSION['login'].".php";
?>

<html>
<head>
	<meta charset="utf-8" />	
	<title>Woroszyło</title>		
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
</head>
<body>

<form  method="POST" ENCTYPE="multipart/form-data"> 
<input type="file" name="plik"/>
 <input type="submit" value="Wyślij plik"/> 
 </form>

<!--Połączenie funkcji wysyłania pliku i odbierania go na serwerze-->
<?php
if (is_uploaded_file($_FILES['plik']['tmp_name']))
{
echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';

if (isset($_FILES['plik']['type'])) 
{
    echo 'Typ: '.$_FILES['plik']['type'].'<br/>';   
    
}
move_uploaded_file($_FILES['plik']['tmp_name'],$_SERVER[getcwd()].$_FILES['plik']['name']);
}
?>
<!--Funkcja pozwalająca nam na pobieranie pliku z lokalizacji-->
<?php
error_reporting(0);
ini_set('display_errors', 0);

if(isset($_GET['name'])){
    
  $file = basename($_GET['name']);

  if(!$file){
    die('file not found');
  } else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Transfer-Encoding: binary");

    readfile($file);
  }
}
//Funkcja pozwalająca nam na usuwanie plików z folderu
if(isset($_POST['Submit']))
    {
    }      
    foreach($_POST['select'] as $file) {

    if(file_exists($file)) {
        unlink($file); 
    }
    elseif(is_dir($file)) {
        rmdir($file);
    }
}
// Funkcja tworząca listę nazw plików w lokalizacji z wyłączeniem plików php tworzących ten skrypt
$files = array();
$dir = opendir('.');
    while(false != ($file = readdir($dir))) {
        if(($file != ".") and ($file != "..") and ($file != $locationName) and ($file != "error_log") and ($file != ".php") and ($file != "index.php")) { 
                $files[] = $file; 
        }   
    }
    natcasesort($files);
?>
<form id="delete"  method="POST"><br>

<!--Utworzenie wizualnej listy plików i folderów w lokalizacji, wybranie pliku pozwoli nam na pobranie go,
wybranie folderu na otworzenie, zaznaczenie checkboxa na wybranie plików do usunięcia.-->    

<?php
echo '<table>'; 

for($i=0; $i<count($files); $i++) {   
		$a_href = "index.php?name=/".$files[$i];
		$newDirectory = $files[$i]."/";

			if(!is_dir($files[$i])){echo '
            <div class="select-all-col"><input name="select[]" type="checkbox" class="select" value="'.$files[$i].'"/>
            <a href="'.$a_href.'" style="cursor: pointer;">'.$files[$i].'</a></div>';}
			else{
				$newIndex = $newDirectory."index.php";
				echo'
			<a class="button" href="'.$newIndex.'">'.$files[$i].'</a>
            </br> ';}'
        ';}
echo '</table>';

?>
<br>
<button type="submit" form="delete" value="Submit">Usuń pliki</button><br>
</form><br>
<!--Formularz tworzenie podkatalogu-->
<form method="POST">
<input type='text' name='mkdir' id="mkdir">
<input type="submit" value="Utworz katalog"/>
</form>
<?php
// Funkcja odpowiedzialna za tworzenie podkatalogu oraz kopiowanie pliku index.php do noweg folderu
	 $katalogName = $_POST['mkdir'];
	 if(!empty($katalogName)){
	 $locationMain = getcwd();
	 $locationCreate = $locationMain."/".$katalogName;
	 mkdir("$locationCreate");
	 $fileName = "index.php";
	 $locationMainHome ="/Users/Jakub/Desktop\zad_7/";   ////////////////////////////////// /home/virtualki/203864/zd7/
	 $sourceFile = $locationMainHome."/"."indexC.php";
	 $destFile = $locationMain."/".$katalogName."/".$fileName;
	 copy($sourceFile,$destFile);}
?>
<!--Plik zmieniony o możliwość powrotu do foleru wyżej-->
<br/><a href="../">Cofnij</a>
</body>
</html>