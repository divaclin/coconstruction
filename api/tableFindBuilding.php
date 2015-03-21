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
	   
       $sql='SELECT * FROM building INNER JOIN color ON building.cid=color.cid WHERE bid=:bid';
       $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		   ':bid'=>$_GET['bid']
       ));
       $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
       echo json_encode($result);
    }
?>