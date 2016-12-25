<?php require_once('userzone.php'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Рейтинги</title>
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

			.ratings-column {
				display: inline-block;
				width: 33%;
				vertical-align: top;
				margin-top: 30px;
			}

			.ratings-column > span {
				color: black;
				margin-bottom: 10px;
				display: inline-block;
			}

			.ratings-column > span > b {
				font-weight: bold;
			}

			.ratings-column > div > div {
				margin-bottom: 10px;
				padding: 10px;
				background-color: #eee;
			}

			.ratings-column > div > div a:link, .ratings-column > div > div a:hover, .ratings-column > div > div:visited {
				text-decoration: none;
			}

			.ratings-column > div > div b {
				width: 100%;
				background-color: #eeffee;
				display: inline-block;
				padding: 5px;
			}

			.ratings-column > div > div i {
				width: calc(100% - 10px);
				display: inline-block;
				padding: 5px;
				padding-left: 1px;
			}

			.star {
				width: 14px;
				height: 14px;
				background-image: url('/imgs/star.png');
				background-repeat: no-repeat;
				background-size: contain;
				display: inline-block;
			}

			.star-dark {
				width: 14px;
				height: 14px;
				background-image: url('/imgs/star_dark.png');
				background-repeat: no-repeat;
				background-size: contain;
				display: inline-block;
			}

			.ratings-menu-item {
				margin: 1px;
				padding: 4px;
				display: inline-block;
				margin-left: -23px;
			}

			a:link.ratings-menu-item, a:hover.ratings-menu-item, a:visited.ratings-menu-item {
				text-decoration: none;
			}

			.ratings-menu-item:before {
				display: block;
				content: ' ';
				float: left;
				width: 0;
				height: 0;
				border-bottom: 25px solid #D3D3D3; 
				border-left: 10px solid transparent;
				margin-top: -3px;
			}

			.ratings-menu-item:after {
				display: block;
				content: ' ';
				float: right;
				width: 0;
				height: 0;
				border-bottom: 25px solid #D3D3D3; 
				border-right: 10px solid transparent;	
				margin-top: -3px;
			}

			.ratings-menu-item > span {
				background-color: #D3D3D3;
				padding-bottom: 5px;
				padding-left: 5px;
				padding-right: 5px;
				padding-top: 3px;
				font-size: 14px;
			}

			a:hover.ratings-menu-item > span {
				background-color: #eee;
			}

			a:hover.ratings-menu-item:before {
				border-bottom: 25px solid #eee; 
			}

			a:hover.ratings-menu-item:after {
				border-bottom: 25px solid #eee;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="body">
			<div id="body-center-block">
				<div style="margin: -37px 0 0 17px;">
					<a href="/ratings.php?time=week" class="ratings-menu-item"><span>Рейтинги за неделю</span></a>
					<a href="/ratings.php?time=month" class="ratings-menu-item"><span>Рейтинги за месяц</span></a>
					<a href="/ratings.php?time=all" class="ratings-menu-item"><span>Рейтинги за всё время</span></a>
				</div>

				<div>
					<div class="ratings-column">
						<span><b>TOP100</b> по играм</span>

						<div>
							<?php
								$db = new PdoDb();
								$req = $db->prepare('SELECT t1.id, t1.login, t2.count, t1.rating FROM `users` AS t1, (SELECT `user`, COUNT(*) AS `count` FROM `games` GROUP BY `user`) AS t2 WHERE t1.id = t2.user ORDER BY t2.count DESC LIMIT 0, 10;');
								$req->execute();

								while (list($id, $login, $count, $rating) = $req->fetch(PDO::FETCH_NUM))
								{
									$color = 'lightgray';

									if (startsWith($login, 'vk:'))
									{
										$color = '#547da9';
									}
									else if (startsWith($login, 'ok:'))
									{
										$color = '#f6a25a';
									}
									else if (startsWith($login, 'fb:'))
									{
										$color = '#738fc5';
									}

									?>
										<div style="display: inline-block; width: calc(100% - 20px);">
											<div style="width: 64px; height: 64px; float: left; border: 3px solid <?php echo $color; ?>; margin-right: 5px; background-image: url('<?php echo getUserThmbByLogin($login); ?>');">
											</div>
											<a href="/user.php?id=<?php echo $id; ?>" style="float: left; width: calc(100% - 82px);">
												<b><?php echo getUserNameByLogin($login); ?></b>
												<i>сыграно <?php echo $count; ?> игр</i>
											</a>
											<div style="margin-right: -5px;">
												<?php
													for ($j = 0; $j < 10 && $j < $rating; ++$j)
													{
														?>
															<div class="star"></div>
														<?php
													}

													for (; $j < 10; ++$j)
													{
														?>
															<div class="star-dark"></div>
														<?php
													}
												?>
											</div>
										</div>
									<?php
								}
							?>
						</div>
					</div>
					<div class="ratings-column">
						<span><b>TOP100</b> богатых</span>

						<div>
							<?php
								$db = new PdoDb();
								$req = $db->prepare('SELECT `id`, `login`, `money`, `rating` FROM `users` WHERE `level`=2 ORDER BY `money` DESC LIMIT 0, 10;');
								$req->execute();

								while (list($id, $login, $money, $rating) = $req->fetch(PDO::FETCH_NUM))
								{
									$color = 'lightgray';

									if (startsWith($login, 'vk:'))
									{
										$color = '#547da9';
									}
									else if (startsWith($login, 'ok:'))
									{
										$color = '#f6a25a';
									}
									else if (startsWith($login, 'fb:'))
									{
										$color = '#738fc5';
									}

									?>
										<div style="display: inline-block; width: calc(100% - 20px);">
											<div style="width: 64px; height: 64px; float: left; border: 3px solid <?php echo $color; ?>; margin-right: 5px; background-image: url('<?php echo getUserThmbByLogin($login); ?>');">
											</div>
											<a href="/user.php?id=<?php echo $id; ?>" style="float: left; width: calc(100% - 82px);">
												<b><?php echo getUserNameByLogin($login); ?></b>
												<br>
												<i><?php echo $money; ?></i>
											</a>
											<div style="margin-right: -5px;">
												<?php
													for ($j = 0; $j < 10 && $j < $rating; ++$j)
													{
														?>
															<div class="star"></div>
														<?php
													}

													for (; $j < 10; ++$j)
													{
														?>
															<div class="star-dark"></div>
														<?php
													}
												?>
											</div>
										</div>
									<?php
								}
							?>
						</div>
					</div>
					<div class="ratings-column">
						<span><b>TOP100</b> по выигрышу</span>

						<div>
							<?php
								$db = new PdoDb();
								$req = $db->prepare('SELECT t1.id, t1.login, t2.sum, t1.rating FROM `users` AS t1, (SELECT `user`, SUM(`win`) AS `sum` FROM `games` GROUP BY `user`) AS t2 WHERE t1.id = t2.user ORDER BY t2.sum DESC LIMIT 0, 10;');
								$req->execute();

								while (list($id, $login, $sum, $rating) = $req->fetch(PDO::FETCH_NUM))
								{
									$color = 'lightgray';

									if (startsWith($login, 'vk:'))
									{
										$color = '#547da9';
									}
									else if (startsWith($login, 'ok:'))
									{
										$color = '#f6a25a';
									}
									else if (startsWith($login, 'fb:'))
									{
										$color = '#738fc5';
									}

									?>
										<div style="display: inline-block; width: calc(100% - 20px);">
											<div style="width: 64px; height: 64px; float: left; border: 3px solid <?php echo $color; ?>; margin-right: 5px; background-image: url('<?php echo getUserThmbByLogin($login); ?>');">
											</div>
											<a href="/user.php?id=<?php echo $id; ?>" style="float: left; width: calc(100% - 82px);">
												<b><?php echo getUserNameByLogin($login); ?></b>
												<br>
												<i><?php echo $sum; ?></i>
											</a>
											<div style="margin-right: -5px;">
												<?php
													for ($j = 0; $j < 10 && $j < $rating; ++$j)
													{
														?>
															<div class="star"></div>
														<?php
													}

													for (; $j < 10; ++$j)
													{
														?>
															<div class="star-dark"></div>
														<?php
													}
												?>
											</div>
										</div>
									<?php
								}
							?>
						</div>
					</div>
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