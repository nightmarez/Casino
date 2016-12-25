<?php require_once('userzone.php'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Мой счёт</title>
		<?php require_once('head.php'); ?>
		<style>
			body {
				background-color: #787b7d;
			}

			div#body {
				background-image: url('../imgs/d-bg.png');
    			background-repeat: repeat-x;
				background-color: #787b7d;
			}

			#body-inner {
				width: 822px;
				margin: 0 auto;
				margin-top: 50px;
			}

			#body-inner > div {
				display: inline-block;
				color: black;
				vertical-align: top;
			}

			#body-inner > div a:link, #body-inner > div a:hover, #body-inner > div a:visited {
				color: black;
				text-decoration: none;
			}

			#body-inner > div:first-child {
				width: 20%;
				background-color: lightgray;
				font-size: 12px;
			}

			#body-inner > div:first-child > b {
				background-color: #aaa;
				color: white;
				padding: 10px;
				display: block;
			}

			#body-inner > div:first-child > ul > li {
				padding: 10px;
			}

			#body-inner > div:last-child {
				margin-left: 20px;
				width: calc(80% - 50px);
				background-color: white;
				padding-top: 8px;
				padding-left: 10px;
				padding-right: 10px;
				padding-bottom: 8px;
			}

			#body-inner > div:last-child > b {
				color: #0099CC;
				display: block;
			}

			#body-inner > div:last-child > span {
				margin-top: 20px;
				display: block;
				min-height: 170px;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="body">
			<div id="body-inner">
				<div style="border: 1px solid black;">
					<b>Мой счёт</b>
					<ul>
						<li><a href="/bill.php?page=fill">Пополнить счёт</a></li>
						<li><a href="/bill.php?page=bonus">Мой бонус</a></li>
						<li><a href="/bill.php?page=bill">Состояние счёта</a></li>
						<li><a href="/bill.php?page=settings">Настройки</a></li>
						<li><a href="/bill.php?page=friends">Пригласить друзей</a></li>
						<li><a href="/bill.php?page=limit">Лимит депозита</a></li>
					</ul>
				</div>
				<div style="border: 1px solid black;">
					<?php
						$page = 'main';

						if (isset($_GET['page']))
						{
							$page = htmlspecialchars($_GET['page']);
						}
					?>

					<?php
						if ($page == 'main') {
					?>
						<b>Мой счёт</b>
						<span>
							Пользователи, играющие на деньги, могут получить в этом разделе всю информацию о своём
							счёте: законченных играх и произведённых транзакциях. Здесь же можно внести депозит или
							отправить запрос на выплату средств.
						</span>
					<?php
						} else if ($page == 'fill') {
					?>
						<b>Пополнить счёт</b>
						<span>

						</span>
					<?php
						} else if ($page == 'bonus') {
					?>
						<b>Мой бонус</b>
						<span>
							На данный момент у вас нет бонусов.
						</span>
					<?php
						} else if ($page == 'bill') {
					?>
						<b>Состояние счёта</b>
						<span>
							На вашем счету:&nbsp;
							<?php
								$userid = intval(getAuthorization());
								$db = new PdoDb();
								$req = $db->prepare('SELECT `money` FROM `users` WHERE `id`=:userid;');
								$req->bindParam(':userid', $userid, PDO::PARAM_INT);
								$req->execute();

								while (list($money) = $req->fetch(PDO::FETCH_NUM))
								{
									echo $money;
									break;
								}
							?>
							<br>
							<br>
							Ваши последние транзакции:
							<br>
							<table>
								<thead>
									<tr>
										<th>Сумма</th>
										<th>Дата</th>
										<th>Описание</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$req = $db->prepare('SELECT `money`, `date`, `type` FROM `payments` WHERE `userid`=:userid ORDER BY `id` DESC LIMIT 0, 10;');
										$req->bindParam(':userid', $userid, PDO::PARAM_INT);
										$req->execute();

										while (list($money, $date, $type) = $req->fetch(PDO::FETCH_NUM))
										{
											?>
												<tr>
													<td><?php echo $money; ?></td>
													<td><?php echo $date; ?></td>
													<td><?php echo $type; ?></td>
												</tr>
											<?php
										}

										$db->close();
									?>
								</tbody>
							</table>
						</span>
					<?php
						} else if ($page == 'settings') {
					?>
						<b>Настройки</b>
						<span>

						</span>
					<?php
						} else if ($page == 'friends') {
					?>
						<b>Пригласить друзей</b>
						<span>
							E-mail: <input type="text" style="margin-left: 20px; margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;"><br>
							E-mail: <input type="text" style="margin-left: 20px; margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;"><br>
							E-mail: <input type="text" style="margin-left: 20px; margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;"><br>
							E-mail: <input type="text" style="margin-left: 20px; margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;"><br>
							E-mail: <input type="text" style="margin-left: 20px; margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;"><br>
							E-mail: <input type="text" style="margin-left: 20px; margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;"><br>
							<br>
							<input type="button" value="Отправить приглашения" style="margin-top: 15px; width: 160px; height: 30px; margin-bottom: 15px;">
						</span>
					<?php
						} else if ($page == 'limit') {
					?>
						<b>Лимит депозита</b>
						<span>
							Установить месячный лимит депозита:
							<br>
							<input type="text" style="margin-top: 15px; padding: 8px; border: #e5e5e5 solid 1px; line-height: 1; background: #fff; border-radius: 3px; font-size: 16px; width: 300px;">
							<br>
							<input type="button" value="Сохранить" style="margin-top: 15px; width: 100px; height: 30px; margin-bottom: 15px;">
						</span>
					<?php
						}
					?>
				</div>
			</div>

			<?php include_once('storeblock.php'); ?>
		</div>

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