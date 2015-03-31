<?php require_once("app.php");?>
<?php
     if(isset($_GET['block'])){
		App::db_connect();
 	    $sql='UPDATE `status` SET `done`="D" WHERE `statusid`=:id';
 	    $stmt=App::$dbn->prepare($sql);
 	    $stmt->execute(array(
 			':id'=>$_GET['id']
 	    ));
	    
    	$sql='SELECT * FROM status WHERE statusid=:id';
    	$stmt=App::$dbn->prepare($sql);
        $stmt->execute(array(
        	':id'=>$_GET['id']
        ));
    	$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    	echo json_encode($result);
     }
?>