<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<title>Административная панель</title>
			<meta name="keywords" content="keno" />
			<meta name="Description" content="keno">
			<meta name="robots" content="index, follow" />
			<link href="../styles/reset.css" rel="stylesheet" text="text/css" />
			<link href="../styles/main.css" rel="stylesheet" text="text/css" />
			<link href="styles/main.css" rel="stylesheet" text="text/css" />
			<link href="styles/alertify.css" rel="stylesheet" text="text/css" />
			<script src="../scripts/jquery.js"></script>
			<script src="../scripts/jquery-ui.js"></script>
			<script src="scripts/bootstrap.js"></script>
			<script src="scripts/alertify.js"></script>
			<script src="../scripts/underscore.js"></script>
			<script src="../scripts/search.js"></script>
			<script src="../common/scripts/utils.js"></script>
			<link href='https://fonts.googleapis.com/css?family=Exo+2:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

			<style>
				table {
					border: none;
					border-collapse: separate;
				}

				td {
					border: none;
					border-collapse: separate;
					padding: 0;
				}

				th {
					border: none;
					border-collapse: separate;
					padding: 0;
				}

				a:link, a:hover, a:visited {
					color: blue;
				}

				.btn {
					color: white !important;
				}

				.navbar {
					background-image: none !important;
   					background-color: black !important;
				}

				a:link.navbar-brand, a:visited.navbar-brand {
					color: #eee !important;
				}

				a:hover.navbar-brand {
					color: #fff !important;
				}

				.nav a:link, .nav a:visited {
					color: #aaa !important;
				}

				.nav a:hover {
					color: #ccc !important;
				}

				ul.nav {
					width: calc(100% - 50px);
				}

				a:link.dropdown-toggle, a:visited.dropdown-toggle {
					color: #aaa !important;
					background: none !important;
					background-color: black;
				}

				a:hover.dropdown-toggle {
					color: #ccc !important;
					background: none !important;
					background-color: black;
				}

				ul.dropdown-menu {
					background-color: white;
				}

				ul.dropdown-menu > li > a:link, ul.dropdown-menu > li > a:visited {
					color: #555 !important;
				}

				ul.dropdown-menu > li > a:hover {
					background: none !important;
					background-color: #08c !important;
					color: white !important;
				}

				#search-area {
					height: 30px;
					margin-top: 10px;
					padding: 0 10px 0 10px;
					border-radius: 15px;
					border: none;
					background-color: gray;
					color: #ddd;
					outline: none;
				}

				#search-area::-webkit-input-placeholder { color: #ddd; }
				#search-area::-moz-placeholder          { color: #ddd; }
				#search-area:-moz-placeholder           { color: #ddd; }
				#search-area:-ms-input-placeholder      { color: #ddd; }

				#lang-button:before {
					background-image: url('../imgs/lang-icon.png');
				    background-repeat: no-repeat;
				    display: block;
				    width: 16px;
				    height: 16px;
				    content: ' ';
				    float: left;
				    margin-right: 5px;
				    -moz-filter: grayscale(100%);
					-o-filter: grayscale(100%);
					-ms-filter: grayscale(100%);
					-webkit-filter: grayscale(100%);
				    filter: grayscale(100%);
				}
			</style>

			<link href="styles/bootstrap.min.css" rel="stylesheet" text="text/css" />
			<link href="styles/bootstrap-theme.min.css" rel="stylesheet" text="text/css" />

			<script src="../scripts/alertify.js"></script>
			<link rel="stylesheet" href="../styles/alertify.core.css" />
			<link rel="stylesheet" href="../styles/alertify.default.css" />
			<link rel="stylesheet" href="../styles/search.css" />
		</head>
		<body>
			<script>
				window.disableDefaultSearch = false;

				$(document).ready(function() {
					if (!window.disableDefaultSearch) {
						$('#search-area').keyup(function(e) {
							if (e.which == 13) {
								var searchValue = $(this).val();

								if (searchValue.length) {
									location.href = '/admin/search.php?q=' + searchValue;
								}
							}
						});
					}
				});
			</script>

			<?php
				require_once('../utils.php');
			?>

			<nav class="navbar navbar-default">
			  <div class="container-fluid">
			    <div class="navbar-header">
			      <a class="navbar-brand" href="/admin/">Keno</a>
			    </div>

			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav">
			        <!--
			        	<li class="active"><a href="/admin/">Главная <span class="sr-only">(current)</span></a></li>
			        -->
			        <li><a href="/admin/"><?php echo getL10n('Admin-TopMenu-Main'); ?></a></li>
			        <li><a href="/admin/monitor.php"><?php echo getL10n('Admin-TopMenu-Monitoring'); ?></a></li>
			        <li><a href="/admin/users.php"><?php echo getL10n('Admin-TopMenu-Bills'); ?></a></li>
			        <li><a href="/admin/dealers.php"><?php echo getL10n('Admin-TopMenu-Dealers'); ?></a></li>
			        <li><a href="/admin/programs.php"><?php echo getL10n('Admin-TopMenu-Programs'); ?></a></li>

			        <!--
			        <li><a href="/admin/programs.php"><?php echo getL10n('Admin-TopMenu-Games'); ?></a></li>
					-->

			        <!--
			        <li><a href="/admin/users.php">Счета</a></li>
			        -->

			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo getL10n('Admin-TopMenu-Reports'); ?> <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			          	<!--
			          	<li><a href="/admin/stat.php">Статистика</a></li>
			            <li><a href="#">Доходность</a></li>
			            <li><a href="#">Анализ</a></li>
			            <li><a href="#">Точки выплаты</a></li>
			            -->
			            <li><a href="/admin/games.php">Тиражи</a></li>
			            <li><a href="/admin/bets.php">Ставки</a></li>
			            <li><a href="/admin/payments.php">Платежи</a></li>
			          </ul>
			        </li>
			        
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo getL10n('Admin-TopMenu-Other'); ?> <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			            <li><a href="/admin/addmoney.php">Пополнение баланса</a></li>
			            <li><a href="/admin/settings.php">Настройки</a></li>
			            <li><a href="/admin/calcgifslen.php">Расчёт анимированных gif файлов</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="/admin/l10n.php">Локализация — Языки</a></li>
			            <li><a href="/admin/l10nconsts.php">Локализация — Константы</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="/finder/">Файловый менеджер</a></li>
			          </ul>
			        </li>

			        <li class="dropdown" style="float: right;">
			        	<a href="#" id="lang-button" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			        		<?php
								$lang = 1;

						    	if (isset($_COOKIE['lang']))
						    	{
						    		$lang = intval($_COOKIE['lang']);
						    	}

						    	$db = new PdoDb();
								$req = $db->prepare('SELECT `title` FROM `langs` WHERE `id`=:id;');
								$req->bindParam(':id', $lang, PDO::PARAM_INT);
								$req->execute();

						    	while (list($langtitle) = $req->fetch(PDO::FETCH_NUM))
						    	{
						    		echo htmlspecialchars($langtitle);
						    		break;
						    	}
							?>
			        		<span class="caret"></span>
			        	</a>
			        	<ul class="dropdown-menu" style="left: auto; right: 0;">
				        	<?php
				        		$db = new PdoDb();
								$req = $db->prepare('SELECT `id`, `title` FROM `langs`;');
								$req->execute();

				        		while (list($langid, $langtitle) = $req->fetch(PDO::FETCH_NUM))
				        		{
				        			?>
				        				<li><a href="/" class="lang-item" onclick="return false;" id="lang-<?php echo $langid; ?>"><?php echo htmlspecialchars($langtitle); ?></a></li>
				        			<?php
				        		}
				        	?>
				        </ul>
			        </li>

			        <li style="float: right;"><a href="/logout.php"><?php echo getL10n('Admin-TopMenu-Exit'); ?></a></li>

			        <li style="float: right;">
			        	<input type="text" placeholder="<?php echo getL10n('Admin-TopMenu-Search'); ?>..." id="search-area">
			        </li>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>

			<script>
				$(document).ready(function() {
					$('.lang-item').click(function() {
						document.cookie = 'lang=' + $(this).attr('id').split('-')[1];
						location.reload();
						return false;
					});
				});
			</script>

			<div id="admin-content">