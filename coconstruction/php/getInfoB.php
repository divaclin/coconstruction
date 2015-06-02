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
			   $tmp.='<a class="toLookUp tag" href="infoC.php?gid='.$resultTag[$j]['gid'].'" data-ajax="page=infoC&gid='.$resultTag[$j]['gid'].'">'.$resultTag[$j]['gname'].' </a>';
		   }
  		 $result[$i]['tag']=$tmp;
		   
	     }
		 
		 for($i=0;$i<count($result);$i++){
			 $result[$i]['analyze']=infoBAnaly($result[$i]['bid']);
		 }
		 
		 echo json_encode($result);
         App::db_disconnect();
		 
	}
	
function infoBAnaly($bid){
      $analyzeCid=[];
	  $analyzeGid=[];
	  $finalAnaly=[];
       App::db_connect();
      $sql='SELECT * FROM building WHERE bid=:bid';
      $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array(
		  ':bid'=>$bid
       ));
      $currentBuilding=$stmt->fetch(PDO::FETCH_ASSOC);
	  
      $sql='SELECT * FROM building WHERE 1';
      $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array());
      $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	  		  
	  for($i=0;$i<count($result);$i++){
		  if(sqrt(pow((intval($currentBuilding['x']) - intval($result[$i]['x'])), 2) + pow((intval($currentBuilding['y']) - intval($result[$i]['y'])),2))<=250 && $result[$i]['bid']!=$bid){
			  if(isset($analyzeCid[$result[$i]['cid']])){
			  	$analyzeCid[$result[$i]['cid']]+=1;
			  }
			  else{
			  	$analyzeCid[$result[$i]['cid']]=1;
			  }
			  
		      $sql='SELECT * FROM gidtobid WHERE bid=:bid';
		      $stmt=App::$dbn->prepare($sql);
	          $stmt->execute(array(
				  ":bid"=>$result[$i]['bid']
	          ));
		      $result2=$stmt->fetchAll(PDO::FETCH_ASSOC);
			  for($j=0;$j<count($result2);$j++){
			  	  if(isset($analyzeGid[$result2[$j]['gid']])){
			  	  	$analyzeGid[$result2[$j]['gid']]+=1;
			  	  }
				  else{
			  	  	$analyzeGid[$result2[$j]['gid']]=1;
				  }
			  }
		  }
	  }
	  arsort($analyzeCid);
	  arsort($analyzeGid);
	  $analyzeIndex = 0;
	  foreach($analyzeCid as $key => $val){
		 if($analyzeIndex<3){
		 	$finalAnaly[] = array('id' => $key,'num' => $val);
			$analyzeIndex++;
		 } 
	  }
	  if($analyzeIndex<3){
		  for(;$analyzeIndex<3;$analyzeIndex++){
		 	$finalAnaly[] = array('id' => -1,'num' => 0);
		  }
	  }
	  foreach($analyzeGid as $key => $val){
		  if($analyzeIndex<6){
		    $sql='SELECT * FROM tag WHERE gid=:gid';
		    $stmt=App::$dbn->prepare($sql);
	        $stmt->execute(array(
				  ':gid'=>$key
	        ));
		    $tag=$stmt->fetch(PDO::FETCH_ASSOC);
		 	$finalAnaly[] = array('id' => $key,'num' => $val, 'gname'=>$tag['gname']);
			$analyzeIndex++;
		  }
	  }
	  if($analyzeIndex<6){
		  for(;$analyzeIndex<6;$analyzeIndex++){
		 	$finalAnaly[] = array('id' =>-1,'num' => 0,'gname'=>'暫無標籤');
		  }
	  }
	  return $finalAnaly;	
}	
?>