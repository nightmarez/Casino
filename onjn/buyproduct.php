<?php
	require_once('utils.php');

	if (!isUserHasAccess(15 /* Products */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		header('Location: storage.php');
		die();
	}

	$id = intval($_POST['id']);

	if (!isset($_POST['price'])) {
		$price = 0;
	} else {
		$price = intval($_POST['price']);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Buy Product</title>
		<?php require_once('head.php'); ?>

		<style>
			.image {
				width: 100px;
				height: 100px;
				background-size: contain;
				background-position: center;
				background-repeat: no-repeat;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Buy Product</div>

			<table class="table">
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `title`, `cost`, `count` FROM `products` WHERE `id`=:id');
						$req->bindParam(':id', $id, PDO::PARAM_INT);
						$req->execute();

						while (list($title, $cost, $count) = $req->fetch(PDO::FETCH_NUM)) {
					?>
						<tr>
							<td>Icon</td>
							<td><div class="image" style="background-image: url('products/<?php echo $id; ?>.jpg');"></div></td>
						</tr>
						<tr>
							<td>Title</td>
							<td><?php echo $title; ?></td>
						</tr>
						<tr>
							<td>You have in Storage</td>
							<td><?php echo $count; ?></td>
						</tr>

						<form action="dobuyproduct.php" method="POST">
							<tr>
								<td>Price</td>
								<td>
									<input type="number" name="price" value="<?php echo $price; ?>">
								</td>
							</tr>
						
							<tr>
								<td>Room</td>
								<td>
									<select name="roomid">
										<?php
											$req1 = $db->prepare('SELECT `id`, `title`, `owner`, `money` FROM `rooms`;');
											$req1->execute();

											while (list($roomid, $title, $owner, $money) = $req1->fetch(PDO::FETCH_NUM)) {
												?>
													<option value="<?php echo $roomid; ?>" data-money="<?php echo $money; ?>"><?php echo htmlspecialchars($title); ?>&nbsp;(money:&nbsp;<?php echo $money; ?>)</option>
												<?php
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>How much you want to buy</td>
								<td>
									<input type="number" name="count" value="100">
									<input type="hidden" name="id" value="<?php echo $id; ?>">

									<?php if (isset($_POST['back'])) { ?>
										<input type="hidden" name="back" value="<?php echo $_POST['back']; ?>">
									<?php } ?>
								</td>
								<td><input type="submit" value="Buy"></td>
							</tr>
						</form>

						<script>
							$(document).ready(function() {
								$('table').submit(function(e) {
									var price = <?php echo $price; ?>;
									var money = parseInt($('option:selected').attr('data-money'));
									var count = parseInt($('input[name=count]').val());

									if (count * price > money) {
										e.stopPropagation();
										alertify.error('Not enough money');
										return false;
									}
								});
							});
						</script>

					<?php break; } ?>
				</tbody>
			</table>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>
