<?php require_once('app.php');?>
<?php
     App::db_connect();

 	 $sql='DELETE FROM building WHERE `bid`>17';
 	 $stm=App::$dbn->prepare($sql);
 	 $stm->execute(array());

 	 $sql='DELETE FROM gidtobid WHERE `bid`>17';
 	 $stm=App::$dbn->prepare($sql);
 	 $stm->execute(array());

 	 $sql='DELETE FROM user WHERE 1';
 	 $stm=App::$dbn->prepare($sql);
 	 $stm->execute(array());

 	 $sql='DELETE FROM tag WHERE gid > 92';
 	 $stm=App::$dbn->prepare($sql);
 	 $stm->execute(array());

     $sql='UPDATE `color` SET `count`=0 WHERE `cid`!=0';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

     $sql='UPDATE `building` SET `reside`=0 WHERE 1';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

     $sql='UPDATE `tag` SET `count`=0';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

     $sql='UPDATE `tag` SET `count`=16 WHERE gid=56';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

     $sql='UPDATE `tag` SET `count`=3 WHERE gid=80 OR gid=79';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

     $sql='UPDATE `tag` SET `count`=2 WHERE gid=75 OR gid=83 OR gid=81 OR gid=88 OR gid=89 OR gid=71 OR gid=86';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

     $sql='UPDATE `tag` SET `count`=1 WHERE gid=64 OR gid=65 OR gid=66 OR gid=67 OR gid=68 OR gid=69 OR gid=70 OR gid=72 OR gid=73 OR gid=74 OR gid=8 OR gid=76 OR gid=77 OR gid=78 OR gid=82 OR gid=84 OR gid=85 OR gid=87 OR gid=90 OR gid=91 OR gid=92';
     $stmt=App::$dbn->prepare($sql);
     $stmt->execute(array());

	 echo 'reset success';

     App::db_disconnect();
?>
