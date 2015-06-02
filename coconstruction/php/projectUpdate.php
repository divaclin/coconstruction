<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 
		 $sql='SELECT * FROM color  WHERE cid!=0 ORDER BY count DESC';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		 $return['cidTotal']=0;
		 for($i=0;$i<count($result);$i++){
			 $return['cidTotal']+=$result[$i]['count'];
		 }
		 
		 $return['rankCid']=$result;
		 
		 $sql='SELECT * FROM tag ORDER BY count DESC';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		 $return['gidTotal']=0;
		 for($i=0;$i<count($result);$i++){
			 $return['gidTotal']+=$result[$i]['count'];
		 }
		 
		 $return['rankGid']=$result;
		 
		 $sql='SELECT * FROM building WHERE reside!=0';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

         $return['total']=0;
		 
		 for($i=0;$i<count($result);$i++){
			 $return['total']+=$result[$i]['reside'];
		 }
         
		 echo json_encode($return);
         App::db_disconnect();
		 
	 }
?>