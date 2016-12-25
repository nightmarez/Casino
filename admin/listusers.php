<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		die();
	}
	else
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT `id`, `login` FROM `users` ORDER BY `login`;');
		$req->execute();
		$arr = array();

		while (list($id, $login) = $req->fetch(PDO::FETCH_NUM))
		{
			// name
			$arr[] = array($id, $login);

			// bill
			$arr[] = array($id, $id);
		}

		echo json_encode($arr);
	}
?>