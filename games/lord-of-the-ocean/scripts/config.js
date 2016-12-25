window.gameConfig = {
	symbols: [
		'ace',
		'chest',
		'j',
		'k',
		'king',
		'lady',
		'man',
		'q',
		'scatter',
		'ten'
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
		var scatters = ['scatter'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'lord-of-the-ocean',
		'name': 'Lord of The Ocean',
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
			'ace': [0, 0, 0, 5, 25, 100],
			'chest': [0, 0, 0, 20, 100, 400],
			'j': [0, 0, 0, 5, 25, 100],
			'k': [0, 0, 0, 5, 25, 100],
			'king': [0, 0, 0, 20, 100, 400],
			'lady': [0, 0, 0, 15, 75, 250],
			'man': [0, 0, 0, 15, 75, 250],
			'q': [0, 0, 0, 5, 25, 100],
			'scatter': [0, 0, 0, 20, 100, 400],
			'ten': [0, 0, 0, 5, 25, 100]
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