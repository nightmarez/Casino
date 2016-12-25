window.gameConfig = {
	symbols: [
		'bird',
		'cat',
		'chest',
		'drawing',
		'eye',
		'jug',
		'lady',
		'person',
		'pyramid',
		'scarab',
		'scatter',
		'scepter',
		'time'
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
		var scatters = ['scarab'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'rise-of-ra',
		'name': 'Rise of Ra',
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
			'bird': [0, 0, 0, 5, 25, 100],
			'cat': [0, 0, 0, 10, 50, 125],
			'chest': [0, 0, 2, 5, 25, 100],
			'drawing': [0, 0, 0, 10, 50, 125],
			'eye': [0, 0, 0, 15, 75, 250],
			'jug': [0, 0, 0, 5, 25, 100],
			'lady': [0, 0, 2, 25, 125, 750],
			'person': [0, 0, 2, 25, 125, 750],
			'pyramid': [0, 0, 0, 15, 75, 250],
			'scarab': [0, 0, 10, 250, 2500, 10000],
			'scatter': [0, 0, 30, 75, 300, 7500],
			'scepter': [0, 0, 0, 10, 50, 125],
			'time': [0, 0, 0, 5, 25, 100]
		};
	},

	getMatrixIndeces: function() {
		return [
			[1, 1, 1, 1, 1],
			[0, 0, 0, 0, 0],
			[2, 2, 2, 2, 2],
			[0, 1, 2, 1, 0],
			[2, 1, 0, 1, 2]
		];
	},

	minLines: 5,
	maxLines: 5,
	minLineBet: 5,
	maxLineBet: 100
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;