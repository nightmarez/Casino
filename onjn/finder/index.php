<?php
	//require_once('../utils.php');

	//if (!isUserHasAccess(14 /* File Manager */)) { 
	//	header('Location: ../403.php');
	//	die();
	//}
?>

<!DOCTYPE html>
<html style="height: 100%;">
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.1.x source version with PHP connector</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script src="js/i18n/elfinder.ru.js"></script>

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			$(document).ready(function() {
				$('#elfinder').elfinder({
					url : 'php/connector.minimal.php'  // connector URL (REQUIRED)
					// , lang: 'ru'                    // language (OPTIONAL)
				});
			});
		</script>

		<style>
			html {
				overflow: hidden !important;
			}

			body {
				overflow: hidden !important;
			}

			#elfinder {
				height: calc(100% - 50px) !important;
				width: calc(100% - 10px) !important;
			}

			#elfinder > .elfinder-workzone > .elfinder-navbar {
				height: 100% !important;
			}

			#elfinder > .elfinder-workzone > .elfinder-cwd-wrapper {
				height: 100% !important;
			}
		</style>
	</head>
	<body style="height: 100%;">

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
