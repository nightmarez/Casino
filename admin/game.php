<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		$id = 0;

		if (isset($_GET['id']))
		{
			$id = intval($_GET['id']);
		}
?>
	<?php require_once('header.php'); ?>

	<div class="panel panel-default">
		<div class="panel-heading">Тираж</div>

		<table class="table">
			<?php
				$db = new PdoDb();
				$req = $db->prepare('SELECT * FROM `games` WHERE `id`=:langid;');
				$req->bindParam(':langid', $langid, PDO::PARAM_INT);
				$req->execute();

				while (list($id, $token, $user, $balls) = $req->fetch(PDO::FETCH_NUM))
				{
					?>
						<tr>
							<td>id</td>
							<td>
								<?php echo $id; ?>
							</td>
						</tr>
						<tr>
							<td>Пользователь</td>
							<td>
								<?php
									$db1 = new PdoDb();
									$req1 = $db->prepare('SELECT * FROM `users` WHERE `id`=:user;');
									$req1->bindParam(':user', $user, PDO::PARAM_INT);
									$req1->execute();
									$any = false;

									while (list($userid, $login) = $req1->fetch(PDO::FETCH_NUM))
									{
										?>
											<a href="/admin/user.php?id=<?php echo $userid; ?>"><?php echo $login; ?></a>
										<?php
										$any = true;
										break;
									}

									if (!$any)
									{
										echo $user;
									}
								?>
							</td>
						</tr>
						<tr>
							<td>Шары</td>
							<td>
								<?php echo $balls; ?>
							</td>
						</tr>
					<?php
					break;
				}
			?>
		</table>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">Ставки тиража</div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Ставка</th>
					<th>Шары</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT COUNT(*) FROM `bets`;');
					$req->execute();
					$count = $req->fetchColumn();

					$db = new PdoDb();
					$req = $db->prepare('SELECT t1.id, t1.gameid, t1.bet, t1.balls, t2.user FROM `bets` AS t1, `games` AS t2 WHERE t1.gameid = t2.id AND t1.gameid=:id ORDER BY `id` DESC;');
					$req->bindParam(':id', $id, PDO::PARAM_INT);
					$req->execute();

					while (list($id, $gameId, $bet, $balls, $user) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td><?php echo $id; ?></td>
								<td><?php echo $bet; ?></td>
								<td><?php echo $balls; ?></td>
							</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>

	<?php require_once('footer.php'); ?>
<?php
	}
?>