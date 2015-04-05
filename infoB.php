<?php require_once('php/app.php'); ?>
<?php require_once('php/core.php');?>
<!DOCTYPE html>
<html>
    <?php HTML::Head('infoB');?>
    <body onload=<?php echo '"infoBUpdate('.$_GET['cid'].',0)"';?>>
	<?php PAD::Background('infoB');?>
	</body>
</html>