<?php
	require_once('utils.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Активация аккаунта</title>
		<?php require_once('head.php'); ?>
		<style>
			body {
				background-color: white;
			}

			#modal-window-outer {
				height: 250px;
			}

			#modal-window {
				height: 250px;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div id="modal-window-outer">
			<div id="modal-window">
				<div id="modal-window-header">Активация</div>

				<form action="doactivation.php" method="POST" id="login-form">
					<table>
						<tr>
							<td>
								<input type="text" name="sms" maxlength="5" placeholder="Код из смс" />
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
								<button type="submit" value="Активировать" id="button-login" />Активировать</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>