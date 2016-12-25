<?php
	//ini_set('error_reporting', E_ALL);
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);

	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		require_once('header.php');

		////////////////////////////////////////////////////

		//$db = new Db();
		//$q = 'DELETE FROM `giflen` WHERE 1=1;';
		//$r = $db->query($q);
		//$db->close();

		////////////////////////////////////////////////////

		?>
			<div class="panel panel-default">
				<div class="panel-heading">Анимированные GIF файлы</div>

				<table class="table" id="giffiles">
					<thead>
						<tr>
							<th>Игра</th>
							<th>Файлов</th>
							<th>Обработка</th>
						</tr>
					</thead>
					<body>
		<?php

		////////////////////////////////////////////////////

		$db = new PdoDb();

		$root = $_SERVER['DOCUMENT_ROOT'];
		$dir = opendir($root . '/games');

		while ($gamepath = readdir($dir)) {
		    if (is_dir($root . '/games/' . $gamepath) && $gamepath != '.' && $gamepath != '..') {
		    	$filesCount = 0;
		        $goodFilesCount = 0;
		        $badFiles = [];

		    	?>
		    		<tr>
		    			<td><?php echo htmlspecialchars($gamepath); ?></td>
		    	<?php

		        $fullSymbolsPath = $root . '/games/' . $gamepath . '/imgs/symbols/';

		        if (is_dir($fullSymbolsPath)) {
		        	$symbolsDir = opendir($fullSymbolsPath);

		        	while ($file = readdir($symbolsDir)) {
		        		$fullFileName = $fullSymbolsPath . $file;

			        	if (is_file($fullFileName) && pathinfo($file, PATHINFO_EXTENSION) == 'gif') {
			        		++$filesCount;

			        		$gamepath = htmlspecialchars($gamepath);
			        		$file = htmlspecialchars($file);
			        		$req = $db->prepare('SELECT `hash`, `len` FROM `giflen` WHERE `game` = :gamepath AND `name` = :file;');
			        		$req->bindParam(':gamepath', $gamepath);
			        		$req->bindParam(':file', $file);
			        		$req->execute();
			        		$any = false;

			        		while (list($hash, $len) = $req->fetch(PDO::FETCH_NUM))
							{
								if ($len != 0 && md5_file(htmlspecialchars($fullFileName)) == $hash) {
									++$goodFilesCount;
									$any = true;
								}
							}

							if (!$any) {
								$badFiles[] = htmlspecialchars('/games/'. $gamepath . '/imgs/symbols/' . $file);
							}
			        	}
			        }
		        }

		        ?>
		        		<td <?php echo $goodFilesCount != $filesCount ? 'style="color: red;"' : 'style="color: green"'; ?>><?php echo $goodFilesCount . ' / ' . $filesCount; ?></td>
		        		<td>
		        			<?php
		        				if (count($badFiles) == 0) {
		        					echo '<span style="color: green;">OK</span>';
		        				} else {
		        					foreach ($badFiles as $badFile) {
		        						?>
		        							<input type="hidden" value="<?php echo htmlspecialchars($badFile); ?>" data-game="<?php echo htmlspecialchars($gamepath); ?>" data-hash="<?php echo md5_file(htmlspecialchars($root . $badFile)); ?>" />
		        						<?php
		        					}
		        				}
		        			?>
		        		</td>
		    		</tr>
		    	<?php
		    }
		}

		////////////////////////////////////////////////////

		?>
					</body>
				</table>
			</div>

			<script>
				$(document).ready(function() {
					// add gif calculation script
					var script = $(document.createElement('script'))
						.attr('src', '../scripts/libgif.js');
					$('head').append(script);

					// data
					var data = [];

					// processing one gif file
					var processImg = function() {
						var obj = _.first(data);
						var img = new Image();
						var hidden = $(obj.hiddens.shift());
						var file = _.last(hidden.val().split('/'));
						var game = hidden.attr('data-game');
						var hash = hidden.attr('data-hash');

						img.addEventListener('load', function() {
							var rub = new SuperGif({ gif: img } );

			                rub.load(function() {
			                    var len = rub.get_length();

			                    console.log('/admin/setgiflen.php?game=' + game + '&file=' + file + '&hash=' + hash + '&len=' + len);

			                    $.get('/admin/setgiflen.php?game=' + game + '&file=' + file + '&hash=' + hash + '&len=' + len, function() {
			                    	var cnt = obj.countColumn.text().split('/');
				                    var countCurr = parseInt(cnt[0].trim()) + 1;
				                    var countTotal = cnt[1].trim();
				                    obj.countColumn.text(countCurr + ' / ' + countTotal);

				                    if (countCurr == countTotal) {
				                    	obj.countColumn.css('color', 'green');
				                    }

				                    work();
			                    });
			                });
						});
						
						img.src = hidden.val();
						hidden.remove();
					};

					// work function
					var work = function() {
						var obj = _.first(data);

						if (obj) {
							if (obj.hiddens.length) {
								if (!obj.workColumn.find('img').length) {
									var img = $(document.createElement('img'))
										.attr('src', '../imgs/loading.gif');
									obj.workColumn.append(img);
								}

								processImg();
							} else if (obj.workColumn.find('img').length && !obj.hiddens.length) {
								obj.workColumn.find('img').remove();
								obj.workColumn.text('OK').css('color', 'green');
								data.shift();
								work();
							}
						}
					};

					// collect data
					_.each($('#giffiles').find('tr'), function(tr) {
						var tr = $(tr);
						var countColumn = $(tr.find('td')[1]);
						var workColumn = $(tr.find('td')[2]);
						var hiddens1 = workColumn.find('input[type=hidden]');
						var hiddens = [];

						_.each(hiddens1, function(h) {
							hiddens.push($(h));
						});

						if (workColumn.find('input[type=hidden]').length) {
							var row = {
								countColumn: countColumn,
								workColumn: workColumn,
								hiddens: hiddens
							};

							data.push(row);
						}
					});

					// start work
					work();
				});
			</script>
		<?php

		require_once('footer.php');
	}
?>