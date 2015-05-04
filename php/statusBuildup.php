<?php require_once('app.php');?>
<?php 
if(isNotBlock()){
    App::db_connect();
	
	$sql='DELETE FROM status WHERE `device`=:device';
	$stm=App::$dbn->prepare($sql);
	$stm->execute(array(
      	':device'=>$_POST['device']
  	));
	
    $sql='INSERT INTO status (device,behavior) VALUES(:device,:behavior)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
       ':device'=>$_POST['device'],
       ':behavior'=>'BUILD_UP'
    ));
}
?>