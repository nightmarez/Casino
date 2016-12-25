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
		<div class="panel-heading">Счета</div>

		<table class="table">
			<thead>
				<tr>
					<th>Номер счёта</th>
					<th>Логин</th>
					<th>Игры</th>
					<th>Валюта</th>
					<th>Овердрафт</th>
					<th>Баланс</th>
					<th>Последний платёж</th>
					<th>Процент</th>
					<th>Уровень</th>
					<th>Статус</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT `id`, `login`, `level`, `money`, `ban` FROM `users`;');
					$req->execute();

					while (list($id, $login, $level, $money, $ban) = $req->fetch(PDO::FETCH_NUM))
					{
						if ($level == 1) {
							continue;
						}

						?>
						<tr>
							<td><a href="/dealer/user.php?id=<?php echo $id; ?>"><?php echo $id; ?></a></td>
							<td><a href="/dealer/user.php?id=<?php echo $id; ?>"><?php echo $login; ?></a></td>
							<td>
								<?php
									$db1 = new PdoDb();
									$req1 = $db1->prepare('SELECT COUNT(*) AS `total` FROM `pgames` WHERE `user`=:id;');
									$req1->bindParam(':id', $id, PDO::PARAM_INT);
									$req1->execute();
									$data = $req1->fetch(PDO::FETCH_ASSOC);
									$gamesCount = $data['total'];

									if ($gamesCount == 0) {
										echo 0;
									} else {
										?>
											<a href="/dealer/usergames.php?id=<?php echo $id; ?>"><?php echo $gamesCount; ?></a>
										<?php
									}
								?>
							</td>
							<td>RUR</td>
							<td>0</td>
							<td><?php echo $money; ?></td>
							<td>нет данных</td>
							<td>0</td>
							<td><?php echo $level == 1 ? 'Администратор' : 'Пользователь'; ?></td>
							<td><?php echo $ban == 0 ? 'Работает' : 'Заблокирован'; ?></td>
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