<?php require_once('app.php');?>
<?php
     App::db_connect();
	 $sql='SELECT * FROM user WHERE tag=:tag';
	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array(
        ':tag'=>$_POST['tag']
     ));
	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 echo ;
     App::db_disconnect();
     
?>