window.gameConfig = {
	symbols: [
		'star',
		'clown',
		'seven',
		'melon',
		'grapes',
		'plum',
		'orange',
		'lemon',
		'cherry'
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
		var substitutes = ['clown'];

		for (var i = 0; i < substitutes.length; ++i) {
			if (symbol == substitutes[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'sizzling-6',
		'name': 'Sizzling 6',
		'sounds': [
			['reel-run-sound', 'reelspin'],
			['reel-stop-sound-0', 'reelstop-0'],
			['reel-stop-sound-1', 'reelstop-1'],
			['reel-stop-sound-2', 'reelstop-2'],
			['reel-stop-sound-3', 'reelstop-3'],
			['reel-stop-sound-4', 'reelstop-4'],
			['reel-stop-sound-5', 'reelstop-5'],
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
		'animations': [
			'clown_cherry',
			'clown_grapes',
			'clown_lemon',
			'clown_orange',
			'clown_plum',
			'clown_seven'
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
			'cherry': [0, 0, 5, 20, 50, 200],
			'clown_cherry': [0, 0, 5, 20, 50, 200],
			'grapes': [0, 0, 0, 50, 200, 500],
			'clown_grapes': [0, 0, 0, 50, 200, 500],
			'lemon': [0, 0, 0, 20, 50, 200],
			'clown_lemon': [0, 0, 0, 20, 50, 200],
			'melon': [0, 0, 0, 50, 200, 500],
			'clown_melon': [0, 0, 0, 50, 200, 500],
			'orange': [0, 0, 0, 20, 50, 200],
			'clown_orange': [0, 0, 0, 20, 50, 200],
			'plum': [0, 0, 0, 20, 50, 200],
			'clown_plum': [0, 0, 0, 20, 50, 200],
			'seven': [0, 0, 0, 100, 1000, 5000],
			'clown_seven': [0, 0, 0, 100, 1000, 5000],
			'star': [0, 0, 0, 10, 50, 250],
			'clown_star': [0, 0, 0, 10, 50, 250],
			'clown': [0, 0, 0, 0, 0, 0]
		};
	},

	getMatrixIndeces: function() {
		return [
			[1, 1, 1, 1, 1, 1],
			[0, 0, 0, 0, 0, 0],
			[2, 2, 2, 2, 2, 2],
			[0, 1, 2, 2, 1, 0],
			[2, 1, 0, 0, 1, 2]
		];
	},

	minLines: 5,
	maxLines: 5,
	minLineBet: 8,
	maxLineBet: 100,

	fruitsFilter: function(fruits) {
		var result = [];
		var mainSymbol = false;

		for (var i = 0; i < fruits.length; ++i) {
			var fruit = fruits[i];

			if (this.isSubstitute(fruit.symbol)) {
				fruit.symbol = false;
			} else {
				mainSymbol = fruit.symbol;
			}

			result.push(_.clone(fruit));
		}

		if (mainSymbol) {
			for (var i = 0; i < fruits.length; ++i) {
				if (result[i].symbol == false) {
					result[i].symbol = 'clown_' + mainSymbol;
				}
			}
		} else {
			result = [];
		}

		return result;
	}
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;

window.currentSymbolWidth = 188;
window.currentSymbolHeight = 222;