<?php
	require_once('../utils.php');

	if (!dealerZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
?>
	<?php require_once('header.php'); ?>

	<div class="panel panel-default">
		<div class="panel-heading">Пользователь</div>

		<table class="table">
			<tbody>

			<?php
				$userId = intval($_GET['id']);

				$db = new PdoDb();
				$req = $db->prepare('SELECT * FROM `users` WHERE `id`=:userId;');
				$req->bindParam(':userId', $userId, PDO::PARAM_INT);
				$req->execute();
								
				while (list($id, $login, $pass, $level, $activated, $sms, $money, $ban, $fullname, $rating, $lobbyaccess) = $req->fetch(PDO::FETCH_NUM))
				{
					?>
						<tr>
							<td>id</td>
							<td id="user-id"><?php echo $id; ?></td>
						</tr>
						<tr>
							<td>Логин</td>
							<td><?php echo $login; ?></td>
						</tr>
						<tr>
							<td>Блокировка</td>
							<td>
								<?php echo $ban == 0 ? 'Работает' : 'Заблокирован'; ?>
							</td>
						</tr>
						<tr>
							<td>Баланс</td>
							<td>
								<?php echo $money; ?>
							</td>
						</tr>
						<tr>
							<td>Игры</td>
							<td>
								<?php
									$db1 = new PdoDb();
									$req1 = $db->prepare('SELECT count(*) AS total FROM `pgames` WHERE `user`=:id;');
									$req1->bindParam(':id', $id, PDO::PARAM_INT);
									$req1->execute();
									$gamesCount = $req->fetchColumn();

									if ($gamesCount == 0) {
										echo 0;
									} else {
										?>
											<a href="/dealer/usergames.php?id=<?php echo $id; ?>"><?php echo $gamesCount; ?></a>
										<?php
									}
								?>
							</td>
						</tr>
					<?php
					break;
				}
			?>

			</tbody>
		</table>
	</div>

	<?php require_once('footer.php'); ?>
<?php
	}
?>