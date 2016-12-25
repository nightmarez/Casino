<!DOCTYPE html>
<html>
	<head>
		<title>VALVEMOVIE</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="body">
			<div id="body-left">
				<br><br><br>
				<div class="left-button" style="background-image: url('imgs/_butt_torq_passive.png'); color: gray;">Выплатить</div>
				<div class="left-button" id="btn-ratings">Рейтинги</div>
				<div class="left-button" id="btn-back">Назад</div>
			</div>
			<div id="body-right">
				<div id="rules-block">
					<h1>Правила</h1>
					<p>
						Перед началом очередного розыгрыша лотереи «Кено» выберите от 1 до 10 из 80 имеющихся в таблице шаров.
						Для простоты при выборе можно воспользоваться серыми шарами, которые случайным образом выбирают в таблице столько
						шаров, сколько написано на сером шаре. Установите размер ставки и нажмите кнопку «Применить ставку».
						Можно выбрать до 10 ставок за одну игру. Выбранные ставки будут показываться в специальной таблице.
						Числа в лотерее «Кено» выпадают случайным образом, поэтому говорить о какой-то стратегии игры здесь вряд ли
						уместно. И всё же, внимательные игроки в лото «Кено» сделали несколько наблюдений.
					</p>
					<p>
						Нужно выбирать числа, которые не выпадали в предыдущем розыгрыше. Таблицу с номерами шаров, сыгравших за последние
						несколько туров, вы можете видеть внизу под игровым полем. Не стоит выбирать номера, которые выпадают редко.
						Лучше всего ставить на те шары, которые выпадают чаще основных. Конечно, эта стратегия игры идёт вразрез
						с теорией вероятности.
					</p>
					<p>
						Некоторые игроки в лотерею «Кено» утверждают, что вероятность выигрыша увеличивается, если выбирать
						последовательные числа (например, 20,21 или 78,79). После набора нужного количества ставок нажмите
						кнопку «Играть» и следите за выпадающими шарами. История последних тиражей записывается в таблицу,
						красной строкой отмечен последний тираж.
					</p>
				</div>

				<div id="rules-table">
					<span class="table-title" style="padding-top: 4px; height: 33px;">Выигрыши просчитываются согласно<br>следующей таблице:</span>
					<div>

					</div>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function() {
				showBalance();

				$('#btn-back').click(function() {
					window.location.href = '/';
				});
			});
		</script>

		<div id="chat-layer">
			<a href="/" id="close-chat-layer">[x]</a>
			<div id="chat-layer-text"></div>
			<input type="text" id="chat-input" />
		</div>

		<script>
			$(document).ready(function() {
				showBalance();

				<?php
					if ($authorization !== false)
					{
						if (isChatEnabled())
						{
							?>
								initChat(<?php echo $authorization; ?>);
							<?php
						}
					}
				?>
			});
		</script>
	</body>
</html>