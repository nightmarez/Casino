<?php
	require_once('../utils.php');

	if (!dealerZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['id'])) {
			die();
		}

		$id = intval($_GET['id']);

?>
	<?php require_once('header.php'); ?>

	<div class="panel panel-default">
		<?php
			$start = 0;
			$limit = 10;
			$page = 1;

			$db = new PdoDb();
			$req = $db->prepare('SELECT COUNT(*) FROM `pgames` WHERE `user`=:id;');
			$req->bindParam(':id', $id, PDO::PARAM_INT);
			$req->execute();
			$count = $req->fetchColumn();

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
		?>

		<div class="panel-heading">Игры</div>

		<table class="table">
			<thead>
				<tr>
					<th>Игра</th>
					<th>ID спина</th>
					<th>Дата</th>
					<th>Ставка на линию</th>
					<th>Количество линий</th>
					<th>Суммарная ставка</th>
					<th>Итоговая матрица</th>
					<th>Спин</th>
					<th>Выигрыш</th>
					<th>Баланс</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT `id`, `gameid`, `bet`, `betlines`, `matrix`, `spin`, `win`, `time`, `money` FROM `pgames` WHERE `user`=:id ORDER BY `id` DESC LIMIT :start,:limit;');
					$req->bindParam(':id', $id, PDO::PARAM_INT);
					$req->bindParam(':start', $start, PDO::PARAM_INT);
					$req->bindParam(':limit', $limit, PDO::PARAM_INT);
					$req->execute();
						
					while (list($spinid, $gameid, $bet, $betlines, $matrix, $spin, $win, $time, $money) = $req->fetch(PDO::FETCH_NUM))
					{
						$gamepath = '/games/' . getGamePathById($gameid) . '/imgs/symbols/';

						?>
						<tr>
							<td style="width: 11%;">
								<a href="<?php echo '/games/' . getGamePathById($gameid); ?>">
									<?php echo htmlspecialchars(getGameNameById($gameid)); ?>
								</a>
							</td>
							<td style="width: 5%;">
								<?php echo $spinid; ?>
							</td>
							<td style="width: 6%;">
								<?php echo $time; ?>
							</td>
							<td style="width: 6%;">
								<?php echo $bet; ?>
							</td>
							<td style="width: 6%;">
								<?php echo $betlines; ?>
							</td>
							<td style="width: 6%;">
								<?php echo ($bet * $betlines); ?>
							</td>
							<td style="width: 27%; min-width: 275px;">
								<?php
									$matrixcolumns = explode('|', $matrix);
									$matrixitems = array();
									$i = 0;

									foreach ($matrixcolumns as $matrixcolumn) {
										$matrixitems[$i++] = explode(',', $matrixcolumn);
									}

									$matrixlines = array();

									for ($i = 0; $i < count($matrixitems[0]); ++$i) {
										$matrixline = array();

										for ($j = 0; $j < count($matrixcolumns); ++$j) {
											$matrixline[] = $matrixitems[$j][$i];
										}

										$matrixlines[] = $matrixline;
									}

									foreach ($matrixlines as $line) {
										?>
											<div style="width: 100%; float: left; margin-bottom: 5px; min-width: 275px;">
												<?php
													foreach($line as $item) {
														?>
															<div style="position: relative; width: 50px; height: 50px; background-size: contain; background-image: url('<?php echo $gamepath . $item . '.jpg' ?>'); float: left; margin-right: 5px;">
																<div style="position: absolute; left: 0; top: 0; width: 50px; height: 50px; background-size: contain; background-image: url('<?php echo $gamepath . $item . '.png' ?>'); float: left;">

																</div>
															</div>
														<?php
													}
												?>
											</div>
										<?php
									}
								?>
							</td>
							<td style="width: 27%; min-width: 300px;">
								<?php
									$spins = explode('|', $spin); 

									foreach ($spins as $spin) {
										?>
											<div style="width: 100%; float: left; margin-bottom: 5px; min-width: 300px;">
												<?php
													$kvp = explode('=', $spin);
													$any = false;
													$items = explode(',', $kvp[0]);

													if (count($kvp) > 1)
													{
														$currwin = $kvp[1];

														foreach($items as $item) {
															if (!strlen($item)) {
																continue;
															}

															$any = true;

															?>
																<div style="position: relative; width: 50px; height: 50px; background-size: contain; background-image: url(<?php echo $gamepath . $item . '.jpg' ?>); float: left; margin-right: 5px;">
																	<div style="position: absolute; left: 0; top: 0; width: 50px; height: 50px; background-size: contain; background-image: url(<?php echo $gamepath . $item . '.png' ?>); float: left;">

																	</div>
																</div>
															<?php
														}
													}
												
													if ($any) { ?>
														<span style="font-size: 18px; margin-top: 12px; float: left;">
															&nbsp;=&nbsp;<?php echo $currwin; ?>
														</span>
													<?php } ?>
											</div>
										<?php
									}
								?>
							</td>
							<td style="width: 7%; font-size: 18px;">
								<?php echo $win; ?>
							</td>
							<td style="width: 7%;">
								<?php echo $money; ?>
							</td>
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
				<a href="/dealer/usergames.php?page=1&id=<?php echo $id ? $id : 0; ?>" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$pages = ceil($count / $limit);

				for ($i = 0; $i < $pages; ++$i)
				{
					?>
						<li <?php echo $i == $page - 1 ? 'class="active"' : ''; ?>>
							<a href="/dealer/usergames.php?page=<?php echo ($i + 1); ?>&id=<?php echo $id ? $id : 0; ?>"><?php echo ($i + 1); ?></a>
						</li>
					<?php
				}
			?>

			<li>
				<a href="/dealer/usergames.php?page=<?php echo $pages; ?>&id=<?php echo $id ? $id : 0; ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<?php require_once('footer.php'); ?>
<?php
	}
?>