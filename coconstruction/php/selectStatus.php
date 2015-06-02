<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $sql='SELECT * FROM status WHERE 1';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 echo json_encode($result);
         App::db_disconnect();
		 
	 }
?>