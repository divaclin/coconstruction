<?php require_once("app.php");?>
<?php
   if(isset($_GET['block'])){ 
    App::db_connect();
    $sql='INSERT INTO building (bid,type,eid) VALUES(:bid,:type,:eid)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
           ':bid'=>$_GET['bid'],
           ':type'=>1,
		   ':eid'=>1
    ));
	
	$sql='SELECT bname,content,tag,eid FROM building WHERE bid=:bid';
	$stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
    	':bid'=>$_GET['bid']
    ));
	$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);
	
    $sql='INSERT INTO status (device,behavior,object_type,object) VALUES(:device,:behavior,:obj_type,:object)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
       ':device'=>'D',
       ':behavior'=>'BUILD_UP_return',
	   ':obj_type'=>'B',
	   ':object'=>'{"bid":"'.$_GET['bid'].'"}'
      ));  
		   
 }
?>