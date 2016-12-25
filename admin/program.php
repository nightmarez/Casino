<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['id']))
		{
			die();
		}

		$id = intval($_GET['id']);

		if ($id < 0){
			die();
		} else if ($id == 0) {
			header('Location: /admin/programs.php');
			die();
		}
?>
	<?php require_once('header.php'); ?>

	<?php
		$db = new PdoDb();
		$req = $db->prepare('SELECT `id`, `title`, `thmb`, `path`, `categories`, `presets`, `preset`, `access` FROM `programs` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($id, $title, $thmb, $path, $categories, $presets, $preset, $access) = $req->fetch(PDO::FETCH_NUM))
		{
	?>

		<div class="panel panel-default">
			<div class="panel-heading">Игра <a href="/admin/program.php?id=<?php echo $id; ?>"><b><?php echo htmlspecialchars($title); ?></b></a></div>

			<table class="table">
				<tbody>
					<tr>
						<td>Иконка</td>
						<td>
							<img src="../lobby/icons/<?php echo $thmb; ?>">
						</td>
					</tr>
					<tr>
						<td>Математика</td>
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
					<tr>
						<td>Доступ</td>
						<td>
							<select class="access-select" style="min-width: 100px; min-height: 30px; text-align: center;" id="access-<?php echo $id; ?>">
								<option value="1" <?php echo ($access == 1 ? 'selected' : ''); ?>>Есть</option>
								<option value="0" <?php echo ($access == 0 ? 'selected' : ''); ?>>Нет</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><a href="/admin/symbols.php?gameid=<?php echo $id; ?>">Символы</a></td>
						<td>
							<?php
								$symbols = array();
								$symbolspath = '../games/' . getGamePathById($id) . '/imgs/symbols/';
								$db1 = new PdoDb();
								$req1 = $db1->prepare('SELECT `id`, `name`, `filename`, `attr` FROM `symbols` WHERE `gameid`=:id ORDER BY `name`;');
								$req1->bindParam(':id', $id, PDO::PARAM_INT);
								$req1->execute();

								while (list($itemid, $itemname, $itemfilename, $itemattr) = $req1->fetch(PDO::FETCH_NUM))
								{
									$fullitemname = false;

									if (file_exists($symbolspath . '/' . $itemfilename . '.jpg')) {
										$fullitemname = $symbolspath . '/' . $itemfilename . '.jpg';
									} else if (file_exists($symbolspath . '/' . $itemfilename . '.png')) {
										$fullitemname = $symbolspath . '/' . $itemfilename . '.png';
									}

									$symbols["'" . $itemname . "'"] = $fullitemname;

									if ($fullitemname)
									{
										?>
											<div style="float: left; width: 64px; margin-right: 10px;">
												<img src="<?php echo $fullitemname; ?>" style="float: left; width: 64px; height: 64px;">
												<div style="text-align: center; float: left; width: 64px;"><?php echo $itemname; ?></div>
											</div>
										<?php
									}
								}
							?>
						</td>
					</tr>
					<tr>
						<td>Ленты</td>
						<!--
						<td><a href="/admin/tapes.php?gameid=<?php echo $id; ?>">Ленты</a></td>
						-->
						<td>
							<table class="table">
								<tbody>
									<tr>
										<?php
											$db2 = new PdoDb();
											$req2 = $db->prepare('SELECT `presetid`, `stringid` FROM `presets` WHERE `gameid`=:id ORDER BY `id`, `presetid`, `stringid`;');
											$req2->bindParam(':id', $id, PDO::PARAM_INT);
											$req2->execute();
											$used = array();

											while (list($presetid, $stringid) = $req2->fetch(PDO::FETCH_NUM))
											{
												if (!in_array($presetid, $used)) {
													$used[] = $presetid;

													?>
														<td><a href="/admin/tapes.php?gameid=<?php echo $id; ?>&presetid=<?php echo $presetid; ?>"><?php echo $presetid; ?></a></td>
													<?php
												}
											}
										?>
										<td style="padding-left: 100px;">
											<input type="text" name="name" id="new-preset-name" placeholder="Название">
											<input type="button" id="add-preset-btn" value="Добавить">

											<script>
												$(document).ready(function() {
													$('#add-preset-btn').click(function() {
														var _this = this;
														var value = $('#new-preset-name').val();
														$.get('/admin/addpreset.php?gameid=<?php echo $id; ?>&name=' + value, function(result) {
															if (result.trim() == 'OK') {
																$(_this).parent().prepend('<td><a href="/admin/tapes.php?gameid=<?php echo $id; ?>&presetid=' + value + '">' + value + '</a></td>');
																alertify.success('Изменения сохранены'); 
															} else {
																alertify.error('Не удалось добавить'); 
															}
														});
													})
												});
											</script>
										</td>
									</tr>
								</tbody>
							</table>
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
			$('select.preset-select').change(function() {
				var id = parseInt($(this).attr('id').split('-')[1]);
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
				var id = parseInt($(this).attr('id').split('-')[1]);
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