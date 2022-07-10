<?php

  require 'configDB.php';

  if($_POST['check_task']) {
	$id = $_POST['check_task'];
    $sql = "UPDATE `tasks` SET `check`='1' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id]);
  }

  if($_POST['uncheck_task']) {
	$id = $_POST['uncheck_task'];
    $sql = "UPDATE `tasks` SET `check`='2' WHERE `id`= ?";
	$query = $pdo->prepare($sql);
	$query->execute([$id]);
  }
 
?>