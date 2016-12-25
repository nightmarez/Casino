<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
?>
	<?php require_once('header.php'); ?>
	
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo getL10n('Admin-Index-Statistics'); ?></div>

		<table class="table">
			<tbody>
				<tr>
					<td><?php echo getL10n('Admin-Index-PlayersRegistered'); ?>:</td>
					<td>
						<a href="/admin/users.php">
							<?php
								$db = new PdoDb();
								$req = $db->prepare('SELECT COUNT(*) FROM `users` WHERE `level`=2;');
								$req->execute();
								echo $req->fetchColumn();
							?>
						</a>
					</td>
				</tr>
				<tr>
					<td><?php echo getL10n('Admin-Index-PlayersOnline'); ?>:</td>
					<td>
						<?php
							echo 0;
						?>
					</td>
				</tr>
				<tr>
					<td><?php echo getL10n('Admin-Index-AttendanceStatistics'); ?>:</td>
					<td>

					</td>
				</tr>
				<tr>
					<td><?php echo getL10n('Admin-Index-RecentPayments'); ?>:</td>
					<td>

					</td>
				</tr>
				<tr>
					<td><?php echo getL10n('Admin-Index-DrawsHistory'); ?>:</td>
					<td>
						<table class="table">
							<thead>
								<tr>
									<th>id</th>
									<th>Пользователь</th>
									<th>Шары</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$db = new PdoDb();
									$req = $db->prepare('SELECT * FROM `games` WHERE LENGTH(`balls`) > 0 ORDER BY `id` DESC LIMIT 0, 10;');
									$req->execute();

									while (list($id, $token, $user, $balls) = $req->fetch(PDO::FETCH_NUM))
									{
										?>
											<tr>
												<td><a href="/admin/game.php?id=<?php echo $id; ?>"><?php echo $id; ?></td>
												<td><a href="/admin/user.php?id=<?php echo $user; ?>"><?php echo htmlspecialchars(getUserLoginById($user)); ?></td>
												<td><?php echo $balls; ?></td>
											</tr>
										<?php
									}
								?>

								<tr>
									<td colspan="3"><a href="/admin/games.php"><?php echo getL10n('More'); ?>...</a></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php require_once('footer.php'); ?>
<?php
	}
?>