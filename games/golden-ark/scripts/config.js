window.gameConfig = {
	symbols: [
		'ace',
		'king',
		'jack',
		'queen',
		'ten',
		'eye',
		'scarab',
		'lady',
		'winestone',
		'book'
	],

	isScatter: function(symbol) {
		var scatters = ['book'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['book'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'golden-ark',
		'name': 'Golden Ark',
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
			['credit-increase', 'creditincrease', true],
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
			['paytable', 'paytable.png']
		],
		'animations': [
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
			'ace': [0, 0, 0, 20, 160, 600],
			'king': [0, 0, 0, 20, 160, 600],
			'jack': [0, 0, 0, 20, 100, 400],
			'queen': [0, 0, 0, 20, 100, 400],
			'ten': [0, 0, 0, 20, 100, 400],
			'eye': [0, 0, 20, 120, 400, 3000],
			'scarab': [0, 0, 20, 120, 400, 3000],
			'lady': [0, 0, 40, 400, 4000, 20000],
			'winestone': [0, 0, 20, 160, 1600, 8000],
			'book': [0, 0, 0, 80, 800, 8000]	
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

	minLines: 5,
	maxLines: 5,
	minLineBet: 8,
	maxLineBet: 100,
	manualAnimationLength: 20
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;
window.gameConfig.preloaderSettings.symbolsModifications = ['_b'];