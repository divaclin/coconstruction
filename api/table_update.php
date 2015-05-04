<?php require_once('app.php');?> 
<?php
	 App::db_connect();//抓資料前必須要跟資料庫做連線
	 
     /*    更新     資料表名稱      給值                 條件*/   
     $sql='UPDATE `status` SET `done`=CONCAT(`done`,"E") WHERE `behavior`="LOOK_UP"';//update必須使用 ` 來表達資料表與其屬性
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());


	 /*    更新     資料表名稱      給值                 條件*/   
	 $sql='UPDATE `building` SET `done`=CONCAT(`done`,"E") WHERE `behavior`="SELECT"';//update必須使用 ` 來表達資料表與其屬性
	 $stmt=App::$dbn->prepare($sql);
	 $stmt->execute(array());

?>
