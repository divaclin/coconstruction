<?php require_once('app.php');?>
<?php 
if(isNotBlock()){
    App::db_connect();
	
	// $sql='DELETE FROM status WHERE `device`=:device';
	// $stm=App::$dbn->prepare($sql);
	// $stm->execute(array(
	//       	':device'=>$_POST['device']
	//   	));
	

	
	
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
	
    $sql='SELECT * FROM user WHERE uid=:uuid';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
    	':uuid'=>$_POST['uuid']
    ));
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
	
	
    $sql='INSERT INTO status (device,behavior,object_type,object,oid) VALUES(:device,:behavior,:object_type,:object,:oid)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
       ':device'=>$_POST['device'],
       ':behavior'=>'LIVE_IN',
	   ':object_type'=>'B',
	   ':object'=>'{"bid":"'.$_POST['bid'].'","bname":"'.$_POST['bname'].'","uname":"'.$result['name'].'"}',
	   ':oid'=>$_POST['bid']
    ));
    App::db_disconnect();
	
}
?>