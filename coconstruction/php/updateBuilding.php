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
	   
	     $sql='UPDATE `color` SET `count` = `count`+1 WHERE `cid`=:cid';
	     $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
              ':cid'=>$_POST['cid']
	     ));
		
		$tagArr=json_decode($_POST['tag']);

		foreach($tagArr as $value){
	        $sql='SELECT * FROM tag WHERE gname=:gname';
	        $stmt=App::$dbn->prepare($sql);
	        $stmt->execute(array(
	 		   ':gname'=>$value
	        ));
	        $result=$stmt->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)){
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
		    else{
			    $sql='INSERT INTO tag(gname) VALUES(:gname)';
		        $stmt=App::$dbn->prepare($sql);
			    $stmt->execute(array(
			           ':gname'=>$value,
			    ));

		        $sql='SELECT * FROM tag WHERE gname=:gname';
		        $stmt=App::$dbn->prepare($sql);
		        $stmt->execute(array(
		 		   ':gname'=>$value
		        ));
		        $resultGid=$stmt->fetch(PDO::FETCH_ASSOC);

			    $sql='INSERT INTO gidtobid (bid,gid) VALUES(:bid,:gid)';
			    $stmt=App::$dbn->prepare($sql);
			    $stmt->execute(array(
			    	':bid'=>$_POST['bid'],
					':gid'=>$resultGid['gid']
			    ));
			}
		}
		
		$sql='DELETE FROM status WHERE `device`=:device OR `behavior`=:behavior';
		$stm=App::$dbn->prepare($sql);
		$stm->execute(array(
	      	':device'=>$_POST['device'],
			':behavior'=>'BUILD_UP_return'
	    ));
	
	    $sql='SELECT * FROM user WHERE uid=:uuid';
	    $stmt=App::$dbn->prepare($sql);
	    $stmt->execute(array(
	    	':uuid'=>$_POST['uuid']
	    ));
	    $result=$stmt->fetch(PDO::FETCH_ASSOC);
	
	    $sql='INSERT INTO status (device,behavior,object_type,object) VALUES(:device,:behavior,:object_type,:object)';
	    $stmt=App::$dbn->prepare($sql);
	    $stmt->execute(array(
	       ':device'=>$_POST['device'],
	       ':behavior'=>'BUILD_UP_after',
		   ':object_type'=>'B',
		   ':object'=>'{"bid":"'.$_POST['bid'].'","bname":"'.$_POST['bname'].'","uname":"'.$result['name'].'"}'
	    ));
        App::db_disconnect();
	 }
?>