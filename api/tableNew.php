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
		   
    }
?>