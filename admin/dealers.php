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
		<div class="panel-heading">Счета</div>

		<table class="table">
			<thead>
				<tr>
					<th>Номер счёта</th>
					<th>Логин</th>
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
					$req = $db->prepare('SELECT * FROM `users`');
					$req->execute();
						
					while (list($id, $login, $pass, $level, $activated, $sms, $money, $ban) = $req->fetch(PDO::FETCH_NUM))
					{
						if ($level != 3) {
							continue;
						}

						?>
						<tr>
							<td><a href="/admin/user.php?id=<?php echo $id; ?>"><?php echo $id; ?></a></td>
							<td><a href="/admin/user.php?id=<?php echo $id; ?>"><?php echo $login; ?></a></td>
							<td>RUR</td>
							<td>0</td>
							<td><a href="/admin/payments.php?id=<?php echo $id; ?>"><?php echo $money; ?></a></td>
							<td>нет данных</td>
							<td>0</td>
							<td><?php echo $level == 1 ? 'Администратор' : 'Пользователь'; ?></td>
							<td>
								<select class="ban-select">
									<option value="0" <?php echo $ban == 0 ? 'selected' : ''; ?>>Работает</option>
									<option value="1" <?php echo $ban == 1 ? 'selected' : ''; ?>>Заблокирован</option>
								</select>
							</td>
						</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>

	<script>
		$(document).ready(function() {
			$('select.ban-select').change(function() {
				var id = parseInt($($(this).parent().parent().find('td')[0]).text());
				var value = parseInt($(this).val());

				$.get('/admin/setban.php?id=' + id + '&ban=' + value, function(result) {
					if (result.trim() == 'true') {
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
				<a href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<li class="active">
				<a href="#">1</a>
			</li>

			<li>
				<a href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<script>
		/////////////////////////////////////////////////// dealers search field ////////////////////////////////////////////////////
		window.disableDefaultSearch = true;
		
		$(document).ready(function() {
			var lastResult = [];

            var func = function (text, f) {
                $.get('/admin/listdealers.php', function(result) {
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