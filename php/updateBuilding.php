<?php require_once('app.php');?>
<?php
     if(isNotBlock()){
	     App::db_connect();
 		 $sql='UPDATE `building` SET `bname`= :bname,`content`=:content,`cid`=:cid WHERE `bid`=:bid';
 		 $stmt=App::$dbn->prepare($sql);
 		 $stmt->execute(array(
			 ':bname'=>$_POST['bname'],
			 ':content'=>$_POST['content'],
			 ':cid'=>$_POST['cid'],
			 ':bid'=>$_POST['bid']	
 		));
        
		$tagStr=explode(" ",$_POST['tag']);
		
		for($i=0;$i<count($tagStr);$i++){
	        $sql='SELECT * FROM tag WHERE gname=:gname';
	        $stmt=App::$dbn->prepare($sql);
	        $stmt->execute(array(
	 		   ':gname'=>$tagStr[$i];
	        ));
	        $result=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if(empty($result)){
			    $sql='INSERT INTO tag (gname,count) VALUES(:gname,:count)';
			    $stmt->execute(array(
			           ':gname'=>$tagStr[$i],
				       ':count'=>1;
			    ));
				
		        $sql='SELECT * FROM tag WHERE gname=:gname';
		        $stmt=App::$dbn->prepare($sql);
		        $stmt->execute(array(
		 		   ':gname'=>$tagStr[$i];
		        ));
		        $result=$stmt->fetch(PDO::FETCH_ASSOC);	
				
			    $sql='INSERT INTO gidtobid (bid,gid) VALUES(:bid,:gid)';
			    $stmt=App::$dbn->prepare($sql);
			    $stmt->execute(array(
			    	':bid'=>$_POST['bid'],
					':gid'=>$result['gid']
			    ));
			}
			else{
	    		 $sql='UPDATE `tag` SET `count` = `count`+1 WHERE `gid`=:gid';
	    		 $stmt=App::$dbn->prepare($sql);
	    		 $stmt->execute(array(
	   		         ':gid'=>$result['gid']	
	    		 ));
				
				 $sql='INSERT INTO gidtobid (bid,gid) VALUES(:bid,:gid)';
			     $stmt=App::$dbn->prepare($sql);
			     $stmt->execute(array(
			    	':bid'=>$_POST['bid'],
					':gid'=>$result['gid']
			     ));
			}
		}		
		
	 }
?>