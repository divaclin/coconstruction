<?php require_once('app.php');?>
<?php
    if(isset($_GET['bid'])){
	try{
	   App::db_connect();
	   App::beginTranscation();
	   $sql='SELECT bname FROM building WHERE bid=:bid';
   	   $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		   ':bid'=>$_GET['bid']
       ));
	   $result=$stmt->fetch(PDO::FETCH_ASSOC);
	   $bname=$result['bname'];
	   
	   
	   $sql='UPDATE `building` SET `x`=:x,`y`=:y WHERE `bid`=:bid';
	   $stmt=App::$dbn->prepare($sql);
	   $stmt->execute(array(
			':x'=>$_GET['x'],
			':y'=>$_GET['y'],
			':bid'=>$_GET['bid']
	   ));
	   
	   /*prevent too many selects */
   	   $sql='SELECT * FROM status WHERE device=:device AND behavior=:behavior';
   	   $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		  ':device'=>'D',
		  ':behavior'=>'LOOK_UP'
       ));
   	   $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		   
	   if(!empty($result)){	   
          $sql='UPDATE `status` SET `object_type`=:object_type,`object`=:object,`oid`=:oid,`done`=:done WHERE `device`=:device AND `behavior`=:behavior AND `oid`!=:oid';
          $stmt=App::$dbn->prepare($sql);
          $stmt->execute(array(
           ':device'=>'D',
           ':behavior'=>'LOOK_UP',
   	       ':object_type'=>'B',
   	       ':object'=>'{"bid":"'.$_GET['bid'].'","bname":"'.$bname.'"}',
		   ':oid'=>$_GET['bid'],
		   ':done'=>' '
          ));  
       }
	   
       $sql='SELECT * FROM building INNER JOIN color ON building.cid=color.cid WHERE bid=:bid';
       $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		   ':bid'=>$_GET['bid']
       ));
       $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	   if(empty($result)){
		   echo json_encode(json_decode('[{"eid":0}]'));
	   }
       else{
		   echo json_encode($result);
	   }
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