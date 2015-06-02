<?php require_once('app.php');?>
<?php
    if(isNotBlock()){
        App::db_connect();
		$sql='UPDATE `user` SET `bid`=:bid WHERE `uid`=:uid';
		$stmt=App::$dbn->prepare($sql);
		$stmt->execute(array(
				':bid'=>$_POST['bid'],
                ':uid'=>$_POST['uid']
		));
	}
?>