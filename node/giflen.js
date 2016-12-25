var WebSocketServer = require('ws').Server,
    wss = new WebSocketServer({ port: 1338 });

var libgif = require('./libgif.js');

function getLen(path, callback) {
	var img = new Image();
	img.src = path;

	(function(img, callback) {
		img.addEventListener('load', function() {
			var rub = new libgif.SuperGif({ gif: img } );

			rub.load(function() {
				var len = rub.get_length();
				callback && callback(len);
			});
		});
	})(img, callback);
}

getLen('../games/sizzling-hot-deluxe/imgs/symbols/cherry.gif', function(len) {
	console.log('Length: ' + len);
});