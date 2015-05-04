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
       ':behavior'=>'LIVE_IN',
	   ':obj_type'=>'B',
	   ':object'=>'{"bid":"'.$_POST['bid'].'"}'
    ));
	
	
    $sql='UPDATE `building` SET `reside`=`reside`+1 WHERE `bid`=:bid';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
		':bid'=>$_POST['bid']
    ));
	
	$sql='UPDATE `user` SET `house`=:bid WHERE `uid`=:uuid';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
		':bid'=>$_POST['bid'],
		':uuid'=>$_POST['uuid']
    ));	
}
?>