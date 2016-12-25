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

	<?php
		$catid = 0;
		$category = '';
		$limit = 10;

		if (isset($_GET['category']))
		{
			$catid = intval($_GET['category']);

			if ($catid > 0)
			{
				$db = new PdoDb();
				$req = $db->prepare('SELECT * FROM `categories`;');
				$req->execute();

				while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
				{
					if ($id == $catid)
					{
						$category = $title;
						break;
					}
				}
			}
		}
	?>

	<div class="panel panel-default">
		<div class="panel-heading">Игры <?php echo (strlen($category) > 0 ? ' (' . $category . ')' : ''); ?></div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Название</th>
					<th>Иконка</th>
					<th>Математика</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$count = 0;
					$start = 0;
					$page = 0;
					$db = new PdoDb();
					$req = $db->prepare('SELECT * FROM `programs`;');
					$req->execute();
						
					while (list($id, $title, $thmb, $path, $categories) = $req->fetch(PDO::FETCH_NUM))
					{
						if ($catid == 0 || in_array($catid, explode(',', $categories))) {
							++$count;
						}
					}

					////////////////////////////////////////////////////////////////////////////////////////////////////

					$db = new PdoDb();
					$req = $db->prepare('SELECT * FROM `programs`;');
					$req->execute();

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

					$i = 0;
						
					while (list($id, $title, $thmb, $path, $categories, $presets) = $req->fetch(PDO::FETCH_NUM))
					{
						if ($catid == 0 || in_array($catid, explode(',', $categories)))
						{
							if ($i >= $start && $i < $start + $limit) {
								?>
									<tr>
										<td><?php echo $id; ?></td>
										<td><?php echo $title; ?></td>
										<td><img src="../lobby/icons/<?php echo $thmb; ?>"></td>
										<td>
											<?php
												if (strlen($presets) == 0) {
													echo 'random';
												} else {
													?>
														<select class="preset-select" style="min-width: 100px; min-height: 30px; text-align: center;" id="preset-<?php echo $id; ?>">
															<?php
																$p = explode(',', $presets);

																foreach ($p as $preset) {
																	?>
																		<option value="<?php echo $preset; ?>">
																			<?php echo $preset; ?>
																		</option>
																	<?php
																}
															?>
														</select>
													<?php
												}
											?>
										</td>
									</tr>
								<?php
							}

							++$i;
						}						
					}
				?>
			</tbody>
		</table>
	</div>

	<script>
		$(document).ready(function() {
			$('select.preset-select').change(function() {
				var id = parseInt($($(this).parent().parent().find('td')[0]).text());
				var value = parseInt($(this).val());

				$.get('/dealer/setpreset.php?game=' + id + '&preset=' + value, function(result) {
					if (result.trim() == 'OK') {
						alertify.success('Изменения сохранены'); 
					} else {
						alertify.error('Не удалось сохранить'); 
					}
				});
			});
		});
	</script>

	<nav>
		<ul class="pagination">
			<li>
				<a href="/dealer/games.php?category=<?php echo $catid; ?>&page=1" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$pages = ceil($count / $limit);

				for ($i = 0; $i < $pages; ++$i)
				{
					?>
						<li <?php echo $i == $page - 1 ? 'class="active"' : ''; ?>>
							<a href="/dealer/games.php?category=<?php echo $catid; ?>&page=<?php echo ($i + 1); ?>"><?php echo ($i + 1); ?></a>
						</li>
					<?php
				}
			?>

			<li>
				<a href="/dealer/games.php?category=<?php echo $catid; ?>&page=<?php echo $pages; ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<?php require_once('footer.php'); ?>
<?php
	}
?>