<?php
	require_once('utils.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Установка нового пароля</title>
		<?php require_once('head.php'); ?>
		<style>
			body {
				background-color: white;
			}

			#modal-window-outer {
				height: 350px;
			}

			#modal-window {
				height: auto;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="modal-window-outer">
			<div id="modal-window">
				<div id="modal-window-header">Установка нового пароля</div>

				<form action="dorecoversetpass.php" method="POST" id="login-form">
					<table>
						<tr>
							<td id="login-input-outer">
								<input id="login-input" type="text" name="sms" maxlength="11" placeholder="Код из смс" />
							</td>
						</tr>
						<tr>
							<td>
								<input type="password" name="pass" maxlength="15" placeholder="Новый пароль (от 4-х символов)" />
							</td>
						</tr>
						<tr>
							<td>
								<input type="password" name="pass2" maxlength="15" placeholder="Повторить новый пароль" />
							</td>
						</tr>
						<?php
							if (isset($_GET['error']))
							{
								?>
								<tr>
									<td colspan="2">
										<span style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></span>
									</td>
								</tr>
								<?php
							}
						?>
						<tr>
							<td colspan="2" style="padding-top: 30px;">
								<button type="submit" style="margin-bottom: 30px;" value="Восстановить" id="button-login" />Сохранить</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>