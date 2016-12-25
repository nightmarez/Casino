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
		if (!isset($_GET['gameid']))
		{
			die();
		}

		$gameid = intval($_GET['gameid']);

		if ($gameid <= 0)
		{
			die();
		}

		$db = new PdoDb();
		$req = $db->prepare('SELECT `title`, `path` FROM `programs` WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();

		while (list($title, $path) = $req->fetch(PDO::FETCH_NUM))
		{
	?>
			<div class="panel panel-default">
				<div class="panel-heading">Символы игры <a href="/admin/program.php?id=<?php echo $gameid; ?>"><b><?php echo htmlspecialchars($title); ?></b></a></div>

				<table class="table">
					<thead>
						<tr>
							<th>Значение</th>
							<th>Изображение</th>
							<th>Специальное значение</th>
							<th>Действие</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$symbolspath = '../games/' . getGamePathById($gameid) . '/imgs/symbols/';
							$db1 = new PdoDb();
							$req1 = $db1->prepare('SELECT `id`, `name`, `filename`, `attr` FROM `symbols` WHERE `gameid`=:gameid ORDER BY `name`, `id`;');
							$req1->bindParam(':gameid', $gameid, PDO::PARAM_INT);
							$req1->execute();

							while (list($itemid, $itemname, $itemfilename, $itemattr) = $req1->fetch(PDO::FETCH_NUM))
							{
								?>
									<tr>
										<td><?php echo htmlspecialchars($itemname); ?></td>
										<td>
											<?php
												if (file_exists($symbolspath . '/' . $itemfilename . '.jpg')) {
													$fullitemname = $symbolspath . '/' . $itemfilename . '.jpg';
												} else if (file_exists($symbolspath . '/' . $itemfilename . '.png')) {
													$fullitemname = $symbolspath . '/' . $itemfilename . '.png';
												}

												if ($fullitemname)
												{
													?>
														<div style="float: left; width: 64px; margin-right: 10px;">
															<img src="<?php echo $fullitemname; ?>" style="float: left; width: 64px; height: 64px;">
														</div>
													<?php
												}
											?>
										</td>
										<td>
											<select class="select-attr" data-id="<?php echo $itemid; ?>">
												<option value="0" <?php echo ($itemattr == 0 ? 'selected' : ''); ?>>Нет</option>
												<option value="1" <?php echo ($itemattr == 1 ? 'selected' : ''); ?>>Substitute</option>
												<option value="2" <?php echo ($itemattr == 2 ? 'selected' : ''); ?>>Scatter</option>
												<option value="3" <?php echo ($itemattr == 3 ? 'selected' : ''); ?>>Substitute + Scatter</option>
											</select>
										</td>
										<td>
											<div style="float: left;">
												<form action="/admin/deletesymbol.php" method="POST">
													<input type="hidden" name="id" value="<?php echo $itemid; ?>">
													<input type="hidden" name="gameid" value="<?php echo $gameid; ?>">
													<input type="submit" value="Удалить">
												</form>
											</div>
											<div style="float: left; margin-left: 20px;">
												<div style="float: left;" class="change-name-btn" id="change-name-btn-<?php echo $itemid; ?>">
													<input type="button" value="Сменить имя">
												</div>
												<div style="float: left; display: none;">
													<input type="text" placeholder="новое имя">
													<input class="change-name-btn-commit" type="button" value="Сохранить имя">
													<input class="change-name-btn-cancel" type="button" value="Отмена">
												</div>
											</div>
										</td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>

			<div class="panel panel-default">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<form enctype="multipart/form-data" action="/admin/addsymbol.php" method="POST">
									<input type="hidden" name="gameid" value="<?php echo $gameid; ?>">
									<p>Файл изображения:</p>
									<input type="file" name="img">
									<br>
									<input type="submit" value="Добавить">
								</form>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
	<?php
		}
	?>

	<script>
		$(document).ready(function() {
			// change symbols attributes
			$('select.select-attr').change(function() {
				var id = parseInt($(this).attr('data-id'));
				var value = parseInt($(this).val());

				$.get('/admin/setsymbolattr.php?game=' + <?php echo $gameid; ?> + '&id=' + id + '&value=' + value, function(result) {
					if (result.trim() == 'OK') {
						alertify.success('Изменения сохранены'); 
					} else {
						alertify.error('Не удалось сохранить'); 
					}
				});
			});

			// show fields for changing symbols names
			$('.change-name-btn').click(function() {
				_.each($('.change-name-btn'), function(btnCnLayer) {
					$(btnCnLayer).parent().find('div:last-child').hide();
					$(btnCnLayer).show();
				});

				$(this).hide();
				$(this).parent().find('div:last-child').show();
			});

			_.each($('.change-name-btn'), function(btnCnLayer) {
				btnCnLayer = $(btnCnLayer).parent().find('div:last-child');

				// commit symbol name changing
				btnCnLayer.find('.change-name-btn-commit').click(function() {
					var id = parseInt(_.last($(this).parent().parent().find('.change-name-btn').attr('id').split('-')));
					var value = $(this).parent().find('input[type=text]').val();

					$.get('/admin/changesymbolname.php?game=' + <?php echo $gameid; ?> + '&id=' + id + '&value=' + value, function(result) {
						if (result.trim() == 'OK') {
							location.reload();
						} else {
							alertify.error('Не удалось сохранить'); 
						}
					});
				});

				// hide fields for changing symbols names
				btnCnLayer.find('.change-name-btn-cancel').click(function() {
					$(this).parent().find('input[type=text]').val('');
					$(this).parent().hide();
					$(this).parent().parent().find('.change-name-btn').show();
				});
			});
		});
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>