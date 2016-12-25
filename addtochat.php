<?php
	require_once('chat.php');

	$id = $_GET['id'];
	echo addUserToChat($id);
?>