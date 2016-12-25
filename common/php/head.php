<?php
	function genHead($title)
	{
		$title = htmlspecialchars($title);

		?>
			<meta charset="utf-8" />
			<meta name="keywords" content="<?php echo $title; ?>" />
			<meta name="Description" content="<?php echo $title; ?>">
			<meta name="robots" content="index, follow" />
			<link href="../../styles/reset.css" rel="stylesheet" text="text/css" />
			<link href="styles/main.css" rel="stylesheet" text="text/css" />
			<link href="../../styles/userselect.css" rel="stylesheet" text="text/css" />

			<link href="../../common/styles/addtohomescreen.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/footer.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/gamble.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/main.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/paytable.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/preloader.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/mobile-rotate.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/reels.css" rel="stylesheet" text="text/css" />
			<link href="../../common/styles/topmenu.css" rel="stylesheet" text="text/css" />

			<script src="../../scripts/jquery.js"></script>
			<script src="../../scripts/jquery.fullscreen.js"></script>
			<script src="../../scripts/underscore.js"></script>
			<script src="../../scripts/howler.js"></script>
			<script src="../../scripts/libgif.js"></script>
			<script src="../../scripts/lz-string.js"></script>
			<script src="../../scripts/localforage.js"></script>
			<script src="../../scripts/requestAnimationFrame.js"></script>
			<script src="../../scripts/pixi.js"></script>

			<script src="../../common/scripts/addtohomescreen.js"></script>
			<script src="../../common/scripts/utils.js"></script>
			<script src="../../common/scripts/sound.js"></script>
			<script src="../../common/scripts/mobile-rotate.js"></script>
			<script src="../../common/scripts/preloader.js"></script>
			<script src="../../common/scripts/scaling.js"></script>
			<script src="../../common/scripts/buttons.js"></script>
			<script src="../../common/scripts/gamble.js"></script>
			<script src="../../common/scripts/doubletap.js"></script>
			<script src="../../common/scripts/engine.js"></script>

			<script src="scripts/config.js"></script>

			<!--
			<link href='https://fonts.googleapis.com/css?family=Exo+2:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
			-->

			<meta name="apple-mobile-web-app-title" content="<?php echo $title; ?>" />
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
			<meta name="format-detection" content="telephone=no" />
			<meta name="format-detection" content="address=no">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
			<link rel="apple-touch-icon" href="imgs/icon.png" />
			
			<title><?php echo $title; ?></title>

			<script>
				$(document).ready(function() {
					
				});
			</script>
		<?php
	}
?>