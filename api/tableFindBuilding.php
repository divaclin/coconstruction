<?php require_once('app.php');?>
<?php
    if(isset($_GET['bid'])){
       App::db_connect();
	   
	   $sql='UPDATE `building` SET `x`=:x,`y`=:y WHERE `bid`=:bid';
	   $stmt=App::$dbn->prepare($sql);
	   $stmt->execute(array(
			':x'=>$_GET['x'],
			':y'=>$_GET['y'],
			':bid'=>$_GET['bid']
	   ));
	   
	   /*prevent too many selects */
   	   $sql='SELECT * FROM status WHERE device=:device AND behavior=:behavior AND oid=:oid';
   	   $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		  ':device'=>'D',
		  ':behavior'=>'LOOK_UP',
		  ':oid'=>$_GET['bid']
       ));
   	   $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		   
	   if(empty($result)){	   
          $sql='INSERT INTO status (device,behavior,object_type,object,oid) VALUES(:device,:behavior,:obj_type,:object,:oid)';
          $stmt=App::$dbn->prepare($sql);
          $stmt->execute(array(
           ':device'=>'D',
           ':behavior'=>'LOOK_UP',
   	       ':obj_type'=>'B',
   	       ':object'=>'{"bid":"'.$_GET['bid'].'"}',
		   ':oid'=>$_GET['bid']
          ));  
       }
	   
       $sql='SELECT * FROM building INNER JOIN color ON building.cid=color.cid WHERE bid=:bid';
       $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		   ':bid'=>$_GET['bid']
       ));
       $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	   if(empty($result)){
		   echo '[{"eid":"0"}]';
	   }
       else{
		   echo json_encode($result);
	   }
    }
?>