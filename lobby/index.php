<?php
	require_once('../utils.php');

	if (isset($_COOKIE['login']) && isset($_COOKIE['pass']))
	{
		$login = htmlspecialchars($_COOKIE['login']);
		$pass = htmlspecialchars($_COOKIE['pass']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT `lobbyaccess` FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=2;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();
		$ok = false;

		while (list($lobbyaccess) = $req->fetch(PDO::FETCH_NUM))
		{
			if ($lobbyaccess == 1)
			{
				$ok = true;
				break;
			}
		}

		if (!$ok && !lobbyZoneAccess())
		{
			header('Location: /accessdenied.php');
			die();
		}
	}

	if (!lobbyZoneAccess())
	{
		header('Location: /login.php?redirect=lobby');
		die();
	}
	else
	{
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="keywords" content="Casino" />
		<meta name="Description" content="Casino">
		<meta name="robots" content="index, follow" />
		<link href="../styles/reset.css" rel="stylesheet" text="text/css" />
		<link href="styles/main.css" rel="stylesheet" text="text/css" />
		<link href="../styles/userselect.css" rel="stylesheet" text="text/css" />

		<link href="../common/styles/footer.css" rel="stylesheet" text="text/css" />
		<link href="../common/styles/main.css" rel="stylesheet" text="text/css" />
		<link href="../common/styles/preloader.css" rel="stylesheet" text="text/css" />
		<link href="../common/styles/mobile-rotate.css" rel="stylesheet" text="text/css" />
		<link href="../common/styles/addtohomescreen.css" rel="stylesheet" text="text/css" />

		<script src="../scripts/jquery.js"></script>
		<script src="../scripts/jquery.fullscreen.js"></script>
		<script src="../scripts/underscore.js"></script>
		<script src="../scripts/howler.js"></script>
		<script src="../scripts/libgif.js"></script>
		<script src="../scripts/lz-string.js"></script>
		<script src="../scripts/localforage.js"></script>
		<script src="../scripts/requestAnimationFrame.js"></script>
		<script src="../scripts/pixi.js"></script>
		<script src="../scripts/jquery.ba-hashchange.js"></script>
		<script src="../common/scripts/doubletap.js"></script>
		<script src="../common/scripts/utils.js"></script>
		<script src="../common/scripts/addtohomescreen.js"></script>
		<script src="../common/scripts/engine.js"></script>
		<script src="../common/scripts/scaling.js"></script>
		<script src="../common/scripts/mobile-rotate.js"></script>

		<!--
		<link href='https://fonts.googleapis.com/css?family=Exo+2:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
		-->

		<meta name="apple-mobile-web-app-title" content="Casino" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
		<link rel="apple-touch-icon" href="icon.png" />

		<title>Lobby</title>
	</head>
	<body>
		<?php require_once('../common/php/mobile-rotate.php'); ?>

		<div id="game" style="display: none;">
			<?php require_once('../common/php/preloader.php'); ?>

			<div id="body-repeat">
				<div id="body">
					<?php require_once('../common/php/topmenu.php'); ?>

					<div id="reels-outer">
					</div>

					<div id="reel-fore-outer">
						<canvas id="reel-fore"></canvas>
					</div>
			
					<?php require_once('../common/php/gamble.php'); ?>
					<?php require_once('../common/php/footer.php'); ?>
				</div>
			</div>
		</div>

		<div id="lobby" style="display: none;">
			<div id="top-gradient"></div>

			<script>
				$(document).ready(function() {
					$(document).nodoubletapzoom();
					
					$('.page').find('a').click(function(e) {
						e.stopPropagation();

						if ($(this).attr('href') == '/lobby/') {
							return false;
						}
					});

					var tryLoadGame = function() {
						var availableGames = [
							<?php
								require_once('../utils.php');

								$db = new PdoDb();
								$req = $db->prepare('SELECT * FROM `programs`;');
								$req->execute();
											
								while (list($id, $title, $thmb, $path, $categories) = $req->fetch(PDO::FETCH_NUM))
								{
									if (strlen($path) > 0)
									{
										echo '"' . $path . '", ';
									}
								}
							?>
						];

						var game = location.href.split('#!')[1];
						var isAvailable = false;

						_.each(availableGames, function(av) {
							if (av == game) {
								isAvailable = true;
							}
						})

						if (!isAvailable) {
							$('#lobby').show();

							if (isMobile()) {
								tryShowRotationBlock();
							}
						} else {
							loadGame(game);
						}
					};

					tryLoadGame();

					$(window).hashchange(function() {
						tryLoadGame();
					});
				});
			</script>

			<div id="main-content">
				<?php
					require_once('../utils.php');

					$db = new PdoDb();
					$req = $db->prepare('SELECT * FROM `categories`;');
					$req->execute();

					$page = 1;
					$countPerFrame = 12;
					$count = 0;
					$frame = 0;
							
					while (list($catid, $cattitle) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<div class="page" id="page-<?php echo $page; ?>" <?php echo $page > 1 ? 'style="display: none;"': ''; ?>>
								<?php
									$db1 = new PdoDb();
									$req1 = $db->prepare('SELECT * FROM `programs`;');
									$req1->execute();
												
									while (list($id, $title, $thmb, $path, $categories, $presets, $preset, $access) = $req1->fetch(PDO::FETCH_NUM))
									{
										if (in_array($catid, explode(',', $categories))) {
											if ($count == 0) {
												?>
													<div <?php echo $frame > 0 ? 'class="hidden"' : ''; ?>>
												<?php
											}

											?>
												<a href="#!<?php echo $path; ?>" class="game-icon" data-access="<?php echo (strlen($path) == 0 || $access == 0 ? 'false' : 'true'); ?>">
													<img src="icons/<?php echo htmlspecialchars($thmb); ?>" <?php if (strlen($path) == 0 || $access == 0) { echo 'class="grayscale"'; }?>>
													<span><?php echo htmlspecialchars($title); ?></span>
													<?php
														if ($access == 0) {
															?>
																<div class="game-icon-lock"></div>
															<?php
														}
													?>
												</a>
											<?php

											++$count;

											if ($count == $countPerFrame) {
												$count = 0;
												++$frame;

												?>
													</div>
												<?php
											}
										}
									}

									if ($count > 0) {
										$count = 0;
										?>
											</div>
										<?php
									}
								?>
							</div>
						<?php

						$frame = 0;
						++$page;
					}
				?>
			</div>

			<div id="main-menu-outer">
				<ul id="main-menu">
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT * FROM `categories`;');
						$req->execute();
						$page = 1;
								
						while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
						{
							?>
								<li>
									<a href="#" id="page-link-<?php echo $page; ?>"><?php echo htmlspecialchars($title); ?></a>
								</li>
							<?php

							++$page;
						}
					?>
				</ul>

				<footer id="main-footer">
					<div id="footer-gradient"></div>

					<div id="footer-credit">
						<span>Credit</span>
						<h2>9.999,00 EUR</h2>
						<h1>999900</h1>
					</div>

					<div id="footer-denominator">
						<span>1 Credit =</span>
						<span>0.01 EUR</span>
					</div>

					<div id="select-title">Select A Game</div>
				</footer>
			</div>

			<script>
				$(document).ready(function() {
					$('a.game-icon').click(function(e) {
						if ($(this).attr('data-access') == 'false') {
							e.preventDefault();
							return false;
						}
					});

					$('ul#main-menu > li > a').click(function() {
						$(this).parent().parent().find('a').css('border-top', '1px solid white').css('margin-top', '0');
						$(this).css('border-top', 'none').css('margin-top', '1px');

						var page = $(this).attr('id').split('-')[2];
						$('.page').css('display', 'none');
						$('#page-' + page).css('display', 'block');

						var gradientColor = $(this).css('background-image').split('radial-gradient(')[1].split(')')[0] + ')';
						$('#top-gradient').css('background-image', 'linear-gradient(' + gradientColor + ', black)');

						return false;
					});
				});
			</script>

			<script>
				// fill all field
				_.each($('.page'), function(page) {
					page = $(page);
					_.each(page.find('div'), function(subpage) {
						subpage = $(subpage);
						var len = subpage.find('a').length;

						if (len) {
							for (var i = len; i <  12; ++i) {
								var a = $(document.createElement('a'))
									.attr('href', '#')
									.addClass('game-icon');
								subpage.append(a);
							}
						}
					});
				});

				// page events
				$('.page > div').click(function(e) {
					if (e.pageX < $(window).width() / 2) {
						$(this).addClass('hidden');

						if ($(this).prev().length) {
							$(this).prev().removeClass('hidden');
						} else {
							$(this).parent().children().last().removeClass('hidden');
						}
					} else {
						$(this).addClass('hidden');

						if ($(this).next().length) {
							$(this).next().removeClass('hidden');
						} else {
							$(this).parent().children().first().removeClass('hidden');
						}
					}
				});

				// show "add to home button"
				$(document).ready(function() {
					if (isiOS() || isAndroid()) {
						if (location.href.indexOf('#!') == -1) {
							location.href = '#!';

							var addtohome = addToHomescreen({
							    skipFirstVisit: false,	    // show at first access
							    displayPace: 0,             // do not obey the display pace
							    privateModeOverride: true,	// show the message in private mode
							    maxDisplayCount: 0,         // do not obey the max display count
							    autostart: false
							});

							addtohome.show();
						}
					}
				});

				// show balance
				$(document).ready(function() {
					showUserBalance();
				});
			</script>
		</div>
	</body>
</html>

<?php
	}
?>