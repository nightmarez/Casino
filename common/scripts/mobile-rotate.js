window.phoneBlockRotationAngle = 0;
window.continuePhoneBlockRotate = false;
window.mobilePhonePaused = false;

function rotatePhoneBlock(callback) {
	window.phoneBlockRotationAngle -= 22.5;

	if (window.phoneBlockRotationAngle < -90.5) {
		window.phoneBlockRotationAngle = 0;
	}

	$('#mobile-rotate').css('transform', 'rotate(' + window.phoneBlockRotationAngle + 'deg)');

	if (window.continuePhoneBlockRotate) {
		setTimeout(rotatePhoneBlock, 300);
	} else {
		callback && callback();
	}
}

function tryShowRotationBlock(callback) {
	if (isVerticalPosition()) {
		$('#mobile-rotate-background').show();

		if ($('#game:visible').length) {
			$('#body-repeat').hide();
			pauseAllSounds();
			window.mobilePhonePaused = true;
		} else if ($('#lobby:visible').length) {

		}		

		if (!window.continuePhoneBlockRotate) {
			window.continuePhoneBlockRotate = true;
			rotatePhoneBlock(callback);
		}
	} else {
		if ($('#game:visible').length) {
			window.mobilePhonePaused = false;
			resumeAllSounds();
			window.continuePhoneBlockRotate = false;
			$('#body-repeat').show();
		} else if ($('#lobby:visible').length) {

		}
		
		$('#mobile-rotate-background').hide();
		$('body,html').animate({
			scrollTop: 0
		});
	}
}

$(document).ready(function() {
	if (isMobile()) {
		$(window).resize(function() {
			tryShowRotationBlock();
		});

		var gestureChange = function(e) {
			e.preventDefault();
		};

		$('#mobile-rotate-background').css('z-index', '10000');

		document.getElementById('mobile-rotate').addEventListener("gesturechange", gestureChange, false);
		document.getElementById('mobile-rotate-fixed').addEventListener("gesturechange", gestureChange, false);
		document.getElementById('mobile-rotate-outer').addEventListener("gesturechange", gestureChange, false);
		document.getElementById('mobile-rotate-background').addEventListener("gesturechange", gestureChange, false);
	}
});