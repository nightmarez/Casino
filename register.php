<?php
	require_once('utils.php');

	// if authorized
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

		if ($count >= 1)
		{
			header('Location: /');
			die();
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Регистрация</title>
		<?php require_once('head.php'); ?>
		<style>
			body {
				background-color: white;
			}

			#modal-window-outer {
				height: 450px;
				margin-top: -35px;
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
				<div id="modal-window-header">Создайте свой профиль — Играйте бесплатно в Keno Zero</div>

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

				<form action="doregister.php" method="POST" id="login-form">
					<table>
						<tr>
							<td id="login-input-outer">
								<input id="login-input" type="text" name="login" maxlength="11" placeholder="Номер телефона" />
							</td>
						</tr>
						<tr>
							<td>
								<input type="password" name="pass" maxlength="15" placeholder="Пароль (от 4-х символов)" />
							</td>
						</tr>
						<tr>
							<td>
								<input type="password" name="pass2" maxlength="15" placeholder="Повторить пароль" />
							</td>
						</tr>
						<tr>
							<td style="font-size: 12px; color: black;">
								<div id="recover-link" style="float: right;">
									Регистрируясь, я соглашаюсь с <a href="/rules.php">правилами</a>
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
							<td colspan="2" style="padding-top: 30px;">
								<button type="submit" style="margin-bottom: 30px;" value="Зарегистрироваться" id="button-login" />Зарегистрироваться</button>
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