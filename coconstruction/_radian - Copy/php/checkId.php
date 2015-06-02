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
	   
	   echo (isset($result['bid'])&&$result['bid']==0?$result['uid']:0);
     }
?>