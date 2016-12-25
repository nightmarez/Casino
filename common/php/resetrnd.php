<?php
	require_once('../../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		require_once('rnd.php');
		randomNumberSeed();
		echo 'true';
		die();
	}

	echo 'false';
?>