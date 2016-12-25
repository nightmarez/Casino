<?php 
	require_once('utils.php');

	function genGuid() {
		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	function addUserToChat($userid) {
		$db = new PdoDb();
		$db->beginTransaction();

		$req = $db->prepare('DELETE FROM `chat` WHERE `userid`=:userid;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		$guid = genGuid();
		$req = $db->prepare('INSERT INTO `chat` (`userid`, `token`) VALUES (:userid, :guid);');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':guid', $guid, PDO::PARAM_STR);
		$req->execute();

		$db->commit();
		return $guid;
	}

	function removeUserFromChat($guid) {
		$db = new PdoDb();
		$guid = htmlspecialchars($guid);
		$req = $db->prepare('DELETE FROM `chat` WHERE `token`=:guid;');
		$req->bindParam(':guid', $guid, PDO::PARAM_STR);
		$req->execute();
	}

	function getUserNameByGuid($guid) {
		if (!preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $guid)) {
			return '???';
		}

		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `chat` WHERE `token`=:guid;');
		$req->bindParam(':guid', $guid, PDO::PARAM_STR);
		$req->execute();
		$userLogin = '';

		while (list($id, $userid, $token) = $req->fetch(PDO::FETCH_NUM)) {
			$db1 = new PdoDb();
			$req1 = $db->prepare('SELECT * FROM `users` WHERE `id`=:userid;');
			$req1->bindParam(':userid', $userid, PDO::PARAM_INT);
			$req1->execute();

			while (list($id, $login) = $req1->fetch(PDO::FETCH_NUM)) {
				$userLogin = $login;
				break;
			}

			break;
		}

		return $userLogin;
	}
?>