<?php
	require_once('../../utils.php');

	function getRandomFromDb($range, $stream)
	{
		// open db
		$db = new PdoDb();
		$db->beginTransaction();

		// get old value
		$req = $db->prepare('SELECT `value` FROM `rnd` WHERE `id`=:id;');
		$req->bindParam(':id', $stream, PDO::PARAM_INT);
		$req->execute();
		$value = intval($req->fetch(PDO::FETCH_ASSOC)['value']);

		// calculate new value
		$value = ($value * 69069 + 1) % 4294967296;

		// write new value
		$req = $db->prepare('UPDATE `rnd` SET `value`=:value WHERE `id`=:id;');
		$req->bindParam(':id', $stream, PDO::PARAM_INT);
		$req->bindParam(':value', $value, PDO::PARAM_INT);
		$req->execute();

		// result nad close db
		$db->commit();
		return $value % $range;
	}

	function randomNumberGet($range)
	{
		// get stream number
		$stream = getRandomFromDb(3, 3);

		// get random number
		return getRandomFromDb($range, $stream);
	}

	function randomNumberSeed($seed = NULL)
	{
		if (!isset($seed)) {
			$seed = mt_rand();
		}

		// generate seeds
		$random = array();
		$random[0] = $seed % 4294967296;
		$random[1] = ($seed * $random[0]) % 4294967296;
		$random[2] = ($seed * $random[1]) % 4294967296;
		$random[3] = ($seed * $random[2]) % 4294967296;

		// open db
		$db = new PdoDb();
		$db->beginTransaction();

		// write values
		for ($i = 0; $i < count($random); ++$i) {
			$req = $db->prepare('UPDATE `rnd` SET `value`=:value WHERE `id`=:id;');
			$req->bindParam(':id', $i, PDO::PARAM_INT);
			$req->bindParam(':value', $random[$i], PDO::PARAM_INT);
			$req->execute();
		}

		// close db
		$db->commit();
	}
?>