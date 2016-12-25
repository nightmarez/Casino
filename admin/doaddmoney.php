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
		$money = intval($_GET['money']);

		if ($userid <= 0 || $money <= 0)
		{
			echo 'false';
			die();
		}


		$db = new PdoDb();
		$db->beginTransaction();

		// add money to user
		$req = $db->prepare('UPDATE `users` SET `money` = `money` + :money WHERE `id` = :userid;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->execute();

		// log this operation
		$req = $db->prepare('INSERT INTO `payments` (`userid`, `money`, `desc`, `to`, `date`, `type`) VALUES (:userid, :money, "Пополнение счёта администратором", :userid, NOW(), "Пополнение счёта");');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		echo 'true';
	}
?>