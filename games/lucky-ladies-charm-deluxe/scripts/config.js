window.gameConfig = {
	symbols: [
		'lady',
		'bug',
		'r.foot',
		'horses',
		'penni',
		'clover',
		'ace',
		'king',
		'queen',
		'jack',
		'ten',
		'nine',
		'hands'
	],

	isScatter: function(symbol) {
		var scatters = ['hands'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['lady'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'lucky-ladies-charm-deluxe',
		'name': 'LuckyLadiesCharmDeluxe',
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
			['paytable', 'paytable.png'],
			['gamble', 'gamble.jpg'],
			['border', 'border.jpg'],
			['dialogbox', 'dialogbox.png'],
			['coveranimation0', 'coveranimation0.png'],
			['coveranimation1', 'coveranimation1.png'],
			['coveranimation2', 'coveranimation2.png'],
			['coveranimation3', 'coveranimation3.png'],
			['coveranimation4', 'coveranimation4.png'],
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
			['gamble'],
			['rules'],
			['freegamedialog'],
			['gamble', true],
			['paytable', true],
			['reels', true],
			['topmenu', true]
		],
		'blocks': [
			['paytable'],
			['rules-1'],
			['rules-2'],
			['freegamedialog']
		]
	},

	getWinMatrix: function() {
		return {
			'lady': [0, 0, 10, 250, 2500, 9000],
			'bug': [0, 0, 2, 25, 125, 750],
			'r.foot': [0, 0, 2, 25, 125, 750],
			'horses': [0, 0, 0, 20, 100, 400],
			'penni': [0, 0, 0, 15, 75, 250],
			'clover': [0, 0, 0, 15, 75, 250],
			'ace': [0, 0, 0, 10, 50, 125],
			'king': [0, 0, 0, 10, 50, 125],
			'queen': [0, 0, 0, 5, 25, 100],
			'jack': [0, 0, 0, 5, 25, 100],
			'ten': [0, 0, 0, 5, 25, 100],
			'nine': [0, 0, 2, 5, 25, 100],
			'hands': [0, 0, 2, 5, 20, 500],
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
	minLineBet: 4,
	maxLineBet: 100
};

window.gameConfig.preloaderSettings.symbols = window.gameConfig.symbols;

