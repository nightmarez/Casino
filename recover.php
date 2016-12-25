<?php
	require_once('utils.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Забыли пароль?</title>
		<?php require_once('head.php'); ?>
		<style>
			body {
				background-color: white;
			}

			#modal-window-outer {
				height: 250px;
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
				<div id="modal-window-header">Забыли пароль?</div>

				<form action="dorecover.php" method="POST" id="login-form">
					<table>
						<tr>
							<td id="login-input-outer">
								<input id="login-input" type="text" name="login" maxlength="11" placeholder="Номер телефона" />
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
								<button type="submit" style="margin-bottom: 30px;" value="Восстановить" id="button-login" />Восстановить</button>
							</td>
						</tr>
					</table>
				<form>
			</div>
		</div>

		<script>
			$(document).ready(function() {
				$('#login-input').focus(function() {
					$('#login-input-outer').addClass('login-input-outer-focus');
				});

				$('#login-input').focusout(function() {
					$('#login-input-outer').removeClass('login-input-outer-focus');
				});
			});
		</script>
	</body>
</html>