<?php require_once("app.php");?>
<?php
   if(isset($_GET['block'])){ 
    App::db_connect();
    $sql='INSERT INTO building (bid,type) VALUES(:bid,:type)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
           ':bid'=>$_POST['bid'],
           ':type'=>1
    ));
	
	$sql='SELECT * FROM building WHERE bid=:bid';
	$stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
    	':bid'=>$_GET['bid']
    ));
	$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);
	
    $sql='INSERT INTO status (device,behavior,obj_type,object) VALUES(:device,:behavior,:obj_type,:object)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
       ':device'=>'D',
       ':behavior'=>'BUILD_UP_return',
	   ':obj_type'=>'B',
	   ':object'=>'{"bid":"'.$_GET['bid'].'"}'
      ));  
		   
 }
?>