<?php require_once('app.php');?>
<?php
    if(isNotBlock()){
       App::db_connect();
	   $sql='SELECT * FROM user WHERE uid=:uid';
	   $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
          ':uid'=>$_POST['uid']
       ));
	   $result=$stmt->fetch(PDO::FETCH_ASSOC);
	   
	   $sql2='SELECT * FROM building WHERE bid=:bid';
	   $stmt2=App::$dbn->prepare($sql2);
       $stmt2->execute(array(
          ':bid'=>$_POST['bid']
       ));
	   $result2=$stmt2->fetch(PDO::FETCH_ASSOC);
	   
	   
	   echo (isset($result['house'])&&$result['house']==0&&$result2['total']-$result2['reside']>=0?$result['uid']:0);
     }
?>