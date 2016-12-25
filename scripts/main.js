function generateBalls(count, callback) {
	$.get('/genballs.php?token=' + window.currentGameToken, function(result) {
		result = result.split('|');
		var win = result[0]
		var balls = result[1].split(',');
		callback && callback(balls, win);
	});
}

function randomBalls(count) {
	var arr = [];
	var result = [];

	for (var i = 1; i <= 80; ++i) {
		arr.push(i);
	}

	for (var j = 0; j < count; ++j) {
		var rnd = Math.floor(Math.random() * arr.length);
    	result.push(arr.splice(rnd, 1)[0]);
	}

	return result;
}

function createGameField(active) {
	var gameField = $('#game-field');
	gameField.empty();

	for (var j = 0; j < 8; ++j) {
		var tr = $(document.createElement('tr'));
		gameField.append(tr);

		for (var i = 0; i < 10; ++i) {
			var td = $(document.createElement('td'));
			tr.append(td);

			var item = $(document.createElement('div'))
				.addClass('game-item')
				.addClass('game-item-' + (j * 10 + i + 1))
				.text(j * 10 + i + 1);
			td.append(item);

			if (active) {
				item.click(function() {
					if (!window.gameInProcess) {
						if ($(this).hasClass('game-item-selected')) {
							document.getElementById('ball-wav').play();
							$(this).removeClass('game-item-selected');
						} else {
							if ($('.game-item-selected').length < 10) {
								document.getElementById('ball-wav').play();
								$(this).addClass('game-item-selected');
							}
						}
					}
				});
			}
		}
	}
}

function createGameRandoms(active) {
	var randomsField = $('#game-randoms');
	randomsField.empty();

	var tr = $(document.createElement('tr'));
	randomsField.append(tr);

	for (var i = 0; i < 10; ++i) {
		var td = $(document.createElement('td'));
		tr.append(td);

		var item = $(document.createElement('div'))
			.addClass('game-random-item')
			.text(i + 1);
		td.append(item);

		if (active) {
			item.click(function() {
				if (!window.gameInProcess) {
					document.getElementById('ball-wav').play();

					//if ($(this).hasClass('game-random-item-selected')) {
					//	$(this).removeClass('game-random-item-selected');
					//	$('.game-item-selected').removeClass('game-item-selected');
					//} else {
						$('.game-random-item-selected').removeClass('game-random-item-selected');
						$(this).addClass('game-random-item-selected');
						$('.game-item-selected').removeClass('game-item-selected');

						var count = parseInt($(this).text());
						var balls = randomBalls(count);
						for (var k = 0; k < balls.length; ++k) {
							$('.game-item-' + balls[k]).addClass('game-item-selected');
						}
					//}
				}
			});
		}
	}
}

function fillGameBalls() {
	var table = $('#game-balls');
	table.empty();

	var tr = $(document.createElement('tr'));
	table.append(tr);

	for (var i = 0; i < 20; ++i) {
		var td = $(document.createElement('td'));
		tr.append(td);

		var item = $(document.createElement('div'))
			.addClass('game-ball-item');
		td.append(item);
	}
}

function fillBets() {
	var table = $('#game-bets').find('tbody');
	table.empty();

	for (var j = 0; j < 10; ++j) {
		var tr = $(document.createElement('tr'));
		table.append(tr);

		for (var i = 0; i < 5; ++i) {
			var td = $(document.createElement('td'));
			tr.append(td);

			td.html('&nbsp;');
		}
	}
}

var currentCircullationLine = 0;
var circullationsCount = 10;

function addCircullation(n, balls) {
	var table = $('#circulations');
	var tr = $(table.find('tr')[currentCircullationLine]);

	if (++currentCircullationLine >= circullationsCount) {
		currentCircullationLine = 0;
	}

	$(tr.find('td')[0]).text(n);
	$(tr.find('td')[1]).text(balls.join(' '));
}

