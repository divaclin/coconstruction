<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $sql='SELECT * FROM building WHERE cid=:cid';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
			 ':cid'=>$_POST['cid']
	     ));
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 echo json_encode($result);
	 }
?>