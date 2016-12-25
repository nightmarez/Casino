window.gameConfig = {
	symbols: [
		'ace',
		'bonus',
		'dragon',
		'gold',
		'jack',
		'king',
		'phoenix',
		'queen',
		'silver',
		'ten',
		'wild',
		'wild-down',
		'wild-left',
		'wild-right',
		'wild-up'
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
		'game': 'dragon-wild-fire',
		'name': 'Dragon Wild Fire',
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
			['game', 'game.png']
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
			'ace': [0, 0, 5, 20, 50, 200],
			'bonus': [0, 0, 5, 20, 50, 200],
			'dragon': [0, 0, 5, 20, 50, 200],
			'gold': [0, 0, 5, 20, 50, 200],
			'jack': [0, 0, 5, 20, 50, 200],
			'king': [0, 0, 5, 20, 50, 200],
			'phoenix': [0, 0, 5, 20, 50, 200],
			'queen': [0, 0, 5, 20, 50, 200],
			'silver': [0, 0, 5, 20, 50, 200],
			'ten': [0, 0, 5, 20, 50, 200],
			'wild': [0, 0, 5, 20, 50, 200],
			'wild-down': [0, 0, 0, 0, 0, 0],
			'wild-left': [0, 0, 0, 0, 0, 0],
			'wild-right': [0, 0, 0, 0, 0, 0],
			'wild-up': [0, 0, 0, 0, 0, 0]
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
	minLineBet: 8,
	maxLineBet: 100
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;

window.currentSymbolWidth = 211;
window.currentSymbolHeight = 211;