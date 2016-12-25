window.gameConfig.getMatrixLineFruit = function(line) {
	if (!window.specGame) {
		return getMatrixLineFruitDefault(line);
	}

	var matrix = window.gameConfig.getWinMatrix();
	var fruits = [];

	for (var i = 0; i < line.length; ++i) {
		var obj = line[i];

		console.log('obj.symbol: ' + obj.symbol + ', window.specSymbol: ' + window.specSymbol);

		if (obj.symbol == window.specSymbol) {
			fruits.push(obj);
		} else {
			fruits.push(false);
		}
	}

	return fruits;
};

window.gameConfig.rebuildMatrix = function(callback) {
	if (_.filter(_.map(_.flatten(getMatrix()), function(s) { return s.symbol; }), function(s) { return s == window.specSymbol; }).length >= 3) {
		stopSoundById('reel-run-sound');
		var reelsCount = $('.reel').length;
		var timeout = 500;

		var refillMatrix = function() {
			for (var i = 0; i < reelsCount; ++i) {
				var reel = reels[i];

				for (var j = 0; j < reel.length; ++j) {
					var item = reel[j];
					var y = item.y;
					var offset = 20;

					if (y > window.symbolHeight * 0 - offset && y < window.symbolHeight * 0 + offset ||
						y > window.symbolHeight * 1 - offset && y < window.symbolHeight * 1 + offset ||
						y > window.symbolHeight * 2 - offset && y < window.symbolHeight * 2 + offset) {

						if (item.symbol != window.specSymbol && item.symbol.indexOf('_fg') == -1) {
							item.symbol = item.symbol + '_fg';

							var img = data['pixi-symbols'][item.symbol];
							var sprite = new PIXI.Sprite(img);
							sprite.position.x = item.obj.position.x;
							sprite.position.y = item.obj.position.y;
							window.stages[i].removeChild(item.obj);
							item.obj = sprite;
							window.stages[i].addChild(sprite);
						}
					}
				}
			}

			rewriteStaticMatrix();

			for (var i = 0; i < reelsCount; ++i) {
				var reel = reels[i];

				for (var j = 0; j < reel.length; ++j) {
					var item = reel[j];
					var y = item.y;
					var offset = 20;

					if (y > window.symbolHeight * 0 - offset && y < window.symbolHeight * 0 + offset ||
						y > window.symbolHeight * 1 - offset && y < window.symbolHeight * 1 + offset ||
						y > window.symbolHeight * 2 - offset && y < window.symbolHeight * 2 + offset) {

						if (item.symbol == window.specSymbol) {
							for (var k = 0; k < reel.length; ++k) {
								if (k != j) {
									var itemc= reel[k];
									var yc = itemc.y;

									if (item.symbol != itemc.symbol) {
										if (yc > window.symbolHeight * 0 - offset && yc < window.symbolHeight * 0 + offset ||
											yc > window.symbolHeight * 1 - offset && yc < window.symbolHeight * 1 + offset ||
											yc > window.symbolHeight * 2 - offset && yc < window.symbolHeight * 2 + offset) {

											itemc.symbol = item.symbol;
											itemc.obj.texture = item.obj.texture.clone();
											itemc.objSmooth.texture = item.objSmooth.texture.clone();
												
											rewriteStaticMatrix();
											setTimeout(refillMatrix, timeout);
											return;
										}
									}
								}
							}
						}
					}
				}
			}

			callback && callback();
		};

		refillMatrix();
	} else {
		callback && callback();
	}
};

