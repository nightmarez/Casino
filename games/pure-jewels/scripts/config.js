window.gameConfig = {
	symbols: [
		'blue',
		'crystal',
		'green',
		'lightblue',
		'magenta',
		'orange',
		'purple',
		'red',
		'scatter',
		'wild',
		'yellow'
	],

	isScatter: function(symbol) {
		var scatters = ['scatter'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['crystal'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'pure-jewels',
		'name': 'Pure Jewels',
		'sounds': [
			['reel-run-sound', 'reelrun', true],
			['reel-stop-sound-0', 'reelstop-0', true],
			['reel-stop-sound-1', 'reelstop-1', true],
			['reel-stop-sound-2', 'reelstop-2', true],
			['reel-stop-sound-3', 'reelstop-3', true],
			['reel-stop-sound-4', 'reelstop-4', true],
			['cash-sound', 'cash', true],
			['changebet-sound', 'changebet', true],
			['overlay-open', 'overlayopen', true],
			['overlay-close', 'overlayclose', true],
			['gamble-win', 'gamblewin', true],
			['autoplay-forced-stop', 'autoplayforcedstop', true],
			['change-max-bet', 'changemaxbet', true],
			['credit-increase', 'creditincrease', true]
		],
		'loadSymbolsAnimations': false,
		'imgtype': 'png',
		'imgs': [
			['card-blue', 'card-blue.png'],
			['card-red', 'card-red.png'],
			['card-0', 'card-0.png'],
			['card-1', 'card-1.png'],
			['card-2', 'card-2.png'],
			['card-3', 'card-3.png'],
			['game', 'game.jpg'],
			['paytable', 'paytable.png']
		],
		'animations': [
			'blue1',
			'crystal1',
			'green1',
			'lightblue1',
			'magenta1',
			'orange1',
			'purple1',
			'red1',
			'scatter1',
			'wild1',
			'yellow1',

			'blue2',
			'crystal2',
			'green2',
			'lightblue2',
			'magenta2',
			'orange2',
			'purple2',
			'red2',
			'scatter2',
			'wild2',
			'yellow2'
		],
		'scripts': [
			['sound', true],
			['preloader', true],
			['buttons', true],
			['gamble', true],
			['doubletap', true],
			['presets'],
			['indicators']
		],
		'styles': [
			['main'],
			['reels'],
			['interface'],
			['gamble', true],
			['paytable', true],
			['reels', true],
			['topmenu', true]
		],
		'blocks': [
		]
	},

	getWinMatrix: function() {
		return {
			'blue': [0, 0, 0, 32, 120, 480],
			'crystal': [0, 0, 0, 160, 1600, 8000],
			'green': [0, 0, 0, 80, 320, 1600],
			'lightblue': [0, 0, 0, 80, 320, 1600],
			'magenta': [0, 0, 0, 16, 48, 160],
			'orange': [0, 0, 0, 24, 80, 240],
			'purple': [0, 0, 0, 32, 120, 480],
			'red': [0, 0, 0, 16, 48, 160],
			'scatter': [0, 0, 0, 80, 400, 4000],
			'wild': [0, 0, 0, 0, 0, 0],
			'yellow': [0, 0, 0, 24, 80, 240]	
		};
	},

	getMatrixIndeces: function() {
		return [
			[0, 0, 0, 0, 0],
			[1, 1, 1, 1, 1],
			[2, 2, 2, 2, 2]
		];
	},

	postloader: function() {
		var tmp = {};

		_.each(window.data.symbols, function(value, key) {
			tmp[key + '_b'] = $(value).clone()[0];

			var baseTexture = new PIXI.BaseTexture(tmp[key + '_b']);
	    	var texture = new PIXI.Texture(baseTexture);
	    	window.data['pixi-symbols'][key + '_b'] = texture;
		});

		_.each(tmp, function(value, key) {
			window.data.symbols[key] = value;
		});
	},

	createFireVideo: function(symbols, symbol, count, n) {
		var element = window.data.animations[symbol + n];
		var divTag = $(element).clone();

		(function(divTag) {
			divTag[0].addEventListener('load', function() {
				divTag.attr('data-loaded', 'true');
			}, false);
		})(divTag);

		divTag
			.css('width', window.symbolWidth + 'px')
			.css('height', window.symbolHeight + 'px')
			.addClass('symbol-video')
			.attr('data-symbol', symbol);

		return divTag;
	},

	calcLineCost: function(line) {
		var cost = 0;
		var winMatrix = this.getWinMatrix();

		for (var i = 0; i < line.length; ++i) {
			cost += winMatrix[line[i].symbol][line.length];
		}

		return cost;
	},

	findBestLine: function(lines) {
		var bestLine = false;
		var bestLineCost = 0;
		_this = this;

		_.each(lines, function(line) {
			var cost = _this.calcLineCost(line);

			if (cost > bestLineCost) {
				bestLineCost = cost;
				bestLine = line;
			}
		});

		return bestLine;
	},

	findWinLines: function() {
		var matrix = getMatrix();
		var winLines = [];
		var indx = [0, 0, 0, 0, 0];

		do {
			var line = [];
			var s = false;

			for (var i = 0; i < indx.length; ++i) {
				var idxi = indx[i];
				var mxi = matrix[i][idxi];
				var symb = mxi.symbol;

				if (s) {
					if (this.isSubstitute(symb)) {
						line.push(mxi);
					} else {
						if (symb != s) {
							if (this.calcLineCost(line)) {
								winLines.push(line);
							}

							break;
						} else {
							line.push(mxi);
						}
					}
				} else {
					if (this.isSubstitute(symb)) {
						line.push(mxi);
					} else {
						line.push(mxi);
						s = symb;
					}
				}
			}

			++indx[0];

			for (var k = 0; k < indx.length; ++k) {
				if (indx[k] > 2) {
					if (k == indx.length - 1) {
						return winLines;
					}

					indx[k] = 0;
					++indx[k + 1];
				}
			}
		} while (true);
	},

	tryContinue: function(callback, line) {
		var divs = [];

		for (var k = 0; k < line.length; ++k) {
			var div = this.createFireVideo(_.map(line, function(f) { return f.symbol; }), line[k].symbol, line.length, 2)
				.attr('data-left', k)
				.attr('data-top', line[k].y)
				.attr('data-symbol', line[k].symbol)
				.css('top', line[k].y);
								
			divs.push(div);
		}
		
		$('.symbol-video').remove();
		createWinBorders(line, divs, 0, true);

		var timeout = calcLineDisplayTime(_.map(line, function(item) { return item.symbol + '2'; }));
		var _this = this;

		setTimeout(function() {
			$('.symbol-video').remove();

			_.each(line, function(item) {
				for (var i = 0; i < reels.length; ++i) {
					var renderer = window.renderers[i];
					var stage = window.stages[i];

					for (var j = 0; j < reels[i].length; ++j) {
						if (reels[i][j].symbol == item.symbol &&
							reels[i][j].y == item.y &&
							reels[i][j].reel == item.reel) {

							reels[i][j] = false;
							stage.removeChild(item.obj);
						}
					}

					renderer.render(stage);
				}
			});
			
			for (var i = 0; i < reels.length; ++i) {
				reels[i] = _.compact(_.filter(reels[i], function(r) { return !!r; }));
			}

			_this.runReels(callback, true);
		}, timeout);
	},

	runReels: function(callback, dontClear) {
		if (reelsRunned) {
			return;
		}

		var _this = this;

		removeAllWinBorders();

		if (!dontClear) {
			rewriteStaticMatrix();
		}

		var bet = parseInt($('#betField').text());
		var credits = parseInt($('#creditField').text());

		if (credits < bet) {
			return;
		}

		setLabelInfo('Good luck!');
		credits -= bet;
		$('#creditField').text(credits);  
		reelsRunned = true;
		var stepsPerMs = 1;
		var oldTime;
		var firstStep = true;

		var nextStep = function(currTime) {
			var interval = currTime - oldTime;
			interval = interval <= 20 ? 20 : interval;
			interval = interval > 25 ? 25 : interval;
			var reelStep = Math.ceil(interval * stepsPerMs);
			oldTime = currTime;

			for (var i = 0; i < reels.length; ++i) {
				var reel = reels[i];

				// required new element
				if (reel.length == 0 ||
					reel.length == 1 && reel[0].y >= window.symbolHeight / 2 ||
					reel.length == 2 && reel[1].y >= window.symbolHeight / 2) {

					// create new element
					var symbol = getNextSymbol(i);

					var img = data['pixi-symbols'][window.gameConfig.symbols[symbol]];
					var imgSmooth = data['pixi-symbols'][window.gameConfig.symbols[symbol]];
					
					var sprite = new PIXI.Sprite(img);
					var spriteSmooth = new PIXI.Sprite(imgSmooth);

					sprite.position.y = -window.symbolHeight;
					spriteSmooth.position.y = -window.symbolHeight;

					reels[i].push({
						y: -window.symbolHeight,
						obj: sprite,
						objSmooth: spriteSmooth,
						symbol: window.gameConfig.symbols[symbol],
						reel: i
					});

					window.stages[i].addChild(sprite);
				}

				// move elements
				for (var j = 0; j < reel.length; ++j) {
					var k = 3 - j - 1;

					reel[j].y += reelStep;
					reel[j].obj.position.y += reelStep;
					reel[j].objSmooth.position.y += reelStep;

					if (reel[j].y >= window.symbolHeight * k) {
						reel[j].y = window.symbolHeight * k;
						reel[j].obj.position.y = window.symbolHeight * k;
						reel[j].objSmooth.position.y = window.symbolHeight * k;
					}
				}

				// draw elements
				if (reels[i].length) {
					var renderer = window.renderers[i];
					var stage = window.stages[i];
					renderer.render(stage);
				}
			}

			var gamePaused = function() {
				if (window.mobilePhonePaused) {
					setTimeout(gamePaused, 500);
				} else {
					return false;
				}
			}

			var isDone = function() {
				for (var i = 0; i < reels.length; ++i) {
					if (reels[i].length < 3) {
						return false;
					}
				}

				for (var i = 0; i < reels.length; ++i) {
					var reel = reels[i];

					for (var j = 0; j < reel.length; ++j) {
						var obj = reel[j];
						var k = 3 - j - 1;
						var offset = 1;

						if (!(obj.y > window.symbolHeight * k - offset && obj.y < window.symbolHeight * k + offset)) {
							return false;
						}
					}
				}

				return true;
			};

			var done = isDone();

			// repeat or callback
			if (!done) {
				if (!gamePaused()) {
					requestAnimationFrame(function() {
						if (!gamePaused()) {
							nextStep(new Date().getTime());
						}
					});
				}
			} else {
				var winLines = _this.findWinLines();

				var endGameFunc = function() {
					var bestLine = _this.findBestLine(winLines);
					var sum = _this.calcLineCost(bestLine);

					if (winLines.length && sum > 0) {
						if (isSoundEnabled()) {
							playSoundById('cash-sound');
						}

						window.needCollect = true;
						window.totalWin += sum;
						window.reelsRunned = false;

						console.log('[' + _.map(bestLine, function(item) { return item.symbol; }).join(', ') + '] = ' + sum);

						_this.tryContinue(callback, bestLine);
					} else {
						window.needCollect = window.totalWin > 0;
						window.reelsRunned = false;
						callback && callback();
					}
				};

				if (!winLines.length) {
					endGameFunc();
				} else {
					var bestLine = _this.findBestLine(winLines);

					if (bestLine) {
						$('#label-info').find('span').text('Win: ' + _this.calcLineCost(bestLine));

						var divs = [];

						for (var k = 0; k < bestLine.length; ++k) {
							var div = _this.createFireVideo(_.map(bestLine, function(f) { return f.symbol; }), bestLine[k].symbol, bestLine.length, 1)
								.attr('data-left', k)
								.attr('data-top', bestLine[k].y)
								.attr('data-symbol', bestLine[k].symbol)
								.css('top', bestLine[k].y);
									
							divs.push(div);
						}

						createWinBorders(bestLine, divs, 0, true);

						var timeout = calcLineDisplayTime(_.map(bestLine, function(item) { return item.symbol + '1'; }));

						setTimeout(function() {
							endGameFunc();
						}, timeout);
					} else {
						endGameFunc();
					}
				}
			}
		};

		if (firstStep) {
			firstStep = false;

			setTimeout(function() {
				if (!dontClear) {
					window.reels = _.map(_.range(window.gameConfig.getMatrixIndeces()[0].length), function() { return []; });

					var j = 0;
					_.each(window.stages, function(stage) {
						for (var i = stage.children.length - 1; i >= 0; --i) {
							stage.removeChild(stage.children[i]);
						}

						window.renderers[j++].render(stage);
					});

					rewriteStaticMatrix();
				}

				window.reelsRunned = true;
				oldTime = new Date().getTime();
				nextStep(oldTime - 1000 / 30);
			}, 300);
		}
	},

	minLines: 3,
	maxLines: 3,
	minLineBet: 40,
	maxLineBet: 10000,
	manualAnimationLength: 20
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;
window.gameConfig.preloaderSettings.symbolsModifications = [];

window.gameConfig.presetSymbols = generatePresetSymbols(window.gameConfig.symbols.length);
window.gameConfig.preset = generateRandomPreset(window.gameConfig.presetSymbols, 25);