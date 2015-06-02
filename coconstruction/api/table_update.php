<?php require_once('app.php');?> 
<?php
try{
     App::db_connect();
     App::beginTranscation();	 
     /*    更新     資料表名稱      給值                 條件*/   
     $sql='UPDATE `status` SET `done`=CONCAT(`done`,"E") WHERE `behavior`="LOOK_UP" AND statusid=:statusid';//update必須使用 ` 來表達資料表與其屬性
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array(
			 ':statusid'=>$_GET['statusid']
	     ));
	 App::db_disconnect();

	 }
     catch(PDOException $e){
     		 App::$dbn->rollBack();
     	     App::db_disconnect();
     		 echo $e->getMessage();
    } 
?>