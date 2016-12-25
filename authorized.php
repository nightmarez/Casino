<?php
	require_once('utils.php');

	if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
	{
		header('Location: /login.php');
		die();
	}

	$login = htmlspecialchars($_COOKIE['login']);
	$pass = htmlspecialchars($_COOKIE['pass']);

	$db = new PdoDb();
	$req = $db->prepare('SELECT `level`, `lobbyaccess` FROM `users` WHERE `login`=:login AND `pass`=:pass');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->execute();
	$count = $req->fetchColumn();
	$lobbyaccess = false;
	$level = false;

	while (list($l, $a) = $req->fetch(PDO::FETCH_NUM))
	{
		$level = intval($l);
		$lobbyaccess = intval($a);
		break;
	}

	if ($count == 0) {
		header('Location: /login.php');
		die();
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

			#modal-window ul {
				margin-top: 20px;
				text-align: center;
			}

			#modal-window ul > li {
				float: none;
				display: block;
				border: none;
			}

			#modal-window ul > li > a:link, #modal-window ul > li > a:visited, #modal-window ul > li > a:hover {
				font-size: 18px;
				color: blue;
				margin-right: 5px;
				margin-left: 5px;
			}

			#modal-window ul > li > a:hover {
				text-decoration: none;
			}
		</style>
	</head>
	<body>
		<div id="modal-window-outer">
			<div id="modal-window">
				<div id="modal-window-header">Вы уже авторизованы</div>

				<span style="color: black; font-size: 20px; display: block; width: 100%; text-align: center;">Логин: <?php echo $login; ?></span>

				<?php
					if ($level == 1) {
						?>
							<ul>
								<li>
									<a href="/admin/">Административная панель</a>
									<a href="/lobby/">Лобби</a>
									<a href="/">Кено</a>
								</li>
							</ul>
						<?php
					} else if ($level == 2) {
						?>
							<ul>
								<li>
									<?php if ($lobbyaccess == 1) { ?>
									<a href="/lobby/">Лобби</a>
									<?php } ?>
									<a href="/">Кено</a>
								</li>
							</ul>
						<?php
					} else if ($level == 3) {
						?>
							<ul>
								<li>
									<a href="/dealer/">Панель дилера</a>
									<a href="/lobby/">Лобби</a>
									<a href="/">Кено</a>
								</li>
							</ul>
						<?php
					}
				?>

				<form action="logout2.php" method="GET" id="login-form">
					<table>
						<tr>
							<td>
								<button style="margin-bottom: 30px; margin-top: 50px;" type="submit" value="Выйти" id="button-login" />Выйти</button>
							</td>
						</tr>
					</table>
				</form>
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