window.gameConfig.createFireVideo = function(symbols, symbol, count) {
	if (symbol == 'tomb' && count >= 3 && _.every(symbols, function(s) { return s == 'tomb'; }) && !window.specGame) {
		var element = data.animations['tomb_3'];
		var divTag = $(element).clone();

		(function(divTag) {
			divTag[0].addEventListener('load', function() {
				divTag.attr('data-loaded', 'true');
			}, false);
		})(divTag);

		divTag.css('width', window.symbolWidth + 'px')
			.css('height', (element.height >= window.symbolHeight ? element.height : window.symbolHeight) + 'px')
			.addClass('symbol-video')
			.attr('data-symbol', symbol)
			.attr('data-playsound', '3-books');

		return divTag;
	}

	if (count >= 3 && count < $('.reel').length || count == $('.reel').length && _.keys(data.animations).indexOf('symbol') == -1) {
		var jsAnimatedSymbols = ['ace', 'king', 'queen', 'jack', 'ten'];

		if (jsAnimatedSymbols.indexOf(symbol) != -1) {
			var img1 = $(window.data.symbols[symbol])
				.clone()
				.css('position', 'absolute')
				.css('width', window.symbolWidth + 'px')
				.css('height', window.symbolHeight + 'px');
			var img2 = $(window.data.symbols[symbol + '_l'])
				.clone()
				.css('position', 'absolute')
				.css('width', window.symbolWidth + 'px')
				.css('height', window.symbolHeight + 'px')
				.addClass('spriteanim');

			var divTag1 = $(document.createElement('div'));
			divTag1.append(img1);
			var divTag2 = $(document.createElement('div')).css('z-index', '100');
			divTag2.append(img2);

			(function(img1, img2) {
				img1[0].addEventListener('load', function() {
					img2[0].addEventListener('load', function() {
						divTag1.attr('data-loaded', 'true');
					}, false);
				}, false);
			})(img1, img2);

			divTag1.css('width', window.symbolWidth + 'px')
				.css('height', window.symbolHeight + 'px')
				.addClass('symbol-video')
				.attr('data-symbol', symbol)
				.css('position', 'relative');

			divTag2.css('width', window.symbolWidth + 'px')
				.css('height', window.symbolHeight + 'px')
				.css('position', 'absolute');

			divTag1.append(divTag2);

			return divTag1;
		}
	}

	if (window.specGame) {
		var element = symbol ? data.symbols[symbol] : false;
		var divTag = symbol ? $(element).clone() : $(document.createElement('div'));

		(function(divTag) {
			divTag[0].addEventListener('load', function() {
				divTag.attr('data-loaded', 'true');
			}, false);
		})(divTag);

		divTag.css('width', window.symbolWidth + 'px')
			.css('height', window.symbolHeight + 'px')
			.addClass('symbol-video')
			.attr('data-symbol', symbol);

		return divTag;
	}

	return createFireVideoDefault(symbols, symbol, count);
};

window.gameConfig.endSpecialGame = function() {
	window.specGame = false;
	window.preset = false;
	stopSoundById('free-game');
	$('#label-info > span').css('color', 'yellow');
};

window.gameConfig.startSpecialGame = function() {
	window.specGame = true;
	window.preset = false;
	window.specGamesCount = 10;
	playSoundById('free-game');
	$('#label-info > span').text('Bonus Games: ' + window.specGamesCount).css('color', 'red');
	window.pSpecIndex = _.map(_.range(window.gameConfig.getMatrixIndeces()[0].length), function() { return 0; });

	setTimeout(function() {
		$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
		$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
		$('#button-start').style('background-position', '0 -222px', 'nonimportant');
		$('#button-start').text('Start');
	}, 500);
};

window.gameConfig.selectSpecialSymbol = function() {
	var block = $('#book-content-symbol');
	var timeout = 100;
	var step = 126;
	var position = -step;
	var target = randomInteger(20, 30);
	var i = -1;
	var _this = this;

	var nextSymbol = function() {
		position += step;

		if (position >= 1134) {
			position = 0;
		}

		block.css('background-position', '-' + position + 'px 0');

		if (++i == target) {
			window.specSymbol = _this.symbols[Math.ceil(position / 126)];
			_this.startSpecialGame();
		} else {
			setTimeout(nextSymbol, timeout);
		}
	};

	nextSymbol();
};

window.gameConfig.calcLineDisplayTime = function(symbols) {
	if (symbols.length >= 3 && _.every(symbols, function(s) { return s == 'tomb'; }) && !window.specGame) {
		symbols = _.map(_.range(3), function() { return 'tomb_3'; });
		var timeout = calcLineDisplayTimeDefault(symbols);
		window.stopLinesAnimation = true;
		window.stopAutoplay = true;
		window.autoplay = false;

		$('#button-autoplay').style('background-position', '0px 0px', 'important');
		$('#button-paytable').style('background-position', '0px 0px', 'important');
		$('#button-gamble').style('background-position', '0px 0px', 'important');
		$('#button-start').style('background-position', '0px 0px', 'important');
		window.gameComplete = true;

		var _this = this;

		setTimeout(function() {
			$('.symbol-video').remove();

			if (window.needCollect) {
				transferWin(function() {
					window.needCollect = false;
					$('#bookblock-outer').show();
					_this.selectSpecialSymbol();
				});
			} else {
				$('#bookblock-outer').show();
				_this.selectSpecialSymbol();
			}
		}, timeout);

		return timeout;
	}

	return calcLineDisplayTimeDefault(symbols);
};

