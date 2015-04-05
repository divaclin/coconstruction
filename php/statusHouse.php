<?php require_once('app.php');?>
<?php 
if(isNotBlock()){
    App::db_connect();
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