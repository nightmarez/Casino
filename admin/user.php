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
		<div class="panel-heading">Пользователь</div>

		<table class="table">
			<tbody>

			<?php
				$userid = intval($_GET['id']);

				$db = new PdoDb();
				$req = $db->prepare('SELECT * FROM `users` WHERE `id`=:userid;');
				$req->bindParam(':userid', $userid, PDO::PARAM_INT);
				$req->execute();
								
				while (list($id, $login, $pass, $level, $activated, $sms, $money, $ban, $fullname, $rating, $lobbyaccess) = $req->fetch(PDO::FETCH_NUM))
				{
					?>
						<tr>
							<td>id</td>
							<td id="user-id"><?php echo $id; ?></td>
						</tr>
						<tr>
							<td>Логин</td>
							<td><?php echo $login; ?></td>
						</tr>
						<tr>
							<td>Блокировка</td>
							<td>
								<select class="ban-select">
									<option value="0" <?php echo $ban == 0 ? 'selected' : ''; ?>>Работает</option>
									<option value="1" <?php echo $ban == 1 ? 'selected' : ''; ?>>Заблокирован</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Последняя связь</td>
							<td>
								<?php
									$activity = getUserActivity($id);

									if ($activity == false)
									{
										echo 'нет данных';
									}
									else
									{
										echo $activity;
									}
								?>
							</td>
						</tr>
						<tr>
							<td>Последний платёж</td>
							<td>Нет данных</td>
						</tr>
						<tr>
							<td>Баланс</td>
							<td>
								<a href="/admin/payments.php?id=<?php echo $id; ?>"><?php echo $money; ?></a>
							</td>
						</tr>
						<tr>
							<td>in / out</td>
							<td>0 / 0</td>
						</tr>
						<tr>
							<td>Бонусы</td>
							<td>0</td>
						</tr>
						<tr>
							<td>Рейтинг</td>
							<td>-</td>
						</tr>
						<tr>
							<td>Максимальный выигрыш</td>
							<td>-</td>
						</tr>
						<tr>
							<td>Тиражи</td>
							<td>
								<?php
									$db1 = new PdoDb();
									$req1 = $db->prepare('SELECT COUNT(*) FROM `games` WHERE `user`=:id AND LENGTH(`balls`) > 0;');
									$req1->bindParam(':id', $id, PDO::PARAM_INT);
									$req1->execute();
									$gamescount = $req1->fetchColumn();
								?>
								<a href="/admin/games.php?id=<?php echo $id; ?>"><?php echo $gamescount; ?></a>
							</td>
						</tr>
						<tr>
							<td>Доступ к Лобби</td>
							<td>
								<?php
									if ($level == 1 || $level == 3) {
										?>Доступен<?php
									} else {
										?>
											<select class="lobby-access-select">
												<option value="0" <?php echo $lobbyaccess == 0 ? 'selected' : ''; ?>>Заблокирован</option>
												<option value="1" <?php echo $lobbyaccess == 1 ? 'selected' : ''; ?>>Доступен</option>
											</select>
										<?php
									}
								?>
							</td>
						</tr>
					<?php
					break;
				}
			?>

			</tbody>
		</table>
	</div>

	<script>
		$(document).ready(function() {
			$('select.ban-select').change(function() {
				var id = parseInt($('#user-id').text());
				var value = parseInt($(this).val());

				$.get('/admin/setban.php?id=' + id + '&ban=' + value, function(result) {
					if (result.trim() == 'true') {
						alertify.success('Изменения сохранены'); 
					} else {
						alertify.error('Не удалось сохранить'); 
					}
				});
			});

			$('select.lobby-access-select').change(function() {
				var id = parseInt($('#user-id').text());
				var value = parseInt($(this).val());

				$.get('/admin/setlobbyaccess.php?id=' + id + '&lobbyaccess=' + value, function(result) {
					if (result.trim() == 'true') {
						alertify.success('Изменения сохранены'); 
					} else {
						alertify.error('Не удалось сохранить'); 
					}
				});
			});
		});
	</script>

	<script>
		///////////////////////////////////////////////////// user search field /////////////////////////////////////////////////////
		window.disableDefaultSearch = true;
		
		$(document).ready(function() {
			var lastResult = [];

            var func = function (text, f) {
                $.get('/admin/listusers.php', function(result) {
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
            	location.href = '/admin/user.php?id=' + lastResult[keyResult][0];
            });
		});
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>