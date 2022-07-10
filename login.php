<?
// Страница авторизации

// Соединямся с БД
require 'configDB.php';

if (isset($_COOKIE['hash'])) {
	$hash = $_COOKIE['hash'];
	$sql = $pdo->prepare('SELECT * FROM `user` WHERE `hash` = ?');
	$sql->execute([$hash]);
	$array = $sql->fetch(PDO::FETCH_ASSOC);

	if($array['hash'] == $_COOKIE['hash']) {
		//setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
		header("Location: /");
	}
}


// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}



if(isset($_POST['submit']))
{
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
	$email = $_POST['email'];
	
	$sql = $pdo->prepare('SELECT * FROM `user` WHERE `email` = ?');
	
	$sql->execute([$email]);
	
	$array = $sql->fetch(PDO::FETCH_ASSOC);

    // Сравниваем пароли
    if($array['password'] === md5($_POST['password']))
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        // Записываем в БД новый хеш авторизации и IP
		
		
		$sql = "UPDATE `user` SET `hash`=:hash WHERE `email`= :email";
		$query = $pdo->prepare($sql);
		$query->execute(['hash' => $hash,'email' => $email]);
		

        // Ставим куки

        setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!!

        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: /"); exit();
    }
    else
    {
        print "Вы ввели неправильный логин/пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Список дел</title>
  <link rel="shortcut icon" href="img/favicon.ico">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
</head>
<body>

<section class="vh-100">
  <div class="container-fluid h-custom h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form method="POST">
          <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <p class="lead fw-normal mb-0 me-3">Вход</p>
            
          </div>

          

          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" name="email" class="form-control form-control-lg"
              placeholder="Введите email адрес" required >
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
            <input type="password" name="password" class="form-control form-control-lg"
              placeholder="Введите пароль" required >
          </div>

          

          <div class="text-center text-lg-start mt-4 pt-2">
            <input name="submit" type="submit" class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Войти">
            
          </div>

        </form>
      </div>
    </div>
  </div>

</section>

</body>
</html>