<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $sql='SELECT * FROM building WHERE cid=:cid';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
			 ':cid'=>$_POST['cid']
	     ));
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		 for($i=0;$i<count($result);$i++){
		   		   	
		   $sql='SELECT * FROM gidtobid INNER JOIN tag ON gidtobid.gid=tag.gid WHERE bid=:bid';
		   $stmt=App::$dbn->prepare($sql);
	       $stmt->execute(array(
			 ':bid'=>$result[$i]['bid']
	       ));
  		   $resultTag=$stmt->fetchAll(PDO::FETCH_ASSOC);
		   
		   $tmp='';
		   for($j=0;$j<count($resultTag);$j++){
			   $tmp.='<a href="infoC.php?gid='.$resultTag[$j]['gid'].'">'.$resultTag[$j]['gname'].' </a>';
		   }
  		 $result[$i]['tag']=$tmp;
		   
	     }
		 
		 echo json_encode($result);
	}
?>