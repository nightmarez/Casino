<?php
	require_once('../utils.php');

	if (isUserUnregistered()) {
		echo 'false';
		die();
	} 
	else if (!isUserHasAccess(13 /* Languages */)) {
		echo 'false';
		die();
	}
	else
	{
		$langid = intval($_GET['id']);

		if ($langid <= 0)
		{
			echo 'false';
			die();
		}


		$db = new PdoDb();
		$db->beginTransaction();

		// delete language
		$req = $db->prepare('DELETE FROM `langs` WHERE `id`=:id;');
		$req->bindParam(':id', $langid, PDO::PARAM_INT);
		$req->execute();

		// delete localizations
		$req = $db->prepare('DELETE FROM `langs` WHERE `lang`=:id;');
		$req->bindParam(':id', $langid, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		echo 'true';
	}
?>