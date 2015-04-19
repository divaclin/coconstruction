<?php require_once('app.php');?>
<?php require_once('core.php');?>
<?php
      switch($_GET['page']){
		  case 'infoA':
		       echo PAD::Background('infoA');
		       break;
		  case 'infoB':
		       echo PAD::Background('infoB');
			   break;
		  case 'infoC':
		       echo PAD::Background('infoC');
		       break;
		  case 'build':
		       echo PAD::Background('build');
		       break;	   
		  default:
		     break;
      }
?>