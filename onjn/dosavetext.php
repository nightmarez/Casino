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
		$lang = intval(htmlspecialchars($_GET['lang']));
		$const = intval(htmlspecialchars($_GET['const']));
		$text = htmlspecialchars($_GET['text']);

		$db = new PdoDb();
		$req = $db->prepare('INSERT INTO `l10n` (`text`, `lang`, `const`) VALUES (:text, :lang, :const) ON DUPLICATE KEY UPDATE `text`=:text;');
		$req->bindParam(':lang', $lang, PDO::PARAM_INT);
		$req->bindParam(':const', $const, PDO::PARAM_STR);
		$req->bindParam(':text', $text, PDO::PARAM_STR);
		$req->execute();

		echo 'true';
	}
?>