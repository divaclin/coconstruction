<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $sql='SELECT * FROM  building WHERE 1';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		 for($i=0;$i<count($result);$i++){
			 $sql2='SELECT * FROM gidtobid INNER JOIN tag ON gidtobid.gid=tag.gid WHERE bid=:bid ORDER BY count DESC';
			 $stmt2=App::$dbn->prepare($sql2);
		     $stmt2->execute(array(
				 ':bid'=>$result[$i]['bid']
		     ));
			 $result2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
			 $result[$i]['tag']='';
			 for($j=0;$j<count($result2);$j++){
				 $result[$i]['tag'].='<a>'.$result2[$j]['gname'].'</a>';
			 }
		 }
		echo json_encode($result);
	 }
?>