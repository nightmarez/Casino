<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['gameid']))
		{
			die();
		}

		$gameid = intval($_GET['gameid']);

		if ($gameid <= 0)
		{
			die();
		}

		if (!isset($_GET['presetid'])) 
		{
			die();
		}

		$presetid = htmlspecialchars($_GET['presetid']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT `title` FROM `programs` WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();
		$title = htmlspecialchars($req->fetch(PDO::FETCH_ASSOC)['title']);
?>
	<?php require_once('header.php'); ?>

	<div class="panel panel-default">
		<div class="panel-heading">Игра <a href="/admin/program.php?id=<?php echo $gameid; ?>"><b><?php echo $title ?></b></a>, лента <?php echo $presetid; ?></div>

		<div>
			<table class="table">
				<tbody>
					<tr>
						<td>Количество итераций</td>
						<td>
							<input id="input-iter-count" type="number" value="1000" min="1">
							<input id="start-stop-button" type="button" value="Пуск!">
						</td>
					</tr>
					<tr>
						<td>Количество итераций игры на один запрос к серверу</td>
						<td>
							<input id="input-iter-count-per-request" type="number" value="100" min="1">
						</td>
					</tr>
					<tr>
						<td>Бот</td>
						<td id="bot-name">-</td>
					</tr>
					<tr>
						<td>Процесс</td>
						<td id="output-process">Не запущен</td>
					</tr>
					<tr>
						<td>Результаты</td>
						<td id="output-results">Нет</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			var botId = false;
			var currentCount = 0;
			var targetCount = 0;
			var playCountsPerIteration = parseInt($('#input-iter-count-per-request').val());

			function iteration() {
				currentCount += playCountsPerIteration;
				var percents = (currentCount / targetCount * 100).toFixed(2);
				$('#output-process').text(percents + '%');

				$.get('/admin/botplay.php?playcount=' + playCountsPerIteration + '&botid=' + botId + '&gameid=<?php echo $gameid; ?>&presetid=<?php echo $presetid; ?>', function(result) {
					if (result.trim() == 'OK') {

						setTimeout(function() {
							if (botId && currentCount < targetCount) {
								iteration();
							} else {
								$.get('/admin/getbotresults.php?botid=' + botId, function(result) {
									result = result.split('|');
									var games = parseInt(result[0]);
									var spent = parseInt(result[1]);
									var win = parseInt(result[2]);

									$('#output-results').html('<table><tr><td>Игр</td><td>' + games + '</td></tr><tr><td>Потрачено</td><td>' + spent + '</td></tr><tr><td>Выиграно</td><td>' + win + '</td></tr><tr><td style="padding-right: 50px;">Процент выигрыша</td><td>' + ((win / spent * 100).toFixed(2)) + '</td></tr></table>');

									$.get('/admin/deletebot.php?botid=' + botId, function(result) {
										botId = false;
										$('#input-iter-count').attr('disabled', false);
										$('#start-stop-button').val('Пуск!');
										$('#bot-name').text(botId ? botId : '-');
										$('#output-process').text('Завершён');
									});
								});
							}
						}, 1);

					} else {
						alert('Что-то пошло не так. Эмуляция остановлена.');
						botId = false;
					}
				});
			}

			$('#start-stop-button').click(function() {
				if (botId) {
					$.get('/admin/deletebot.php?botid=' + botId, function(result) {
						if (result.trim() == 'OK') {
							botId = false;
							$('#input-iter-count').attr('disabled', false);
							$('#start-stop-button').val('Пуск!');
							$('#bot-name').text(botId ? botId : '-');
							$('#output-process').text('Не запущен');
							$('#output-results').text('Нет');
						} else {
							alert('Произошла непредвиденная ошибка. Страница будет перезагружена.');
							location.reload();
						}
					});
				} else {
					botId = 'bot:' + GUID();

					$.get('/admin/createbot.php?botid=' + botId, function(result) {
						if (result.trim() == 'OK') {
							$('#bot-name').text(botId);
							$('#input-iter-count').attr('disabled', true);
							$('#start-stop-button').val('Стоп!');
							$('#output-results').text('Нет');
							$('#output-process').text('0%');
							currentCount = 0;
							targetCount = parseInt($('#input-iter-count').val());

							// запуск
							iteration();
						} else {
							alert('Произошла непредвиденная ошибка. Страница будет перезагружена.');
							location.reload();
						}
					});
				}
			});
		});
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>