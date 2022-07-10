<?php
	$config = __DIR__ . "/configDB.php";

	if(!file_exists($config)) {
	header("Location: /install/");
	}

	ini_set("display_errors",1);
	error_reporting(E_ALL);
	
	require 'configDB.php';

	if (isset($_COOKIE['hash'])) {
		$hash = $_COOKIE['hash'];
		$sql = $pdo->prepare('SELECT * FROM `user` WHERE `hash` = ?');
		$sql->execute([$hash]);
		$array = $sql->fetch(PDO::FETCH_ASSOC);

		if(!$array['hash']) {
			setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
			header("Location: login.php");
		}
	} else {
		header("Location: login.php");
	}
	
	$id = 1;
	$sql = $pdo->prepare('SELECT * FROM `setting` WHERE `id` = ?');
	$sql->execute([$id]);
	$array = $sql->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $array["value"]; ?></title>
  <link rel="shortcut icon" href="img/favicon.ico">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
</head>
<body>

<section class="" style="background-color: #e2d5de;min-height: 100vh!important; position:relative;">
  <div class="container py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">

        <div class="card" style="border-radius: 15px;">
          <div class="card-body p-5">

            <h5 class="mb-3"><?php echo $array["value"]; ?></h5>

            <form class="d-flex justify-content-center align-items-center mb-4" action="/add.php" method="post">
              <div class="form-outline flex-fill">
                <input type="text" name="task" id="task" id="form3" class="form-control form-control-lg" placeholder="Что вам нужно сделать сегодня?"/>
                
              </div>
              <button type="submit" name="sendTask" class="btn btn-primary btn-lg ms-2">Добавить</button>
            </form>

            <ul class="list-group mb-0">
				<?php
				  
				  $query = $pdo->query('SELECT * FROM `tasks` ORDER BY `id` DESC');
				  while($row = $query->fetch(PDO::FETCH_OBJ)) {
					if($row->check == 1){
						$checked = 'checked';
					} else {
						$checked = '';
					}

					if($row->check == 1){
						$through = 'text-decoration:line-through;';
					} else {
						$through = '';
					}

					echo '<li class="list-group-item d-flex justify-content-between align-items-center border-start-0 border-top-0 border-end-0 border-bottom rounded-0 mb-2"><div class="d-flex align-items-center line-through"><input id="'.$row->id.'" class="form-check-input me-2" type="checkbox" value="" aria-label="..." '.$checked.'/><span class="label_task" style="'.$through.'">'.$row->task.'</span></div><a data-mdb-toggle="tooltip" title="Удалить" href="/delete.php?id='.$row->id.'"><i class="fas fa-times text-primary"></i></a></li>';
				  };
				  
				?>
            </ul>

          </div>
        </div>

      </div>
    </div>
  </div>
  <a href="exit.php" type="button" class="btn btn-dark exit">Выход</a>
</section>

    
<script type='text/javascript' src='js/script.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
