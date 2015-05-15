<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
		 $sql='SELECT * FROM tag ORDER BY count DESC';
		 $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array());
		 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		 $json='[';
		 for($i=0;$i<count($result);$i++){
			 $json.='{"id":"'.$result[$i]['gid'].'","name":"'.substr($result[$i]['gname'],1).'"}'.($i+1==count($result)?'':',');
		 }
		 $json.=']';
		 
		 
		 $jsonFile = fopen('tag.json','w+');
		 fwrite($jsonFile,$json);
		 fclose($jsonFile);
		 echo json_encode($json);
	}
?>