function stopSoundById(id) {
	if (!isSoundEnabled()) {
		return;
	}

	var sound = window.data['sounds'][id];
	sound.volume(0);
	sound.stop();
}

function playSoundById(id) {
	if (!isSoundEnabled()) {
		return;
	}

	var sound = window.data['sounds'][id];
	sound.stop();
	sound.volume(1);
	sound.play();
	return sound;
}

window.pausedSounds = [];

function pauseAllSounds() {
	if (!isSoundEnabled()) {
		return;
	}

	if (!window.data) {
		return;
	}

	for (var id in window.data['sounds']) {
		var sound = window.data['sounds'][id];

		if (sound.playing()) {
			sound.pause();
			window.pausedSounds.push(sound);
		}
	}
}

function stopAllSounds() {
	if (!isSoundEnabled()) {
		return;
	}

	if (!window.data) {
		return;
	}

	for (var id in window.data['sounds']) {
		var sound = window.data['sounds'][id];

		if (sound.playing()) {
			sound.volume(0);
			sound.stop();
		}
	}

	window.pausedSounds = [];
}

function resumeAllSounds() {
	if (!isSoundEnabled()) {
		return;
	}

	if (!window.data) {
		return;
	}

	_.each(window.pausedSounds, function(sound) {
		sound.play();
	});

	window.pausedSounds = [];
}

function isSoundEnabled() {
	var isSoundDisabled = false;
	_.each(document.cookie.split(';'), function(value) {
		if (value.indexOf('=') != -1) {
			var kvp = value.split('=');

			if (kvp[0].trim() == 'sound' && kvp[1].trim() == 'disabled') {
				isSoundDisabled = true;
			}
		}
	});

	return !isSoundDisabled;
}

function isMusicEnabled() {
	if (!isSoundEnabled()) {
		return false;
	}

	var isMusicDisabled = false;
	_.each(document.cookie.split(';'), function(value) {
		if (value.indexOf('=') != -1) {
			var kvp = value.split('=');

			if (kvp[0].trim() == 'music' && kvp[1].trim() == 'disabled') {
				isMusicDisabled = true;
			}
		}
	});

	return !isMusicDisabled;
}

function enableSound() {
	document.cookie = 'sound=enabled; expires=' + new Date(new Date().getTime() + 60 * 100000).toUTCString();
}

function disableSound() {
	stopAllSounds();
	document.cookie = 'sound=disabled; expires=' + new Date(new Date().getTime() + 60 * 100000).toUTCString();
}

function enableMusic() {
	document.cookie = 'music=enabled; expires=' + new Date(new Date().getTime() + 60 * 100000).toUTCString();
}

function disableMusic() {
	document.cookie = 'music=disabled; expires=' + new Date(new Date().getTime() + 60 * 100000).toUTCString();
}