window.gameConfig.gameOver = function(callback) {
	if (!window.specGame) {
		gameOverDefault(callback);
	} else {
		window.stopLinesAnimation = false;
		var lines = getMatrixHorizontalLines();
		var fullLines = [];
		console.log('============ game complete ============');

		this.rebuildMatrix(function() {
			rewriteStaticMatrix();
			reelsRunned = false;
			stopSoundById('reel-run-sound');
			window.reelsRunned = false;



			callback && callback();
		});

		

		/*
		var linesWin = 0;
		var winLinesCount = 0;
		var spin = [];

		for (var j = 0; j < lines.length; ++j) {
			var fruits = getMatrixLineFruit(lines[j]);

			if (fruits && window.gameConfig.fruitsFilter) {
				fruits = window.gameConfig.fruitsFilter(fruits);
			}

			if (fruits) {
				var divs = [];

				for (var k = 0; k < fruits.length; ++k) {
					var div = createFireVideo(_.map(fruits, function(f) { return f.symbol; }), fruits[k].symbol, fruits.length)
						.attr('data-left', k)
						.attr('data-top', fruits[k].y)
						.attr('data-symbol', fruits[k].symbol)
						.css('top', fruits[k].y);
						
					divs.push(div);
				}

				var lineWin = getLineWin(fruits);

				if (lineWin) {
					fullLines.push([lines[j], divs, j]);
				}			

				if (lineWin) {
					++winLinesCount;
					linesWin += lineWin;
					console.log('line ' + (j + 1) + ': [' + _.map(fruits, function(fruit) { return fruit.symbol; }).join(', ') + '] = ' + lineWin);
					spin.push(_.map(fruits, function(fruit) { return fruit.symbol; }).join(',') + '=' + lineWin);
				}
			}
		}
		console.log('win lines count: ' + winLinesCount);

		var totalWin = linesWin;
		console.log('total win: ' + totalWin);

		if (window.gameId) {
			var obj = {
				spin: spin.join('|'),
				win: totalWin,
				gameid: window.gameId,
				id: window.newspin,
				token: window.spintoken,
				bet: parseInt($('#betLineField').text()),
				betlines: parseInt($('#linesCountField').text()),
				matrix: _.map(getMatrix(), function(matrixLine) { return _.map(matrixLine, function(matrixItem) { return matrixItem.symbol }).join(','); }).join('|'),
				balance: parseInt($('#creditField').text()) + totalWin
			};

			console.log(obj);
			$.post('/common/php/logspin.php', obj);

			window.newspin = false;
			window.spintoken = false;
		}

		console.log('=======================================');

		var cycles = -1;

		var showNextFullLine = function(idx) {
			if (window.autoplay) {
				if (!idx) {
					++cycles;
				}
				
				if (cycles >= 1) {
					callback && callback();
					return;
				}
			}

			for (var k = 0; k < fullLines.length; ++k) {
				if (k != idx) {
					var otherLine = fullLines[k][1];

					for (var m = 0; m < otherLine.length; ++m) {
						var div = otherLine[m];
						div.css('display', 'none');
					}
				}
			}

			createWinBorders(fullLines[idx][0], fullLines[idx][1], fullLines[idx][2]);

			var next = idx >= fullLines.length - 1 ? 0 : idx + 1;
			var timeout = calcLineDisplayTime(_.map(fullLines[idx][1], function(item) { return item.attr('data-symbol'); }));

			if (!window.stopLinesAnimation) { 
				(function(next) {
					setTimeout(function() {
						rewriteStaticMatrix();

						if (!window.stopLinesAnimation) {
							if (window.requestAnimationFrame) {
								(function(next) {
									requestAnimationFrame(function() {
										showNextFullLine(next);
									});
								})(next);
							} else {
								showNextFullLine(next);
							}
						}
					}, timeout);
				})(next);
			} else {
				var cvs = document.getElementById('reel-fore');
				$(cvs).attr('width', $(cvs).css('width'));
				$(cvs).attr('height', $(cvs).css('height'));
				var ctx = cvs.getContext('2d');
				ctx.clearRect(0, 0, $(cvs).width(), $(cvs).height());

				rewriteStaticMatrix();
				callback && callback();
			}
		}

		if (fullLines.length) {
			if (isSoundEnabled()) {
				playSoundById('cash-sound');
			}

			showNextFullLine(0);
			window.needCollect = true;
			$('#button-start').text('Collect');
			window.totalWin = calculateWin();
			$('#lastWinField').text(window.totalWin);
		} else {
			callback && callback();
		}*/
	}
};