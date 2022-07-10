<?php if($_GET['step'] == 4 and $_GET['delete'] == 1) { 

$message = "
   <div id='deleteAdm' class='alert alert-info mb-0' role='alert'>
		Директория INSTALL успешно удалена!
	</div>
	<script>setTimeout(() => {  document.getElementById('deleteAdm').classList.add('hidden'); }, 3000); </script>
   ";

?>
	<script>
		var audio = new Audio(); 
		audio.src = 'sound/delete.mp3'; 
		audio.autoplay = true; 
	</script>
<?php } ?>

<?
ini_set("display_errors",0);
error_reporting(E_ALL);

if(isset($_GET['step']))$step = $_GET['step']; else $step = 1;

$phpversion = phpversion();

if(isset($_POST['nameSERVER']))$nameSERVER = $_POST['nameSERVER'];
if(!isset($_POST['nameSERVER']))$nameSERVER = 'localhost';
if(isset($_POST['nameCharset']))$nameCharset = $_POST['nameCharset'];
if(!isset($_POST['nameCharset']))$nameCharset = 'utf8';
if(isset($_POST['nameDB']))$nameDB = $_POST['nameDB'];
if(isset($_POST['nameUSER']))$nameUSER = $_POST['nameUSER'];
if(isset($_POST['passUSER']))$passUSER = $_POST['passUSER'];

if(isset($_POST['nameSERVER']) and isset($_POST['nameCharset']) and isset($_POST['nameDB']) and isset($_POST['nameUSER']) and isset($_POST['passUSER']))
{

	$dbh = mysqli_connect($nameSERVER, $nameUSER, $passUSER, $nameDB);

	if($dbh == true) {
	   
		   $message = "
		   <div id='successDB' class='alert alert-success mb-0' role='alert'>
				<strong>Отлично!</strong> Успешное подключение к базе данных! Таблицы созданы.
			</div>
			<script>setTimeout(() => {  document.getElementById('successDB').classList.add('hidden'); }, 3000); </script>
		   ";
		   
		   $connection = 1;
		   
		   $sound = 'success-db';
		   
	$dbNEW = "<?php

	\$dsn = \"mysql:host=".$nameSERVER.";dbname=".$nameDB."\";
	\$pdo = new PDO(\$dsn, \"".$nameUSER."\", \"".$passUSER."\");

	?>";
	
	$url_path = __DIR__ . "../../configDB.php";

	if(!file_exists($url_path)) {
		
		file_put_contents($url_path, $dbNEW);

	}
	
		$filename = 'todolist.sql';
		
		mysqli_select_db($dbh,$nameDB);
		$templine = '';
		$lines = file($filename);
		foreach ($lines as $line){
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
				$templine .= $line;
			if (substr(trim($line), -1, 1) == ';'){
				mysqli_query($dbh,$templine);
				$templine = '';
			}
		}

	} else {
		$message = "
		   <div id='errorDB' class='alert alert-danger mb-0' role='alert'>
				<strong>Что-то пошло не по плану?</strong> Проверьте данные для подключения к базе данных!
			</div>
			<script>setTimeout(() => {  document.getElementById('errorDB').classList.add('hidden'); }, 3000); </script>
		   ";
		
		$sound = 'error-db';
	}
}

if(isset($_POST['seotitle']))$seotitle = $_POST['seotitle'];

if(isset($_POST['email']))$email = $_POST['email'];

if(isset($_POST['password']))$passwordAdmin = $_POST['password'];

if(isset($seotitle) and isset($email) and isset($passwordAdmin))
{
	include_once __DIR__ . "../../configDB.php";
	
	$passwordAdmin = md5($passwordAdmin);
	
	$id_email = 2;
    $sql = "UPDATE `setting` SET `value`='$email' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id_email]);

	$id_seotitle = 1;
	$sql = "UPDATE `setting` SET `value`='$seotitle' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id_seotitle]);

	$id_user = 1;
	$sql = "UPDATE `user` SET `email`='$email' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id_user]);

	$sql = "UPDATE `user` SET `password`='$passwordAdmin' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id_user]);
	
	$role = 'admin';
	$sql = "UPDATE `user` SET `role`='$role' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id_user]);

	$hash = 'a8bb3f3fe619f9c39a8e14f9486c64b2';
	$sql = "UPDATE `user` SET `hash`='$hash' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id_user]);

	if(isset($_POST['password']))$passwordAdmin = $_POST['password'];
	
	$connection = 2;
	$sound = 'success-adm';
}


	if($_GET['step'] == 4){
		
		
		
		include_once __DIR__ . "../../configDB.php";
	
		$query = $pdo->query('SELECT * FROM `user`');

		while($row = $query->fetch(PDO::FETCH_OBJ)) {
					$email = $row->email;
		};

		$passwordAdmin = $_GET['password'];

		

	}
	
	if($_GET['delete'] == 1){

		
		
		$path_install_dir = __DIR__;

		function recursiveRemoveDir($dir) {

			$includes = glob($dir.'/*');

			foreach ($includes as $include) {

				if(is_dir($include)) {

					recursiveRemoveDir($include);
				}

				else {

					unlink($include);
				}
			}

			rmdir($dir);
		}
		
		recursiveRemoveDir($path_install_dir);
		
		header("Location: login.php");
	}
	
?>