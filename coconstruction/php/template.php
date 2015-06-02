<?php require_once('app.php');?> 
<?php
     /*請將所有跟資料庫相關的php檔擺到'api'資料夾底下*/
	 /*由於連資料庫的準備工程都在app.php裡所以一定要import進來*/
	 App::db_connect();//抓資料前必須要跟資料庫做連線
	 
	 /*新增             資料表名稱  資料表內的屬性     給值        */          
	 //      $sql='INSERT INTO building (bid,type) VALUES(:bid,:type)';//sql語法
	 //      $stmt=App::$dbn->prepare($sql);//要先確認資料庫有無準備好及防止sql injection
	 //      /*執行sql語法 array會依照宣告的key給value*/
	 // $stmt->execute(array(
	 //             ':bid'=>$_POST['bid'],
	 //             ':type'=>1
	 //      ));
	
	 /*    選擇           資料表名稱       條件*/
 	 $sql='SELECT * FROM building WHERE 1';
 	 $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());
 	 $result=$stmt->fetchAll(PDO::FETCH_ASSOC);//fetch ->只選第一個結果 fetchAll->全部結果都選
 	 
	 $rows = urlencode(array());
	   while($r = $result=$stmt->fetch(PDO::FETCH_ASSOC)) {
	       foreach ($r as $key => $value) $r[$key] = urlencode($value) ;
	       $rows[] = $r;
	   }
       urldecode(json_encode($rows));	 //使用 echo 回傳字串 json_encode目的是把array of object 轉成 字串
	 
     /*    更新     資料表名稱      給值                 條件*/   
		//      $sql='UPDATE `building` SET `x`=:x,`y`=:y WHERE `bid`=:bid';//update必須使用 ` 來表達資料表與其屬性
		//      $stmt=App::$dbn->prepare($sql);
		//      $stmt->execute(array(
		// ':x'=>$_GET['x'],
		// ':y'=>$_GET['y'],
		// ':bid'=>$_GET['bid']
		//      ));

?>