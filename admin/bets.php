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
		<?php
			$start = 0;
			$limit = 15;
			$page = 1;
			$maxPagesCount = 20;
		?>

		<div class="panel-heading">Ставки</div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Тираж</th>
					<th>Пользователь</th>
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

					// limit pages
					if ($count > $limit * $maxPagesCount)
					{
						$count = $limit * $maxPagesCount;
					}

					if (isset($_GET['page']))
					{
						$page = intval($_GET['page']);
						$start = ($page - 1) * $limit;

						if ($start < 0)
						{
							$start = 0;
						}

						if ($start >= $count)
						{
							$start = floor($count / $limit) * $limit;
						}
					}

					$db = new PdoDb();
					$req = $db->prepare('SELECT t1.id, t1.gameid, t1.bet, t1.balls, t2.user FROM `bets` AS t1, `games` AS t2 WHERE t1.gameid = t2.id ORDER BY `id` DESC LIMIT :start, :limit;');
					$req->bindParam(':start', $start, PDO::PARAM_INT);
					$req->bindParam(':limit', $limit, PDO::PARAM_INT);
					$req->execute();

					while (list($id, $gameId, $bet, $balls, $user) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td><?php echo $id; ?></td>
								<td><a href="/admin/game.php?id=<?php echo $gameId; ?>"><?php echo $gameId; ?></a></td>
								<td>
									<?php
										$db1 = new PdoDb();
										$req1 = $db->prepare('SELECT * FROM `users` WHERE `id`=:user;');
										$req1->bindParam(':user', $start, PDO::PARAM_INT);
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
								<td><?php echo $bet; ?></td>
								<td><?php echo $balls; ?></td>
							</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>

	<nav>
		<ul class="pagination">
			<li>
				<a href="/admin/bets.php?page=1" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$pages = ceil($count / $limit);

				for ($i = 0; $i < $pages; ++$i)
				{
					?>
						<li <?php echo $i == $page - 1 ? 'class="active"' : ''; ?>>
							<a href="/admin/bets.php?page=<?php echo ($i + 1); ?>"><?php echo ($i + 1); ?></a>
						</li>
					<?php
				}
			?>

			<li>
				<a href="/admin/bets.php?page=<?php echo $pages; ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<?php require_once('footer.php'); ?>
<?php
	}
?>