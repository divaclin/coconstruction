<?php require_once('app.php');?>
<?php 
if(isNotBlock()){
    App::db_connect();
    $sql='INSERT INTO status (device,behavior) VALUES(:device,:behavior)';
    $stmt=App::$dbn->prepare($sql);
    $stmt->execute(array(
       ':device'=>'A',
       ':behavior'=>'BUILD_UP'
    ));
}
?>