<?php require_once('app.php');?>
<?php
  if(isset($_GET['block'])){
	  try{
	 	 App::db_connect();
		 App::beginTranscation();
	     $sql='UPDATE `status` SET `done`= CONCAT(`done`,"D") WHERE `statusid`='.$_GET['id'];
         App::$dbn->exec($sql);
         App::$dbn->commit();
	 	 echo 'update live in';
	     App::db_disconnect();
		 
	  }
	  catch(PDOException $e){
		 App::$dbn->rollBack();
	     App::db_disconnect();
		 echo $e->getMessage();
	  }
  }
?>