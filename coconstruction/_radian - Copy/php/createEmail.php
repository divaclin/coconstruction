<?php require_once('app.php');?>
<?php
  if(isNotBlock()){
     App::db_connect();
     $sql='INSERT INTO user (email,name) VALUES(:email,:name)';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array(
        ':email'=>$_POST['address'],
        ':name'=>$_POST['usr']
     ));
	 
	 $sql='SELECT uid FROM user WHERE email=:email AND name=:name';
	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array(
        ':email'=>$_POST['address'],
        ':name'=>$_POST['usr']
     ));
	 $result=$stmt->fetch(PDO::FETCH_ASSOC);
	 echo $result['uid'];
  }
?>