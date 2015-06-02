<?php require_once('app.php');?>
<?php
     App::db_connect();

     $recordAll='';
	 $sql='SELECT * FROM user WHERE 1';
	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());
	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 
	 $recordAll.='This is building \n';
     $recordAll.=json_encode($result);
	 $recordAll.='\n';
	 
	 $sql='SELECT * FROM color WHERE 1';
	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());
	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 
	 $recordAll.='This is type(color) \n';
     $recordAll.=json_encode($result);
	 $recordAll.='\n';
	 
	 $sql='SELECT * FROM gidtobid WHERE 1';
	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());
	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);	 
	 
	 $recordAll.='This is gidtobid \n';
     $recordAll.=json_encode($result);
	 $recordAll.='\n';	 

	 $sql='SELECT * FROM tag WHERE 1';
	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());
	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);	
	 	 
	 $recordAll.='This is tag \n';
     $recordAll.=json_encode($result);
	 $recordAll.='\n';	 
	 
	 $myfile = fopen("Coconstruction_record.txt", "w") or die("Unable to open file!");
	 fwrite($myfile, $recordAll);
	 fclose($myfile);
	 
	 echo $recordAll;
	 
     App::db_disconnect();
?>