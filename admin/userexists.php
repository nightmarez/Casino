<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		$userid = intval($_GET['id']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `users` WHERE `id`=:userid;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();
		$count = $req->fetchColumn();
		echo $count >= 1 ? 'true' : 'false';
	}
?>