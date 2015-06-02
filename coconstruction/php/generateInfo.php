<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $text='';
		 $sql='SELECT * FROM user INNER JOIN building ON user.bid=building.bid WHERE uid=:uid';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
			 ':uid'=>$_POST['uid']
	     ));
		 $result=$stmt->fetch(PDO::FETCH_ASSOC);
		 
		 $sql2='SELECT * FROM gidtobid INNER JOIN tag ON gidtobid.gid=tag.gid WHERE bid=:bid ORDER BY count DESC';
		 $stmt2=App::$dbn->prepare($sql2);
	     $stmt2->execute(array(
			 ':bid'=>$result['bid']
	     ));
		 $result2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
		 
		 for($i=0;$i<count($result2);$i++){
			 $text.=$result2[$i]['gname'].' ';
		 }
		 
		 $result['tag']=$text;
		 echo json_encode($result);
         App::db_disconnect();
		 
	 }
?>