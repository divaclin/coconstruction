<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
	     $sql='INSERT INTO building (bname,content,tag,cid) VALUES(:bname,:content,:tag,:cid)';
	     $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
	        ':bname'=>$_POST['bname'],
	        ':content'=>$_POST['content'],
			':tag'=>$_POST['tag'],
			':cid'=>$_POST['cid']
	     ));
	 
		 $sql='SELECT bid FROM building WHERE bname=:bname AND content=:content AND tag=:tag AND cid=:cid';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
 	        ':bname'=>$_POST['bname'],
 	        ':content'=>$_POST['content'],
 			':tag'=>$_POST['tag'],
 			':cid'=>$_POST['cid']
	     ));
		 $result=$stmt->fetch(PDO::FETCH_ASSOC);
		 echo $result['bid'];
     }
?>