<?php
	require_once('utils.php');

	if (!isUserHasAccess(15 /* Products */)) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Market</title>
		<?php require_once('head.php'); ?>

		<style>
			a.product, a.product:link, a.product:hover, a.product:visited {
				border: none;
				text-decoration: none;
				display: block;
				float: left;
			}

			a.product div.image {
				width: 300px;
				height: 300px;
				background-size: contain;
				background-position: center;
				background-repeat: no-repeat;
			}

			a.product div.image:hover {
				-webkit-filter: blur(1px) grayscale(0.2) contrast(150%);
  				filter: blur(1px) grayscale(0.2) contrast(150%);
			}

			a.product span {
				display: block;
				font-size: 18px;
				text-align: center;
				color: black;
			}

			a.product:hover span {
				-webkit-filter: blur(1px) grayscale(0.2) contrast(150%);
  				filter: blur(1px) grayscale(0.2) contrast(150%);
			}

			a.product:hover span:last-child {
				-webkit-filter: none;
  				filter: none;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Market</div>

			<?php
				$db = new PdoDb();
				$req = $db->prepare('SELECT `id`, `title`, `cost`, `count` FROM `products`;');
				$req->execute();

				while (list($pid, $ptitle, $pcost, $count) = $req->fetch(PDO::FETCH_NUM)) {
					?>
						<a class="product" href="#">
							<div class="image" style="background-image: url('products/<?php echo $pid; ?>.jpg');"></div>
							<br>
							<span>
								<form action="buyproduct.php" method="POST" style="float: left; margin-left: 0; margin-bottom: 20px; width: 100%;">
									<label>Price:</label>
									<input type="number" name="price" value="0" style="max-width: 100px;">
									<input type="submit" value="Buy">
									<input type="hidden" name="id" value="<?php echo $pid; ?>">
									<input type="hidden" name="back" value="market">
								</form>
							</span>
						</a>
					<?php
				}
			?>

		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>