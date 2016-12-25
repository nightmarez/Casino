<?php
	require_once('chat.php');

	$guid = $_GET['token'];
	echo getUserNameByGuid($guid);
?>