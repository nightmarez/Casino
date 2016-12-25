<?php
	require_once('utils.php');
?>

<header>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header" style="float: left;">
				<a class="navbar-brand" href="index.php">Dealer</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="float: left; width: calc(100% - 80px);">
				<ul class="nav navbar-nav">
					<!--================================================================-->

					<li><a href="index.php">Main</a></li>

					<?php if (isUserHasAccess(1 /* All Users */) && isUserHasAccess(2 /* Self Users */) && isUserHasAccess(3 /* Manage Users */) && isUserHasAccess(4 /* Deleted Users */)) { ?>
					<li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Users<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<?php if (isUserHasAccess(2 /* Self Users */)) { ?>
				        	<li><a href="users.php?self=true">Self Users</a></li>
				        	<?php } ?>

				        	<?php if (isUserHasAccess(1 /* All Users */)) { ?>
				        	<li><a href="users.php">All Users</a></li>
				        	<?php } ?>

				        	<?php if (isUserHasAccess(3 /* Add Users */)) { ?>
				        	<li><a href="adduser.php">Add User</a></li>
				        	<?php } ?>

				        	<?php if (isUserHasAccess(4 /* Delete Users */)) { ?>
				        	<li><a href="deletedusers.php">Deleted Users</a></li>
				        	<?php } ?>
				    	</ul>
					</li>
					<?php } ?>

					<?php if (isUserHasAccess(5 /* User Types */) && isUserHasAccess(6 /* User Access */) && isUserHasAccess(7 /* Areas */)) { ?>
					<li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Access<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<?php if (isUserHasAccess(5 /* User Types */)) { ?>
				        	<li><a href="usertypes.php">User Types</a></li>
				        	<?php } ?>

				        	<?php if (isUserHasAccess(6 /* User Access */)) { ?>
				        	<li><a href="usersaccess.php">Users Access</a></li>
				        	<?php } ?>

				        	<?php if (isUserHasAccess(7 /* Areas */)) { ?>
				        	<li><a href="areas.php">Areas</a></li>
				        	<?php } ?>
				    	</ul>
					</li>
					<?php } ?>

					<?php if (isUserHasAccess(10 /* Rooms */)) { ?>
					<li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rooms<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<li><a href="rooms.php">All Rooms</a></li>
				        	<li><a href="rooms.php?self=true">Self Rooms</a></li>
				        	<li><a href="deletedrooms.php">Deleted Rooms</a></li>
				    	</ul>
					</li>
					<?php } ?>

					<?php if (isUserHasAccess(15 /* Products */)) { ?>
					<li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Products<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<li><a href="storage.php">Storage</a></li>
				        	<li><a href="market.php">Market</a></li>
				    	</ul>
					</li>
					<?php } ?>

					<?php if (isUserHasAccess(15 /* Clients */)) { ?>
					<li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Clients<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<li><a href="clients.php">Clients Accounting</a></li>
				        	<li><a href="clients.php?vip=true">VIP Clients</a></li>
				        	<li><a href="clients.php?banned=true">Banned Clients</a></li>
				        	<li><a href="cards.php">Client's Cards</a></li>
				        	<li><a href="clientsaccidents.php">Accidents</a></li>

				        	<!--
				        	<?php if (isUserHasAccess(11 /* Statistics */)) { ?>
				        		<li role="separator" class="divider"></li>
				        		<li><a href="clientsstat.php">Statistics</a></li>
				        		<li><a href="clientscharts.php">Charts</a></li>
				        	<?php } ?>
				        	-->
				    	</ul>
					</li>
					<?php } ?>

					<?php if (isUserHasAccess(12 /* Logs */)) { ?>
					<li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Logs<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<li><a href="logs.php">Finances</a></li>
				        	<li><a href="managementlogs.php">Management</a></li>
				    	</ul>
					</li>
					<?php } ?>

			        <?php if (isUserHasAccess(13 /* Languages */) || isUserHasAccess(14 /* File Manager */)) { ?>
			        <li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Other<span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<?php if (isUserHasAccess(13 /* Languages */)) { ?>
				        	<li><a href="l10n.php">Localization — Languages</a></li>
				        	<li><a href="l10nconsts.php">Localization — Constants</a></li>
				        	<?php } ?>

				        	<!--
				        	<li role="separator" class="divider"></li>

				        	<?php if (isUserHasAccess(14 /* File Manager */)) { ?>
				        	<li><a href="finder/">File Manager</a></li>
				        	<?php } ?>
				        	-->
				    	</ul>
					</li>
			        <?php } ?>

				    <!--================================================================-->

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

					<li style="float: right;"><a href="dologout.php">Exit</a></li>
					<li style="float: right;">
			        	<input type="text" placeholder="Search..." id="search-area">
			        </li>
				</ul>
			</div>
		</div>
	</nav>
</header>