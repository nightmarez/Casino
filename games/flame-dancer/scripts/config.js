window.gameConfig = {
	symbols: [
		'ace',
		'coconut',
		'drum',
		'flame',
		'guitar',
		'island',
		'jack',
		'king',
		'nine',
		'person',
		'queen',
		'ten'
	],

	isScatter: function(symbol) {
		var scatters = ['flame'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	isSubstitute: function(symbol) {
		var scatters = ['person'];

		for (var i = 0; i < scatters.length; ++i) {
			if (symbol == scatters[i]) {
				return true;
			}
		}

		return false;
	},

	preloaderSettings: {
		'game': 'flame-dancer',
		'name': 'Flame Dancer',
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
			'coconut': [0, 0, 5, 20, 50, 200],
			'drum': [0, 0, 5, 20, 50, 200],
			'flame': [0, 0, 5, 20, 50, 200],
			'guitar': [0, 0, 5, 20, 50, 200],
			'island': [0, 0, 5, 20, 50, 200],
			'jack': [0, 0, 5, 20, 50, 200],
			'king': [0, 0, 5, 20, 50, 200],
			'nine': [0, 0, 5, 20, 50, 200],
			'person': [0, 0, 5, 20, 50, 200],
			'queen': [0, 0, 5, 20, 50, 200],
			'ten': [0, 0, 5, 20, 50, 200]
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

window.gameConfig.presetSymbols = generatePresetSymbols(window.gameConfig.symbols.length);
window.gameConfig.preset = generateRandomPreset(window.gameConfig.presetSymbols, 25);