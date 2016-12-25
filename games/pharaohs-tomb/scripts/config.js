window.gameConfig = {
	symbols: [
		'ace',
		'bird',
		'jack',
		'king',
		'queen',
		'time',
		'tomb',
		'water',
		'wings',
		'bug',
		'snake',
		'mummy_bottom',
		'mummy_middle',
		'mummy_top'
	],

	isScatter: function(symbol) {
		var scatters = ['tomb'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['mummy_bottom', 'mummy_middle', 'mummy_top'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'pharaohs-tomb',
		'name': 'Pharaoh\'s Tomb',
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
		'imgtype': 'png',
		'imgs': [
			['card-blue', 'card-blue.png'],
			['card-red', 'card-red.png'],
			['card-0', 'card-0.png'],
			['card-1', 'card-1.png'],
			['card-2', 'card-2.png'],
			['card-3', 'card-3.png'],
			['game', 'game.jpg'],
			['game-repeat', 'game-repeat.jpg'],
			['paytable', 'paytable.png']
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
			['paytable'],
			['gamble', true],
			['paytable', true],
			['reels', true],
			['topmenu', true]
		],
		'blocks': [
			['paytable']
		]
	},

	getWinMatrix: function() {
		return {
			'ace': [0, 0, 0, 20, 80, 400],
			'king': [0, 0, 0, 20, 80, 400],
			'queen': [0, 0, 0, 20, 80, 400],
			'jack': [0, 0, 0, 20, 80, 400],
			'wings': [0, 0, 0, 40, 200, 800],
			'water': [0, 0, 0, 40, 200, 800],
			'snake': [0, 0, 0, 80, 400, 1000],
			'bug': [0, 0, 0, 80, 500, 1200],
			'bird': [0, 0, 0, 200, 800, 1600],
			'time': [0, 0, 0, 400, 1000, 2000],
			'tomb': [0, 0, 0, 80, 800, 2000],
			'mummy_bottom': [0, 0, 0, 0, 0, 0],
			'mummy_middle': [0, 0, 0, 0, 0, 0],
			'mummy_top': [0, 0, 0, 0, 0, 0]
		};
	},

	getMatrixIndeces: function() {
		return [
			[1, 1, 1, 1, 1],
			[0, 0, 0, 0, 0],
			[2, 2, 2, 2, 2],
			[0, 1, 2, 1, 0],
			[2, 1, 0, 1, 2],
			[1, 2, 2, 2, 1],
			[1, 0, 0, 0, 1],
			[2, 2, 1, 0, 0],
			[0, 0, 1, 2, 2],
			[2, 1, 1, 1, 0]
		];
	},

	minLines: 1,
	maxLines: 10,
	minLineBet: 8,
	maxLineBet: 100
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;