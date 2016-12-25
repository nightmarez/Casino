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
					<!--
						<th>id</th>
					-->
					<th>Название</th>
					<th>Иконка</th>
					<th>Математика</th>
					<th>Доступ</th>
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
					$req = $db->prepare('SELECT `id`, `title`, `thmb`, `path`, `categories`, `presets`, `preset`, `access` FROM `programs`;');
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
						
					while (list($id, $title, $thmb, $path, $categories, $presets, $preset, $access) = $req->fetch(PDO::FETCH_NUM))
					{
						if ($catid == 0 || in_array($catid, explode(',', $categories)))
						{
							if ($i >= $start && $i < $start + $limit) {
								?>
									<tr>
										<!--
											<td><a href="/admin/program.php?id=<?php echo $id; ?>"><?php echo $id; ?></a></td>
										-->
										<td><a href="/admin/program.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a></td>
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
										<td>
											<select class="access-select" style="min-width: 100px; min-height: 30px; text-align: center;" id="access-<?php echo $id; ?>">
												<option value="1" <?php echo ($access == 1 ? 'selected' : ''); ?>>Есть</option>
												<option value="0" <?php echo ($access == 0 ? 'selected' : ''); ?>>Нет</option>
											</select>
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

				$.get('/admin/setpreset.php?game=' + id + '&preset=' + value, function(result) {
					if (result.trim() == 'OK') {
						alertify.success('Изменения сохранены'); 
					} else {
						alertify.error('Не удалось сохранить'); 
					}
				});
			});

			$('select.access-select').change(function() {
				var id = parseInt($($(this).parent().parent().find('td')[0]).text());
				var value = parseInt($(this).val());

				$.get('/admin/setaccess.php?game=' + id + '&access=' + value, function(result) {
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
				<a href="/admin/programs.php?category=<?php echo $catid; ?>&page=1" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$pages = ceil($count / $limit);

				for ($i = 0; $i < $pages; ++$i)
				{
					?>
						<li <?php echo $i == $page - 1 ? 'class="active"' : ''; ?>>
							<a href="/admin/programs.php?category=<?php echo $catid; ?>&page=<?php echo ($i + 1); ?>"><?php echo ($i + 1); ?></a>
						</li>
					<?php
				}
			?>

			<li>
				<a href="/admin/programs.php?category=<?php echo $catid; ?>&page=<?php echo $pages; ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<script>
		///////////////////////////////////////////////////// game search field /////////////////////////////////////////////////////
		window.disableDefaultSearch = true;
		
		$(document).ready(function() {
			var lastResult = [];

            var func = function (text, f) {
                $.get('/admin/listgames.php', function(result) {
                	result = JSON.parse(result);
                	lastResult = [];
                	var count = 0;

                	text = text.toLowerCase();
                    _.each(result, function (item) {
                    	if (lastResult.length > 10) {
                    		return;
                    	}

                        if (item[1].length >= text.length && text == item[1].substr(0, text.length).toLowerCase()) {
                            lastResult.push(item);
                        } 
                    });

                    //lastResult = lastResult.concat([[0, '...']]);
                	f(_.map(lastResult, function(r) { return r[1]; }));
                });
            };

            $('#search-area').searchPlugin(func, function (keyResult) {
            	location.href = '/admin/program.php?id=' + lastResult[keyResult][0];
            });
		});
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>