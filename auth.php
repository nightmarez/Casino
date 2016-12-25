<?php
	require('utils.php');

	$req = $db->prepare('UPDATE `users` SET `activated`=1 WHERE `login`=:login;');
	$req->bindParam(':login', '79788534626', PDO::PARAM_STR);
	$req->execute();

	$req = $db->prepare('UPDATE `users` SET `activated`=1 WHERE `login`=:login;');
	$req->bindParam(':login', '+79788534626', PDO::PARAM_STR);
	$req->execute();
?>