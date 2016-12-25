window.gameConfig = {
	symbols: [
		'ace',
		'book',
		'cat',
		'jack',
		'king',
		'pharaoh',
		'queen',
		'ring',
		'scarab',
		'status',
		'ten'
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
		'game': 'pharaohs-ring',
		'name': 'Pharaoh\'s Ring',
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
			['teaser-1', 'teaser-1'],
			['teaser-2', 'teaser-2'],
			['teaser-3', 'teaser-3']
		],
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
			'ace': [0, 0, 0, 20, 80, 300],
			'book': [0, 0, 0, 80, 800, 8000],
			'cat': [0, 0, 10, 80, 800, 4000],
			'jack': [0, 0, 0, 10, 60, 200],
			'king': [0, 0, 0, 20, 80, 300],
			'pharaoh': [0, 0, 20, 200, 2000, 10000],
			'queen': [0, 0, 0, 10, 60, 200],
			'ring': [0, 0, 0, 0, 0, 0],
			'scarab': [0, 0, 10, 60, 200, 1500],
			'status': [0, 0, 10, 60, 200, 1500],
			'ten': [0, 0, 0, 10, 60, 200]
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

	rebuildMatrix: function(callback) {
		var len = _.filter(_.flatten(getMatrix()), function(obj) { return obj.symbol == 'book'; }).length;

		if (len >= 1 && len <= 3) {
			playSoundById('teaser-' + len);
		}

		callback && callback();
	},

	minLines: 1,
	maxLines: 5,
	minLineBet: 8,
	maxLineBet: 100,
	manualAnimationLength: 20
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;
window.gameConfig.preloaderSettings.symbolsModifications = ['_b', '_f', '_fb'];

window.currentSymbolWidth = 208;
window.currentSymbolHeight = 208;