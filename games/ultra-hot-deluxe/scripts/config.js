window.gameConfig = {
	symbols: [
		'7',
		'star',
		'bar',
		'plum',
		'orange',
		'lemon',
		'cherry',
		'x'
	],

	isScatter: function(symbol) {
		var scatters = ['star'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = [];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'ultra-hot-deluxe',
		'name': 'UltraHotDeluxe',
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
			['reels'],
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
			'7': [0, 0, 0, 1500],
			'star': [0, 0, 0, 40],
			'bar': [0, 0, 0, 12],
			'plum': [0, 0, 0, 8],
			'orange': [0, 0, 0, 8],
			'lemon': [0, 0, 0, 8],
			'cherry': [0, 0, 0, 8],
			'x': [0, 0, 0, 1]
		};
	},

	getMatrixIndeces: function() {
		return [
			[1, 1, 1],
			[0, 0, 0],
			[2, 2, 2],
			[0, 1, 2],
			[2, 1, 0]
		];
	},

	minLines: 1,
	maxLines: 5,
	minLineBet: 8,
	maxLineBet: 100
};

window.currentSymbolWidth = 367;
window.currentSymbolHeight = 222;

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;