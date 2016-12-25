<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		die();
	}
	else
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT `id`, `title` FROM `programs` ORDER BY `title`;');
		$req->execute();
		$arr = array();

		while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
		{
			$arr[] = array($id, $title);
		}

		echo json_encode($arr);
	}
?>