<?php require_once("app.php");?>
<?php
   if(isset($_GET['block'])){  
	$imgArr=array(
		'18'=>4,
		'19'=>4,
		'20'=>6,
		'21'=>6,
		'22'=>7,
		'23'=>8,
		'24'=>8,
		'25'=>9,
		'26'=>10,
		'27'=>10,
		'28'=>11,
		'29'=>11,
		'30'=>11,
		'31'=>11,
		'32'=>12,
		'33'=>12,
		'34'=>12,
		'35'=>13,
		'36'=>14,
		'37'=>15,
		'38'=>16,
		'39'=>16,
		'40'=>17,
		'41'=>17,
		'42'=>18,
		'43'=>18,
		'44'=>18,
		'45'=>19,
		'46'=>20,
		'47'=>21,
		'48'=>22,
		'49'=>22,
		'50'=>22,
		'51'=>23,
		'52'=>24,
		'53'=>25,
		'54'=>25,
		'55'=>26,
		'56'=>26,
		'57'=>27,
		'58'=>27,
		'59'=>28,
		'60'=>29,
		'61'=>30,
		'62'=>30,
		'63'=>31,
		'64'=>32,
		'65'=>33,
		'66'=>34,
		'67'=>34,
		'68'=>35,
		'69'=>36,
		'70'=>37,
		'71'=>38
	);
	try{   
    App::db_connect();
    App::beginTranscation();
    $sql='INSERT INTO building (bid,type,eid,iid) VALUES(:bid,:type,:eid,:iid)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
           ':bid'=>$_GET['bid'],
           ':type'=>1,
		   ':eid'=>3,
		   ':iid'=>$imgArr[$_GET['bid']]
    ));
	
	$sql='SELECT bname,content,eid FROM building WHERE bid=:bid';
	$stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
    	':bid'=>$_GET['bid']
    ));
	$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);
	
	$sql='DELETE FROM status WHERE `device`=:device AND behavior!=:behavior';
	$stm=App::$dbn->prepare($sql);
	$stm->execute(array(
      	':device'=>'D',
		':behavior'=>'LOOK_UP'
  	));
	
    $sql='INSERT INTO status (device,behavior,object_type,object) VALUES(:device,:behavior,:obj_type,:object)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
       ':device'=>'D',
       ':behavior'=>'BUILD_UP_return',
	   ':obj_type'=>'B',
	   ':object'=>'{"bid":"'.$_GET['bid'].'"}'
      ));
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