function fillCirculations() {
	var table = $('#circulations');
	table.empty();

	for (var j = 0; j < circullationsCount; ++j) {
		var tr = $(document.createElement('tr'));
		table.append(tr);

		for (var i = 0; i < 2; ++i) {
			var td = $(document.createElement('td'));
			tr.append(td);

			if (i == 0) {
				td.html('&nbsp;');
			} else {
				td.html('&nbsp;');
			}
		}
	}
}

function clearGameBalls() {
	$('.game-ball-item').removeClass('game-ball-item-full').text('');
}

function addGameBall(idx, value) {
	$($('.game-ball-item')[idx]).addClass('game-ball-item-full').text(value);
}

function applyBets(callback) {
	var betLines = $('#game-bets td:nth-child(3)');
	var request = {
		gameid: window.currentGame,
		token: window.currentGameToken,
		data: []
	};

	for (var j = 0; j < betLines.length; ++j) {
		var betLine = $(betLines[j]);
		var balls = [];
		var spans = betLine.find('span');

		for (var i = 0; i < spans.length; ++i) {
			var span = $(spans[i]);
			balls.push(parseInt(span.text()));
		}

		if (balls.length) {
			request.data.push({
				bet: parseInt($(betLine.parent().find('td')[1]).text()),
				balls: balls
			});
		}
	}

	$.post('applybets.php', { request: JSON.stringify(request) }, function(result) {
		if (result.trim() == 'OK') {
			callback && callback();
		}
	});
}

function generateGameBalls(count, balls, win) {
	if (count === undefined) {
		count = 20;
		generateBalls(count, function(balls, win) {
			document.getElementById('game-wav').play();
			generateGameBalls(count, balls, win);
		});
		return;
	}

	if (count > 0) {
		setTimeout(function() {
			var idx = balls.length - count;
			var ball = balls[idx];
			addGameBall(idx, ball);
			$('.game-item-' + ball).addClass('game-item-maroon');
			highlightBets(ball);
			generateGameBalls(count - 1, balls, win);
		}, 500);
	} else {
		addCircullation(idToDraw(window.currentGame), balls);

		if (parseInt(win)) {
			setHint('Ваш выигрыш: +' + win);

			setTimeout(function() {
				setHint('Выберите от 1 до 10 шаров и нажмите "Применить ставку"');
			}, 2000);
		} else {
			setHint('Удачи в следующий раз!');

			setTimeout(function() {
				setHint('Выберите от 1 до 10 шаров и нажмите "Применить ставку"');
			}, 2000);
		}

		setTimeout(function() {
			showBalance(function() {
				clearGame();
			});
		}, 3000);
	}
}

