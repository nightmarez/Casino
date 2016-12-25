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

	<div class="panel panel-default">
		<div class="panel-heading">Мониторинг</div>

		<table class="table">
			<thead>
				<tr>
					<th>№</th>
					<th>Версия</th>
					<th>Адрес</th>
					<th>Блокировка</th>
					<th>Последняя связь</th>
					<th>Последний платёж</th>
					<th>Платежей сегодня</th>
					<th>Терминало- дни</th>
					<th>Купюр / сумма в купюрнике</th>
					<th>Купюр в диспенсере</th>
					<th>Внесено / выдано сегодня</th>
					<th>Выручка за сегодня</th>
					<th>Модем</th>
					<th>Принтер</th>
					<th>SIM</th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
			
	<?php require_once('footer.php'); ?>
<?php
	}
?>