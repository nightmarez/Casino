function isHorizontalPosition() {
	return $(window).width() >= $(window).height();
}

function isVerticalPosition() {
	return !isHorizontalPosition();
}

/*
function applyScale() {
	if (/Firefox/i.test(navigator.userAgent)) {
		$('body').css('-moz-transform', 'scale(1)');
	} else {
		$('body').css('zoom', '1');
	}
	
	var scaleFactor = 1, scaleFactorX = 1, scaleFactorY = 1;
	var width, height;

	if (location.href.indexOf('/lobby/') != -1) {
		if (isAndroid()) {
			var width = window.screen && window.screen.availWidth || $(window).width();
			var height = window.height && window.screen.availHeight || $(window).height();
		} else if (isiOS()) {
			var width = $(window).width();
			var height = $(window).height();
		} else {
			var width = $(window).width();
			var height = $(window).height();
		}

		if (width < 1024) {
			scaleFactorX = width / 1024;
		}

		if (height < 1024) {
			scaleFactorY = height / 1024;
		}

		scaleFactor = scaleFactorX < scaleFactorY ? scaleFactorX : scaleFactorY;

		if (scaleFactor < 0.1) {
			scaleFactor = 0.1;
		}

		$('body').css('zoom', scaleFactor);
	} else {
		if (isiOS() && /CriOS/i.test(navigator.userAgent)) {
			//var width = window.screen && window.screen.availWidth || $(window).width();
			//var height = window.screen && window.screen.availHeight || $(window).height();
			var width = $(window).width();
			var height = $(window).height();
		} else if (isiOS()) {
			var width = $(window).width();
			var height = $('#body-repeat').height();
		} else {
			var width = $('#body-repeat').width();
			var height = $('#body-repeat').height();
		}

		var cnst = 1.3;

		if (width > height * cnst) {
			// height should occupy the entire space...
			scaleFactorY = height / 1024;
			scaleFactor = scaleFactorY;
		} else {
			// ...but this must don't broke game
			scaleFactorY = (width / cnst) / 1024;
			scaleFactor = scaleFactorY;
		}
	}

	

	if (scaleFactor < 0.1) {
		scaleFactor = 0.1;
	}

	if (/Firefox/i.test(navigator.userAgent)) {
		$('body').css('-moz-transform', 'scale(' + scaleFactor + ')');
	} else {
		$('body').css('zoom', scaleFactor);
	}
}
*/

function applyScale() {
	$('body').css('zoom', '1');
	var scaleFactor = 1, scaleFactorX = 1, scaleFactorY = 1;
	var width, height;

	if ($('#game:visible').length) {

		if (isAndroid()) {
			var width = window.screen && window.screen.availWidth || $(window).width();
			var height = window.screen && window.screen.availHeight || $(window).height();
		} else if (isiOS()) {
			var width = $(window).width();
			var height = $('#body-repeat').height();
		} else {
			var width = $(window).width();
			var height = $(window).height();
		}

		//if (width < 1450) {
			scaleFactorX = width / 1450;
		//}

		//if (height < 1024) {
			scaleFactorY = height / 1024;
		//}

		scaleFactor = scaleFactorX < scaleFactorY ? scaleFactorX : scaleFactorY;

		if (scaleFactor < 0.1) {
			scaleFactor = 0.1;
		}

		$('body').css('zoom', scaleFactor);

	} else {

		var width = $(window).width();
		var height = $(window).height();

		if (width < 1450) {
			scaleFactorX = width / 1450;
		}

		if (height < 1024) {
			scaleFactorY = height / 1024;
		}

		scaleFactor = scaleFactorX < scaleFactorY ? scaleFactorX : scaleFactorY;

		if (scaleFactor < 0.1) {
			scaleFactor = 0.1;
		}

		$('body').css('zoom', scaleFactor);
	}
}

$(document).ready(function() {
	$(window).resize(function() {
		applyScale();
	});

	if (isiOS()) {
		$('#body-repeat').resize(function() {
			applyScale();
		});
	}

	applyScale();

	var gesture = function(e) {
		e.preventDefault();
		return false;
	};

	if (isiOS()) {
		document.getElementsByTagName('body')[0].addEventListener("touchmove", gesture, false);
		document.getElementById('body-repeat').addEventListener("touchmove", gesture, false);
		document.getElementById('body').addEventListener("touchmove", gesture, false);

		document.getElementsByTagName('body')[0].addEventListener("gesturechange", gesture, false);
		document.getElementById('body-repeat').addEventListener("gesturechange", gesture, false);
		document.getElementById('body').addEventListener("gesturechange", gesture, false);
	}
});