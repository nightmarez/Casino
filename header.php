<header>
	<h1><!-- Кено --></h1>

	<?php
		require_once('utils.php');
		$authorization = getAuthorization();
	?>

	<div style="float: left; margin-left: 20px;">
		<div id="top-circulation"><b>Тираж:&nbsp;</b></div>
		<div id="top-user">
			Пользователь:&nbsp;
			<?php
				echo getUserNameByLogin(getUserLoginById($authorization));
			?>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('#lang-icon').click(function() {
				$('#lang-menu')
					.css('display', 'block')
					.css('top', $(this).position().top + $(this).height() + 5 + 'px')
					.css('left', $(this).position().left + 'px');
			});

			$('.lang-item').click(function() {
				document.cookie = 'lang=' + $(this).attr('id').split('-')[1];
				location.reload();
				return false;
			});
		});
	</script>

	<div style="float: right; margin-right: 20px;">
		<div id="top-balance">Баланс: </div>

		<ul id="top-menu">
			<li>
				<ul id="lang-menu">
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title` FROM `langs`;');
						$req->execute();

						while (list($langid, $langtitle) = $req->fetch(PDO::FETCH_NUM))
						{
							?>
								<li>
									<a href="#" class="lang-item" id="lang-<?php echo $langid; ?>"><?php echo htmlspecialchars($langtitle); ?></a>
								</li>
							<?php
						}
					?>
				</ul>
				<a href="#" id="lang-icon" onclick="return false;">
					<?php
						$lang = 1;

				    	if (isset($_COOKIE['lang']))
				    	{
				    		$lang = intval($_COOKIE['lang']);
				    	}

				    	$db = new PdoDb();
				    	$req = $db->prepare('SELECT `title` FROM `langs` WHERE `id`=:lang;');
				    	$req->bindParam(':lang', $lang, PDO::PARAM_INT);
				    	$req->execute();

				    	while (list($langtitle) = $req->fetch(PDO::FETCH_NUM))
				    	{
				    		echo htmlspecialchars($langtitle);
				    		break;
				    	}
					?>
				</a>
			</li>
			<?php
				if ($authorization === false)
				{
			?>
				<li>
					<a href="/login.php"><?php echo getL10n('TopMenu-Button-Login'); ?></a>
				</li>
				<li>
					<a href="/register.php"><?php echo getL10n('TopMenu-Button-Register'); ?></a>
				</li>
			<?php
				}
				else
				{
			?>
				<?php if (isChatEnabled()) { ?>
					<li>
						<a href="/" id="chat-button"><?php echo getL10n('TopMenu-Button-Chat'); ?></a>
					</li>
				<?php } ?>

				<?php
					$uri = explode('?', $_SERVER['REQUEST_URI'])[0];

					if ($uri == '/')
					{
				?>
					<li>
						<a href="/logout.php"><?php echo getL10n('TopMenu-Button-Exit'); ?></a>
					</li>
				<?php
					}
				?>
				<li>
					<a href="/rules.php"><?php echo getL10n('TopMenu-Button-Help'); ?></a>
				</li>
				<li>
					<a href="/shop.php"><?php echo getL10n('TopMenu-Button-Shop'); ?></a>
				</li>
				<li>
					<a href="/bill.php"><?php echo getL10n('TopMenu-Button-Bill'); ?></a>
				</li>
				<li>
					<a href="/drawings.php"><?php echo getL10n('TopMenu-Button-Games'); ?></a>
				</li>
				<li>
					<a href="/ratings.php"><?php echo getL10n('TopMenu-Button-Ratings'); ?></a>
				</li>
				<li>
					<a href="#" id="button-fullscreen" style="display: none;"><?php echo getL10n('TopMenu-Button-Fullscreen'); ?></a>
					<a href="#" id="button-exitfullscreen" style="display: none;"><?php echo getL10n('TopMenu-Button-ExitFullscreen'); ?></a>
				</li>
			<?php
				}
			?>

			<?php
				$uri = explode('?', $_SERVER['REQUEST_URI'])[0];

				if ($uri == '/login.php' || $uri == '/register.php' || $uri == '/rules.php' || $uri == '/recover.php' || $uri == '/activation.php' ||
					$uri == '/ratings.php' || $uri == '/drawings.php' || $uri == '/bill.php' || $uri == '/shop.php')
				{
			?>
				<li>
					<a href="/">Назад</a>
				</li>
			<?php
				}
			?>
		</ul>
	</div>
</header>