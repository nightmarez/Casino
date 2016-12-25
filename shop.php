<?php require_once('userzone.php'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Магазин</title>
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

			#tigr {
				width: 300px;
				min-height: 349px;
				background-image: url('/imgs/tigr.png');
				background-repeat: no-repeat;
				display: inline-block;
			}

			#column1 {
				width: calc((100% - 370px) / 2);
				min-height: 349px;
				display: inline-block;
				vertical-align: top;
				background-color: #f2f2f2;
    			border: 1px solid #d3d3d3;
    			padding: 10px;
    			position: relative;
			}

			#column1 > div:first-child {
				width: 100%;
				border-bottom: 1px solid darkblue;
				padding-bottom: 5px;
				margin-bottom: 10px;
				color: darkblue;
				font-weight: bold;
			}

			#column2 {
				width: calc((100% - 370px) / 2);
				min-height: 349px;
				display: inline-block;
				vertical-align: top;
				background-color: #FFFBD5;
    			border: 1px solid #DFCD85;
    			padding: 10px;
    			position: relative;
			}

			#column2 > div:first-child {
				width: 100%;
				border-bottom: 1px solid darkblue;
				padding-bottom: 5px;
				margin-bottom: 10px;
				color: darkblue;
				font-weight: bold;
			}

			#pay-button {
				width: 90%;
				box-shadow: -1px -1px 1px #b75b0d inset,1px 1px 2px rgba(0,0,0,0.3);
				background: linear-gradient(#f48221, #e77310);
				position: absolute;
				bottom: 10px;
			}

			#pay-button:hover {
				background: linear-gradient(#f69b26, #ed8a13);
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="body">
			<div id="body-center-block">
				<div style="color: darkblue; font-weight: bold; padding-bottom: 20px;">
					Keno интернет-магазин - Выбор за Вами!
				</div>

				<div style="overflow: hidden; color: black;">
					<div id="column1">
						<div>Реальные деньги</div>
						<div>
							Играйте на деньги в наши навыковые (казуальные) игры и наслаждайтесь реальными выигрышами! Нажмите на расположенную ниже кнопку и пополните свой счет прямо сейчас!
						</div>

						<a href="/pay.php" id="pay-button" class="social-login-button">
							<span class="pill">
								<strong>Внести депозит!</strong>
							</span>
						</a>
					</div>

					<div id="column2">
					<div>Виртуальные деньги</div>
						<div>
							Играйте на виртуальные деньги на valvemovie.com. Их можно использовать для ставок во всех играх сайта.
							<br><br>
							Являясь виртуальной валютой, они не имеют какой-либо коммерческой ценности. Выплатить виртуальные деньги реальными деньгами нельзя.
						</div>

						<a href="/pay.php" id="pay-button" class="social-login-button">
							<span class="pill">
								<strong>Внести депозит!</strong>
							</span>
						</a>
					</div>

					<div id="tigr">
						
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