function highlightBets(ball) {
	var lines = $('#game-bets > tbody').find('tr');

	var table = [
		[0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
		[3, 1, 0, 0, 0, 0, 0, 0, 0, 0],
		[0, 10, 2, 1, 1, 0, 0, 0, 0, 0],
		[0, 0, 45, 10, 3, 2, 2, 0, 0, 0],
		[0, 0, 0, 80, 20, 15, 4, 5, 2, 0],
		[0, 0, 0, 0, 150, 60, 20, 15, 10, 5],
		[0, 0, 0, 0, 0, 500, 80, 50, 25, 30],
		[0, 0, 0, 0, 0, 0, 1000, 200, 125, 100],
		[0, 0, 0, 0, 0, 0, 0, 2000, 1000, 300],
		[0, 0, 0, 0, 0, 0, 0, 0, 5000, 2000],
		[0, 0, 0, 0, 0, 0, 0, 0, 0, 10000]
	];

	for (var j = 0; j < lines.length; ++j) {
		var line = $(lines[j]);
		var td = $(line.find('td')[2]);
		var spans = td.find('span');
		var total = 0;

		if (spans.length) {
			for (var i = 0; i < spans.length; ++i) {
				var span = $(spans[i]);

				if (parseInt(span.text()) == ball) {
					span.css('color', 'red');
					span.addClass('span-red');
				}

				if (span.hasClass('span-red')) {
					++total;
				}
			}

			$(line.find('td')[3]).text(total + ' / ' + spans.length);
			$(line.find('td')[4]).text(parseInt($(line.find('td')[1]).text()) * table[total][spans.length - 1]);
		}
	}
}

function clearGame() {
	$('.game-item-maroon').removeClass('game-item-maroon');
	fillBets();
	clearGameBalls();
	document.getElementById('game-wav').pause();
	document.getElementById('game-wav').currentTime = 0;
	window.gameInProcess = false;
	genGame();
	setHint('Выберите от 1 до 10 шаров и нажмите "Применить ставку"');
}

function getBet() {
	return parseInt($('#bet-value').text());
}

function setBet(bet) {
	$('#bet-value').text(bet);
}

function betUp() {
	var value = getBet();

	switch (value) {
		case 25:
			value = 50;
			break;

		case 50:
			value = 100;
			break;

		case 100:
			value = 200;
			break;

		case 200:
			value = 500;
			break;

		case 500:
			value = 1000;
			break;

		case 1000:
			value = 1000;
			break;

		default:
			value = 25;
			break;
	}

	setBet(value);
}

function betDown() {
	var value = getBet();

	switch (value) {
		case 1000:
			value = 500;
			break;

		case 500:
			value = 200;
			break;

		case 200:
			value = 100;
			break;

		case 100:
			value = 50;
			break;

		case 50:
			value = 25;
			break;

		case 25:
			value = 25;
			break;

		default:
			value = 25;
			break;
	}

	setBet(value);
}

function getBalance(callback) {
	$.get('/balance.php', function(balance) {
		callback && callback(balance);
	});
}

function showBalance(callback) {
	getBalance(function(balance) {
		$('#top-balance').html('Баланс:&nbsp;' + balance);
		callback && callback();
	});
}

function getCurrentTotalBets() {
	var lines = $('#game-bets > tbody').find('tr');
	var total = 0;

	for (var j = 0; j < lines.length; ++j) {
		var line = $(lines[j]);
		var text = $(line.find('td')[1]).text().trim();

		if (text.length) {
			total += parseInt(text);
		} else {
			break;
		}
	}

	return total;
}

function getEmptyBetLine() {
	var lines = $('#game-bets > tbody').find('tr');

	for (var j = 0; j < lines.length; ++j) {
		var line = $(lines[j]);

		if (!$(line.find('td')[0]).text().trim().length) {
			return line;
		}
	}

	return false;
}

function anyBetLine() {
	var lines = $('#game-bets > tbody').find('tr');

	for (var j = 0; j < lines.length; ++j) {
		var line = $(lines[j]);

		if ($(line.find('td')[0]).text().trim().length) {
			return true;
		}
	}

	return false;
}

function addBet(betid, money, balls, callback) {
	getBalance(function(balance) {
		if (getCurrentTotalBets() + money <= balance) {
			$('#top-balance').html('Баланс:&nbsp;' + (parseInt($('#top-balance').text().split(':')[1]) - money));

			var line = getEmptyBetLine();

			if (line) {
				var tds = line.find('td');

				$(tds[0]).text(betid);
				$(tds[1]).text(money);
				$(tds[2]).html('<span>' + balls.join('</span><span>') + '</span>');
				$(tds[3]).text('0 / ' + balls.length);

				$('.game-item-selected').removeClass('game-item-selected');
				$('.game-random-item-selected').removeClass('game-random-item-selected');
			} else {
				$('#game-hint').text('Выбрано максимальное количество ставок');
			}

			callback && callback();
		} else {
			setHint('Внесите кредиты на баланс');

			setTimeout(function() {
				setHint('Выберите от 1 до 10 шаров и нажмите "Применить ставку"');
			}, 2000);

			callback && callback();
		}
	});
}

function idToDraw(id) {
	var draw = id + '';

	while (draw.length < 14) {
		draw = '0' + draw;
	}

	draw = '7' + draw + 'G';
	return draw;
}

function genGame() {
	$.get('/gengame.php', function(result) {
		result = result.split('|');
		var id = result[0];
		var token = result[1];

		window.currentBetNumber = 0;
		window.currentGame = id;
		window.currentGameToken = token;
		var draw = idToDraw(id);
		$('#top-circulation').html('Тираж:&nbsp;' + draw);
	})
}

function lastUnplayed(callback) {
	$.get('/lastunplayed.php', function(result) {
		if (result.indexOf('|') == -1) {
			callback && callback(0);
			return;
		}

		result = result.split('|');
		window.currentBetNumber = 0;
		var id = result[0];
		var token = result[1];
		var draw = idToDraw(id);
		callback && callback(id, draw, token);
	});
}

function getCurrentBetNumber() {
	var num = '' + window.currentGame;

	while (num.length < 5) {
		num = '0' + num;
	}

	var betnum = '' + (window.currentBetNumber++);

	while (betnum.length < 3) {
		betnum = '0' + betnum;
	}

	return num + betnum;
}

function acceptBet(callback) {
	if (!window.gameInProcess) {
		var money = parseInt($('#bet-value').text());
		var balls = [];
		var selected = $('.game-item-selected');

		_.each(selected, function(ball) {
			balls.push(parseInt($(ball).text()));
		});

		if (balls.length) {
			addBet(getCurrentBetNumber(), money, balls, callback);
		} else {
			$('#game-hint').text('Нет выбранных шаров');

			setTimeout(function() {
				$('#game-hint').text('Выберите от 1 до 10 шаров и нажмите "Применить ставку"');
			}, 2000);

			callback && callback();
		}
	}
}

function playGame() {
	acceptBet(function() {
		var anyBet = anyBetLine();

		if (anyBet && !window.gameInProcess) {
			$('.game-random-item-selected').removeClass('game-random-item-selected');
			$('.game-item-selected').removeClass('game-item-selected');
			setHint('');
			window.gameInProcess = true;
			clearGameBalls();

			applyBets(function() {
				showBalance(function() {
					generateGameBalls();
				});
			});
		} else if (!anyBet) {
			$('#game-hint').text('Нет активных ставок');
			setTimeout(function() {
				$('#game-hint').text('Выберите от 1 до 10 шаров и нажмите "Применить ставку"');
			}, 3000);
		}
	});
}

function setHint(hint) {
	if (!hint.length) {
		$('#game-hint').html('&nbsp;');
	} else {
		$('#game-hint').text(hint);
	}
}

$(document).ready(function() {
	// bind keys
	$(document).keypress(function(e){
		var which = e.which;

		if (which >= 48 /* '0' */ && which <= 57 /* '9' */) {
			which = which == 48 ? 10 : which - 48;
			$('#game-randoms td:nth-child(' + which + ') > div').click();
		} else {
			switch (which) {
				case 32 /* space */:
					$('#accept-bet-button').click();
					break;

				case 13 /* enter */:
					$('#play-button').click();
					break;

				case 45 /* - */:
					$('#bet-down-button').click();
					break;

				case 43 /* + */:
				case 61 /* = */:
					$('#bet-up-button').click();
					break;

				case 104 /* 'h' */:
					$('#btn-rules').click();
					break;
			}
		}
	});

	// game scaling
	function applyScale() {
		var scaleFactor = 1, scaleFactorX = 1, scaleFactorY = 1;

		if ($(window).width() < 1500) {
			scaleFactorX = $(window).width() / 1500;
		}

		if ($(window).height() < 1000) {
			scaleFactorY = $(window).height() / 1000;
		}

		scaleFactor = scaleFactorX < scaleFactorY ? scaleFactorX : scaleFactorY;

		if (scaleFactor < 0.2) {
			scaleFactor = 0.2;
		}

		$('body').css('zoom', scaleFactor);
	}

	$(window).resize(function() {
		applyScale();
	});

	applyScale();

	// game fullscreen
	$('#button-fullscreen').toggle($(document).fullScreen() != null);

	$(document).bind("fullscreenchange", function(e) {
		if (!$(document).fullScreen()) {
			$('#button-fullscreen').css('display', 'block');
			$('#button-exitfullscreen').css('display', 'none');
		} else {
			$('#button-fullscreen').css('display', 'none');
			$('#button-exitfullscreen').css('display', 'block');
		}
    });

	$('#button-fullscreen').click(function() {
		$(document).fullScreen(true);
	});

	$('#button-exitfullscreen').click(function() {
		$(document).fullScreen(false);
	});
});