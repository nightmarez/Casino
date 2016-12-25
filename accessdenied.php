<!DOCTYPE html>
<html>
	<head>
		<title>Нет доступа</title>
		<?php require_once('head.php'); ?>
		<style>
			body {
				background-color: white;
			}

			#modal-window {
				height: auto;
			}

			#login-footer {
			    margin-top: 80px;
			    margin-bottom: -37px;
			}
		</style>
	</head>
	<body>
		<div id="modal-window-outer">
			<div id="modal-window">
				<div id="modal-window-header">У вашего аккаунта нет доступа к этому разделу</div>

				<table style="border: none; margin-top: 100px; width: 100%;">
					<tr style="border: none;">
						<td style="border: none; width: 50%;">
							<form action="/logout.php" method="POST">
								<button style="margin: 0 auto; width: 250px; float: none; display: block;" type="submit" value="Войти" id="button-logout" />Выйти</button>
							</form>
						</td>
						<td style="border: none; width: 50%;">
							<form action="/index.php" method="POST">
								<button style="margin: 0 auto; width: 250px; float: none; display: block;" type="submit" value="Войти" id="button-login" />На главную</button>
							</form>
						</td>
					</tr>
				</table>

				<div id="login-footer">
					Ещё не зарегистрированы? <a href="/register.php">Присоединяйтесь!</a>
				</div>
			</div>
		</div>
	</body>
</html>