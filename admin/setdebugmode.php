<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		$value = intval($_GET['value']);

		$db = new PdoDb();
		$req = $db->prepare('INSERT INTO `settings` (`param`, `value`) VALUES ("debugmode", :value) ON DUPLICATE KEY UPDATE `value`=:value;');
		$req->bindParam(':value', $value, PDO::PARAM_INT);
		$req->execute();

		echo 'true';
	}
?>