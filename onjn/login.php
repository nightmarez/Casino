<?php
	require_once('utils.php');

	if (!isUserUnregistered()) {
		header('Location: index.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Authorization</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<div id="modal-window-outer">
			<div id="modal-window">
				<form action="dologin.php" method="POST" id="login-form">
					<table>
						<tbody>
							<tr>
								<td id="login-input-outer" class="">
									<input id="login-input" type="text" name="login" placeholder="Login">
								</td>
							</tr>
							<tr>
								<td>
									<input type="password" name="pass" placeholder="Password">
								</td>
							</tr>
							<tr>
								<td style="font-size: 12px; color: black;">
									<div style="float: left; margin-top: -4px;">
										<input type="checkbox" name="remember" style="float: left;">
										<span style="margin-top: 3px; display: block; float: left;">Remember</span>
									</div>
									<div id="recover-link" style="float: right;">
										<a href="recover.php">Forget password?</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<button style="margin-bottom: -30px;" type="submit" value="Войти" id="button-login">Login</button>
								</td>
							</tr>
						</tbody>
					</table>
				
					<!--
						<div id="login-footer">
							Ещё не зарегистрированы? <a href="/register.php">Присоединяйтесь!</a>
						</div>
					-->
				</form>
			</div>
		</div>
	</body>
</html>