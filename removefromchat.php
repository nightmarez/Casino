<?php
	require_once('chat.php');

	$guid = $_GET['token'];
	removeUserFromChat($guid);
?>