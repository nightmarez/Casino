<?php require_once('userzone.php'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Тиражи</title>
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

			#body-center-block > div {
				margin-bottom: 10px;
				padding: 10px;
				background-color: #eee;
				color: black;
			}

			#body-center-block > div > i {
				width: calc(100% - 10px);
				background-color: #eeffee;
				display: inline-block;
				padding: 5px;
				margin-top: 8px;
				margin-bottom: 8px;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="body">
			<div id="body-center-block">
				<?php
					$db = new Db();
					$q = 'SELECT t1.id, t1.user, t1.balls, t1.win, t2.login FROM `games` AS t1, `users` AS t2 WHERE t1.user = t2.id AND LENGTH(t1.balls) > 0 ORDER BY t1.id DESC LIMIT 0, 10;';
					$r = $db->query($q);

					while (list($id, $user, $balls, $win, $login) = mysql_fetch_row($r))
					{
						?>
							<div>
								Пользователь:&nbsp;<b><?php echo $login; ?></b>
								<i>
									Тираж: <?php echo $id; ?>, шары:&nbsp;<?php echo $balls; ?>
								</i>
								Выигрыш:&nbsp;<?php echo $win; ?>
							</div>
						<?php
					}

					$db->close();
				?>
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