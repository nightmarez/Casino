window.gameConfig.openCoverField = function(callback, max) {
	if (!max) {
		max = 0;
	}

	var depth = function(div) {
		var sub = $(div).find('div');

		if (sub.length) {
			return depth($(sub[0]));
		}

		return div;
	}

	for (var i = 0; i < (max < 5 ? max : 5); ++i) {
		for (var j = 0; j < (max < 3 ? max : 3); ++j) {
			var div = window.coverMatrix[i][j];

			if (div) {
				var div = depth(div);

				if (div.attr('base') == 'true') {
					window.coverMatrix[i][j] = false;
				} else {
					window.coverMatrix[i][j] = div.parent();
				}
					
				div.remove();
			}
		}
	}

	var _this = this;
	var any = false;
	for (var i = 0; i < 5; ++i) {
		for (var j = 0; j < 3; ++j) {
			if (window.coverMatrix[i][j] !== false) {
				any = true;
			}
		}
	}

	if (any) {
		setTimeout(function() {
			this.openCoverField(callback, max + 1);
		}, 200);
	} else {
		var gamePaused = function() {
			if (window.mobilePhonePaused) {
				setTimeout(gamePaused, 500);
			} else {
				return false;
			}
		};

		if (!gamePaused()) {
			var endGameFunc = function() {
				rewriteStaticMatrix();
				stopSoundById('reel-run-sound');
				window.reelsRunned = false;

				gameOver(function() {
					if (window.autoplay) {
						removeAllWinBorders();
						rewriteStaticMatrix();
						callback && callback();
					}
				});

				if (!window.autoplay) {
					callback && callback();
				}
			};

			if (window.gameConfig.rebuildMatrix) {
				window.gameConfig.rebuildMatrix(function() {
					endGameFunc();
				});
			} else {
				endGameFunc();
			}
		}
	}
};

window.gameConfig.play = function(callback1, callback2) {
	if (!window.specGame) {
		return playDefault(callback1, callback2);
	}

	if (window.needExit) {
		return;
	}

	if (!$('div[base=true]').length) {
		this.coverField();

		// fill reels
		for (var i = 0; i < $('.reel').length; ++i) {
			var renderer = window.renderers[i];
			var stage = window.stages[i];

			for (var j = 0; j < 3; ++j) {
				var symbol = getNextSymbol(i);
				var img = data['pixi-symbols'][window.gameConfig.symbols[symbol]];
				var imgSmooth = data['pixi-symbols'][window.gameConfig.symbols[symbol] + '_b'];
				var sprite = new PIXI.Sprite(img);
				var spriteSmooth = new PIXI.Sprite(img);

				window.reels[i].push({
					x: 0,
					y: j * window.symbolHeight,
					obj: sprite,
					objSmooth: spriteSmooth,
					symbol: window.gameConfig.symbols[symbol]
				});

				sprite.position.x = 0;
				sprite.position.y = j * window.symbolHeight;

				stage.addChild(sprite);
			}

			renderer.render(stage);
		}
	}

	window.gameComplete = false;

	$('.symbol-video').remove();
	window.stopLinesAnimation = true;
	stopSoundById('cash-sound');

	callback1 && callback1();

	removeAllWinBorders();
	rewriteStaticMatrix();

	this.openCoverField(callback2);
};

window.gameConfig.createCoverBlock = function() {
	var block = false;
	var outer = false;

	for (var i = 4; i >= 0; --i) {
		var div = $(document.createElement('div'))
			.css('position', 'absolute')
			.css('width', window.symbolWidth + 'px')
			.css('height', window.symbolHeight + 'px')
			.css('left', 0)
			.css('top', 0);

		if (i == 4) {
			div.attr('base', 'true');
		}

		var img = $(window.data.imgs['coveranimation' + i])
			.clone()
			.attr('width', window.symbolWidth)
			.attr('height', window.symbolHeight);

		div.append(img);

		if (!block) {
			outer = block = div;
		} else {
			outer.append(div);
			outer = div;
		}
	}

	return block;
};

window.gameConfig.coverField = function() {
	var coverMatrix = [[], [], [], [], []];

	for (var j = 0; j < 3; ++j) {
		for (var k = 0; k < 5; ++k) {
			var img = this.createCoverBlock()
				.css('top', (j * window.symbolHeight) + 'px');
			coverMatrix[k][j] = img;
			var reel = $('#reel-' + k);
			reel.append(img);
		}
	}

	window.coverMatrix = coverMatrix;
};

window.gameConfig.startSpecialGame = function() {
	window.specGame = true;
	window.specGamesCount = 15;
	playSoundById('free-game');

	$('#freegamedialog-outer').show();
	var _this = this;

	setTimeout(function() {
		$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
		$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
		$('#button-start').style('background-position', '0 -222px', 'nonimportant');
		$('#button-start').text('Start');
		$('#freegamedialog-outer').hide();
		_this.coverField();
	}, 2000);
};

window.gameConfig.calcLineDisplayTime = function(symbols) {
	if (symbols.length >= 3 && _.every(symbols, function(s) { return s == 'hands'; }) && !window.specGame) {
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
					_this.startSpecialGame();
				});
			} else {
				$('#bookblock-outer').show();
				_this.startSpecialGame();
			}
		}, timeout);

		return timeout;
	}

	return calcLineDisplayTimeDefault(symbols);
};