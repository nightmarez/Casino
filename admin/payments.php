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

	<?php
		$user = false;

		if (isset($_GET['id']))
		{
			$user = intval($_GET['id']);

			if ($user <= 0)
			{
				$user = false;
			}
		}
	?>

	<div class="panel panel-default">
		<div class="panel-heading">Платежи
			<?php
				if ($user != false)
				{
					$db = new PdoDb();
					$req = $db->prepare('SELECT * FROM `users` WHERE `id`=:user;');
					$req->bindParam(':user', $user, PDO::PARAM_INT);
					$req->execute();

					while (list($id, $login) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							&nbsp;(пользователь <a href="/admin/user.php?id=<?php echo $id; ?>"><?php echo $login; ?></a>)
						<?php
						break;
					}
				}
			?>
		</div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Дата</th>
					<?php if ($user == false) { ?>
						<th>Пользователь</th>
					<?php } ?>
					<th>Деньги</th>
					<th>Счёт источник</th>
					<th>Счёт назначения</th>
					<th>Описание</th>
					<th>Тип операции</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();

					if ($user == false)
					{
						$req = $db->prepare('SELECT t1.id, t1.money, t1.desc, t2.login, t2.id, t1.from, t1.to, t1.date, t1.type FROM `payments` AS t1, `users` AS t2 WHERE t1.userid = t2.id ORDER BY t1.id DESC;');
					}
					else
					{
						$req = $db->prepare('SELECT t1.id, t1.money, t1.desc, t2.login, t2.id, t1.from, t1.to, t1.date, t1.type FROM `payments` AS t1, `users` AS t2 WHERE t1.userid = t2.id AND t1.userid = :user ORDER BY t1.id DESC;');
						$req->bindParam(':user', $user, PDO::PARAM_INT);
					}
					
					$req->execute();
						
					while (list($id, $money, $desc, $login, $userid, $from, $to, $date, $type) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td><?php echo $id; ?></td>
								<td><?php echo $date; ?></td>
								<?php if ($user == false) { ?>
									<td><a href="/admin/user.php?id=<?php echo $userid; ?>"><?php echo $login; ?></a></td>
								<?php } ?>
								<td><?php echo $money; ?></td>
								<td><?php echo $from; ?></td>
								<td><?php echo $to; ?></td>
								<td><?php echo $desc; ?></td>
								<td><?php echo $type; ?></td>
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