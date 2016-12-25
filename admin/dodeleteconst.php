<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		$id = intval(htmlspecialchars($_GET['id']));
		$db = new PdoDb();
		$db->beginTransaction();

		// delete constant
		$req = $db->prepare('DELETE FROM `consts` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		// delete localizations
		$req = $db->prepare('DELETE FROM `l10n` WHERE `const`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		echo 'true';
	}
?>