<?php
	require_once('utils.php');

	// if admin
	if (isset($_COOKIE['login']) && isset($_COOKIE['pass']))
	{
		$login = $_COOKIE['login'];
		$pass = $_COOKIE['pass'];
		$login = htmlspecialchars($login);
		$pass = htmlspecialchars($pass);

		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=1;');
		$req->bindParam(':login', $login);
		$req->bindParam(':pass', $pass);
		$req->execute();
		$count = $req->fetchColumn();

		if ($count >= 1)
		{
			header('Location: /admin/');
			die();
		}
	}

	if (isset($_COOKIE['login']) && isUserExists($_COOKIE['login']))
	{
		$login = $_COOKIE['login'];
		$pass = $_COOKIE['pass'];
		$login = htmlspecialchars($login);
		$pass = htmlspecialchars($pass);

		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=2;');
		$req->bindParam(':login', $login);
		$req->bindParam(':pass', $pass);
		$req->execute();
		$count = $req->fetchColumn();

		if ($count > 0)
		{
			header('Location: /');
			die();
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Авторизация</title>
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
		<?php /* require_once('header.php'); */ ?>

		<div id="modal-window-outer">
			<div id="modal-window">
				<div id="modal-window-header">Вход в игру Keno Zero</div>

				<ul id="menu-inline-social-auth">
					<li>
						<a href="/vk-auth.php" id="vk-login-button" class="social-login-button">
							<span class="pill">
								<strong>ВКонтакте</strong>
							</span>
						</a>
					</li>
					<li>
						<a href="/fb-auth.php" id="facebook-login-button" class="social-login-button">
							<span class="pill">
								<strong>Facebook</strong>
							</span>
						</a>
					</li>
					<li>
						<a href="/ok-auth.php" id="odnoklassniki-login-button" class="social-login-button">
							<span class="pill">
								<strong>Одноклассники</strong>
							</span>
						</a>
					</li>
				</ul>

				<form action="dologin.php" method="POST" id="login-form">
					<table>
						<tr>
							<td id="login-input-outer">
								<input id="login-input" type="text" name="login" placeholder="Номер телефона" />
							</td>
						</tr>
						<tr>
							<td>
								<input type="password" name="pass" placeholder="Пароль" />
								<?php
									$redirect = '';

									if (isset($_GET['redirect']))
									{
										$redirect = htmlspecialchars($_GET['redirect']);
										?>
											<input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
										<?php
									}
								?>	
							</td>
						</tr>
						<tr>
							<td style="font-size: 12px; color: black;">
								<div style="float: left; margin-top: -4px;">
									<input type="checkbox" style="float: left;">
									<span style="margin-top: 3px; display: block; float: left;">Запомнить</span>
								</div>
								<div id="recover-link" style="float: right;">
									<a href="/recover.php">Забыли пароль?</a>
								</div>
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
							<td>
								<button style="margin-bottom: -30px;" type="submit" value="Войти" id="button-login" />Войти</button>
							</td>
						</tr>
					</table>
				<form>

				<div id="login-footer">
					Ещё не зарегистрированы? <a href="/register.php">Присоединяйтесь!</a>
				</div>
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