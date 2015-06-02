<?php require_once('app.php');?>
<?php
  if(isset($_GET['block'])){
	 try{
		 App::db_connect();
		 App::beginTranscation();
	 	 $sql='SELECT * FROM status WHERE 1';
	 	 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
	 	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 	 echo json_encode($result);
         App::$dbn->commit();
	     App::db_disconnect();
	 
	 }
   	 catch(PDOException $e){
   		 App::$dbn->rollBack();
   	     App::db_disconnect();
   		 echo $e->getMessage();
   	  }	 

  }
?>