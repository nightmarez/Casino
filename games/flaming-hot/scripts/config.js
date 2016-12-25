window.gameConfig = {
	symbols: [
		'apple',
		'banana',
		'bar',
		'cherry',
		'dollar',
		'grapes',
		'melon',
		'peach',
		'seven',
		'wild'
	],

	isScatter: function(symbol) {
		var scatters = ['dollar'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['wild'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'flaming-hot',
		'name': 'Flaming Hot',
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
			['game', 'game.jpg']
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
			'apple': [0, 0, 0, 8, 40, 100],
			'banana': [0, 0, 0, 20, 80, 200],
			'bar': [0, 0, 0, 40, 100, 300],
			'cherry': [0, 0, 0, 8, 40, 100],
			'dollar': [0, 0, 0, 80, 800, 20000],
			'grapes': [0, 0, 0, 8, 40, 100],
			'melon': [0, 0, 0, 20, 80, 200],
			'peach': [0, 0, 0, 8, 40, 100],
			'seven': [0, 0, 4, 60, 200, 1000],
			'wild': [0, 0, 0, 0, 0, 0]
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
			[2, 1, 1, 1, 0],
			[0, 1, 1, 1, 2],
			[1, 2, 1, 0, 1],
			[1, 0, 1, 2, 1],
			[0, 1, 0, 1, 0],
			[2, 1, 2, 1, 2],
			[1, 1, 0, 1, 1],
			[1, 1, 2, 1, 1],
			[0, 2, 0, 2, 0],
			[2, 0, 2, 0, 2],
			[2, 0, 1, 0, 2]
		];
	},

	minLines: 1,
	maxLines: 20,
	minLineBet: 8,
	maxLineBet: 100
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;

window.gameConfig.presetSymbols = generatePresetSymbols(window.gameConfig.symbols.length);
window.gameConfig.preset = generateRandomPreset(window.gameConfig.presetSymbols, 25);