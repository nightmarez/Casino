<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
?>
	<?php require_once('header.php'); ?>
		<h1>Пустая страница</h1>

		<?php require_once('menu.php'); ?>

		<!-- CONTENT -->
			
	<?php require_once('footer.php'); ?>
<?php
	}
?>