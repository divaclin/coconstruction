<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
 		 $sql='UPDATE `building` SET `bname`= :bname,`content`=:content,`tag`=:tag,`cid`=:cid WHERE `bid`=:bid';
 		 $stmt=App::$dbn->prepare($sql);
 		 $stmt->execute(array(
			 ':bname'=>$_POST['bname'],
			 ':content'=>$_POST['content'],
			 ':tag'=>$_POST['tag'],
			 ':cid'=>$_POST['cid'],
			 ':bid'=>$_POST['bid']	
 		));
	 }
?>