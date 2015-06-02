<?php require_once('app.php');?> 
<?php
	 App::db_connect();//抓資料前必須要跟資料庫做連線
	 
	 /*    選擇           資料表名稱       條件*/
 	 $sql='SELECT * FROM status WHERE behavior="LOOK_UP" AND (device="A" OR device="B" OR device="C")';
 	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());
 	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);//fetch->只選第一個結果 fetchAll->全部結果都選
 	 echo json_encode($result);//使用echo回傳字串 json_encode目的是把array of object 轉成字串
     App::db_disconnect();
     
?>