window.gameConfig = {
	symbols: [
		'person',
		'mummy',
		'status',
		'scarab',
		'ace',
		'king',
		'queen',
		'jack',
		'ten',
		'tomb'
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
		var scatters = ['tomb'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'book-of-ra-deluxe',
		'name': 'Book of Ra deluxe',
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
			['free-game', 'free-game'],
			['3-books', '3-books'],
			['gamblesuspense', 'gamblesuspense', true]
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
			['paytable', 'paytable.png'],
			['gamble', 'gamble.jpg']
		],
		'animations': [
			'tomb_3'
		],
		'scripts': [
			['sound', true],
			['preloader', true],
			['buttons', true],
			['gamble', true],
			['doubletap', true],
			['buttons'],
			['indicators'],
			['bonus']
		],
		'styles': [
			['main'],
			['paytable'],
			['animation'],
			['bookblock'],
			['rules'],
			['gamble'],
			['gamble', true],
			['paytable', true],
			['reels', true],
			['topmenu', true]
		],
		'blocks': [
			['paytable'],
			['bookblock'],
			['rules-1'],
			['rules-2']
		]
	},

	getWinMatrix: function() {
		return {
			'person': [0, 0, 10, 100, 1000, 5000],
			'mummy': [0, 0, 5, 40, 400, 2000],
			'status': [0, 0, 5, 30, 100, 750],
			'scarab': [0, 0, 5, 30, 100, 750],
			'ace': [0, 0, 0, 5, 40, 150],
			'king': [0, 0, 0, 5, 40, 150],
			'queen': [0, 0, 0, 5, 25, 100],
			'jack': [0, 0, 0, 5, 25, 100],
			'ten': [0, 0, 0, 5, 25, 100],
			'tomb': [0, 0, 0, 2, 20, 200],		
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
	maxLineBet: 100,
	manualAnimationLength: 20
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;
window.gameConfig.preloaderSettings.symbolsModifications = ['_b', '_l', '_f', '_fb', '_fl', '_fg'];