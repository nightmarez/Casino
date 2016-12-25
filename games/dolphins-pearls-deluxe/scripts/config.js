window.gameConfig = {
	symbols: [
		'dolphi',
		'whale',
		'tortoi',
		'urchin',
		'sea ho',
		'yellyf',
		'ace',
		'king',
		'queen',
		'jack',
		'ten',
		'nine',
		'pearl'
	],

	isScatter: function(symbol) {
		var scatters = ['pearl'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['dolphin'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'dolphins-pearls-deluxe',
		'name': 'DolphinsPearlsDeluxe',
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
			['gamblesuspense', 'gamblesuspense', true]
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
			['paytable', 'paytable.png'],
			['info', 'info.jpg'],
			['gamble', 'gamble.jpg']
		],
		'scripts': [
			['sound', true],
			['preloader', true],
			['buttons', true],
			['gamble', true],
			['doubletap', true],
			['buttons'],
			['indicators']
		],
		'styles': [
			['main'],
			['paytable'],
			['gamble'],
			['rules'],
			['gamble', true],
			['paytable', true],
			['reels', true],
			['topmenu', true]
		],
		'blocks': [
			['paytable'],
			['rules-1'],
			['rules-2']
		]
	},

	getWinMatrix: function() {
		return {
			'dolphi': [0, 0, 10, 250, 2500, 9000],
			'whale': [0, 0, 2, 25, 125, 750],
			'tortoi': [0, 0, 2, 25, 125, 750],
			'urchin': [0, 0, 0, 20, 100, 400],
			'sea ho': [0, 0, 0, 15, 75, 250],
			'yellyf': [0, 0, 0, 15, 75, 250],
			'ace': [0, 0, 0, 10, 50, 125],
			'king': [0, 0, 0, 10, 50, 125],
			'queen': [0, 0, 0, 5, 25, 100],
			'jack': [0, 0, 0, 5, 25, 100],
			'nine': [0, 0, 2, 5, 25, 100],
			'ten': [0, 0, 0, 5, 25, 100],
			'pearl': [0, 0, 2, 5, 20, 500]
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