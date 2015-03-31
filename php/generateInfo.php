<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $sql='SELECT * FROM user INNER JOIN building ON user.bid=building.bid WHERE uid=:uid';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
			 ':uid'=>$_POST['uid']
	     ));
		 $result=$stmt->fetch(PDO::FETCH_ASSOC);
		 echo json_encode($result);
	 }
?>