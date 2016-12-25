function initButtons() {
	////////////////////////// Top Menu Buttons //////////////////////////

	// Exit
	$('#top-menu-exit-button').off('click');
	$('#top-menu-exit-button').on('click', function() {

		$('#top-menu-exit-button').off('click');

		if (window.reelsRunned) {
			$('#button-autoplay').style('background-position', '0px 0px', 'important');
			$('#button-paytable').style('background-position', '0px 0px', 'important');
			$('#button-gamble').style('background-position', '0px 0px', 'important');
			$('#button-start').style('background-position', '0px 0px', 'important');

			blockBetButtons();
			window.needExit = true;
			window.stopAutoplay = true;
			window.autoplay = false;
			forceReelsStop();

			var waitStop = function() {
				if (!window.reelsRunned && window.gameComplete) {
					unload(function() {
						unloadGame();
						location.href = '#!';
					});
				} else {
					setTimeout(waitStop, 100);
				}
			};

			waitStop();
		} else {
			unload(function() {
				unloadGame();
				location.href = '#!';
			});
		}

		return false;
	});

	// if user pressed "back" button in browser
	window.onhashchange = function() {
		if (location.href.indexOf('#!') != -1 && !location.href.split('#!')[1].length && window.data ||
			location.href.indexOf('/lobby/') != -1 && location.href.split('#!').length == 1 && window.data) {

			$('#top-menu-exit-button').click();
			document.title = 'Lobby';
			showLobby();
		}
	}

	// Help
	$('#top-menu-help-button').off('click');
	$('#top-menu-help-button').on('click', function() {


		return false;
	});

	// Top Up
	$('#top-menu-topup-button').off('click');
	$('#top-menu-topup-button').on('click', function() {


		return false;
	});


	// Pay In
	$('#top-menu-payin-button').off('click');
	$('#top-menu-payin-button').on('click', function() {


		return false;
	});

	// Music On/Off
	if (!isMusicEnabled()) {
		$('#top-menu-music-button').addClass('checked');
	} else {
		$('#top-menu-music-button').removeClass('checked');
	}

	$('#top-menu-music-button').off('click');
	$('#top-menu-music-button').on('click', function() {
		if (isSoundEnabled()) {
			if (isMusicEnabled()) {
				disableMusic();
				$(this).addClass('checked');
			} else {
				enableMusic();
				$(this).removeClass('checked');
			}
		}

		return false;
	});

	// Sound On/Off
	if (!isSoundEnabled()) {
		$('#top-menu-sound-button').addClass('checked');
	} else {
		$('#top-menu-sound-button').removeClass('checked');
	}

	$('#top-menu-sound-button').off('click');
	$('#top-menu-sound-button').on('click', function() {
		if (isSoundEnabled()) {
			disableSound();
			$(this).addClass('checked');
			$('#top-menu-music-button').css('filter', 'grayscale(50%)').css('-webkit-filter', 'grayscale(50%)').css('opacity', '0.5');
		} else {
			enableSound();
			$(this).removeClass('checked');
			$('#top-menu-music-button').css('filter', 'none').css('-webkit-filter', 'none').css('opacity', '1');
		}

		return false;
	});

	// Fullscreen button
	/*if (isiOS()) {
		//if (location.href[location.href.length - 1] != '#!') {
			$('#top-menu-fullscreenbutton').css('background-image', 'url("../../common/imgs/icon-add-to-home.png")').css('width', '30px').css('height', '30px');

			$('#top-menu-fullscreenbutton').off('click');
			$('#top-menu-fullscreenbutton').on('click', function() {
				addToHomescreen({
				    skipFirstVisit: false,	    // show at first access
				    startDelay: 0,              // display the message right away
				    lifespan: 0,                // do not automatically kill the call out
				    displayPace: 0,             // do not obey the display pace
				    privateModeOverride: true,	// show the message in private mode
				    maxDisplayCount: 0          // do not obey the max display count
				});
			});
		//} else {
		//	$('#top-menu-fullscreenbutton').hide();
		//}		
	} else*/

	if (isMobile()) {
		$('#top-menu-fullscreenbutton').hide();
	} else {
		$('#top-menu-fullscreenbutton').off('click');
		$('#top-menu-fullscreenbutton').on('click', function() {
			if ($(this).hasClass('checked')) {
				$(this).removeClass('checked');

				if (isSoundEnabled()) {
					playSoundById('overlay-close');
				}

				if(document.exitFullscreen) {
					document.exitFullscreen();
				} else if(document.mozCancelFullScreen) {
					document.mozCancelFullScreen();
				} else if(document.webkitExitFullscreen) {
					document.webkitExitFullscreen();
				}
			} else {
				$(this).addClass('checked');
				var body = document.getElementsByTagName('html')[0];

				if (isSoundEnabled()) {
					playSoundById('overlay-open');
				}

				if(body.requestFullscreen) {
					body.requestFullscreen();
				} else if(body.mozRequestFullScreen) {
					body.mozRequestFullScreen();
				} else if(body.webkitRequestFullscreen) {
					body.webkitRequestFullscreen();
				} else if(body.msRequestFullscreen) {
					body.msRequestFullscreen();
				}
			}

			applyScale();
			return false;
		});
	}

	//////////////////////// Bottom Menu Buttons /////////////////////////

	// Autoplay
	$('#button-autoplay').off('click');
	$('#button-autoplay').on('click', function() {
		if ($(this).css('background-position').trim().indexOf('0px 0px') != -1) {
			return false;
		}

		$('#paytable-content').slideUp('fast');
		$('#bookblock-outer').hide();
		$('#rules-outer').hide();
		$('.rules-outer').hide();

		$('#button-more-info').hide();
		$('#button-gamble').show();

		if (window.autoplay) {
			window.autoplay = false;
			$('#button-autoplay').style('background-position', '0px 0px', 'important');
			$('#button-autoplay').text('Autoplay');
			$('#button-paytable').style('background-position', '0px 0px', 'important');
			$('#button-gamble').style('background-position', '0px 0px', 'important');
		} else {
			$('#button-autoplay').style('background-position', '0 -222', 'nonimportant');
			$('#button-autoplay').text('Stop');
			$('#button-paytable').style('background-position', '0px 0px', 'important');
			$('#button-gamble').style('background-position', '0px 0px', 'important');
			window.autoplay = true;
			window.stopAutoplay = false;
			$('#button-start').style('background-position', '0 -222px', 'nonimportant');
			$('#button-start').addClass('button-skip');

			$('#button-paytable').style('background-position', '0px 0px', 'important');
			$('#button-gamble').style('background-position', '0px 0px', 'important');

			var autoplay = function() {
				play(function() {
					blockBetButtons();
					stopSoundById('reel-run-sound');

					$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
					$('#button-paytable').style('background-position', '0px 0px', 'important');
					$('#button-gamble').style('background-position', '0px 0px', 'important');
					$('#button-start').style('background-position', '0 -222px', 'nonimportant');
					$('#button-start').text('Start');
				}, function() {
					if (window.needCollect) {
						transferWin(function() {
							window.needCollect = false;
							$('.symbol-video').remove();

							var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
							if (totalBet > getCredit()) {
								$('#button-start').style('background-position', '0px 0px', 'important');
								$('#button-autoplay').style('background-position', '0px 0px', 'important');
							} else {
								$('#button-start').style('background-position', '0 -222px', 'nonimportant');
								$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
							}

							if (totalBet <= getCredit()) {
								setTimeout(function() {
									if (!window.stopAutoplay && window.autoplay && !window.needExit) {
										autoplay();
									}
								}, 300);
							} else {
								unblockBetButtons();
								window.stopAutoplay = true;
								window.autoplay = false;
							}

							$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
							window.gameComplete = true;
							//unblockBetButtons();
						});
					} else {
						//unblockBetButtons();
						var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
						if (totalBet > getCredit()) {
							$('#button-start').style('background-position', '0px 0px', 'important');
							$('#button-autoplay').style('background-position', '0px 0px', 'important');
						} else {
							$('#button-start').style('background-position', '0 -222px', 'nonimportant');
							$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
						}

						if (totalBet <= getCredit()) {
							setTimeout(function() {
								if (!window.stopAutoplay && window.autoplay && !window.needExit) {
									autoplay();
								}
							}, 300);
						} else {
							unblockBetButtons();
							window.stopAutoplay = true;
							window.autoplay = false;
						}

						$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
						window.gameComplete = true;
					}

					if (!window.autoplay && !window.needExit && !window.gameComplete) {
						unblockBetButtons();
						stopSoundById('reel-run-sound');

						var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
						if (totalBet > getCredit()) {
							$('#button-autoplay').text('Autoplay');
							$('#button-autoplay').style('background-position', '0px 0px', 'important');
							$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
							$('#button-gamble').style('background-position', '0px 0px', 'important');
							$('#button-start').style('background-position', '0px 0px', 'important');
							$('#button-start').text('Start');
							$('#button-start').removeClass('button-skip');
						} else {
							$('#button-autoplay').text('Autoplay');
							$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
							$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
							$('#button-gamble').style('background-position', '0px 0px', 'important');
							$('#button-start').style('background-position', '0 -222px', 'nonimportant');
							$('#button-start').text('Start');
							$('#button-start').removeClass('button-skip');
						}
					}
				});
			};

			if (!window.stopAutoplay && window.autoplay && !window.needExit) {
				autoplay();
			}
		}					
	});

	// Paytable
	$('#button-paytable').off('click');
	$('#button-paytable').on('click', function() {
		if ($(this).css('background-position').trim().indexOf('0px 0px') != -1) {
			return false;
		}

		$('#bookblock-outer').hide();
		$('.rules-outer').hide();

		if ($('#paytable-content').css('display') == 'none') {
			correctPayTable();
			playSoundById('overlay-open');
			$('#paytable-content').show();
			$('#paytable-content').slideDown('fast');
			$('#button-more-info').show();
			$('#button-gamble').hide();
		} else {
			playSoundById('overlay-close');
			$('#paytable-content').slideUp('fast');
			$('#button-more-info').hide();
			$('#button-gamble').show();
		}		
	});

	// Gamble
	$('#button-gamble').css('background-position', '0px 0px');
	$('#button-gamble').off('click');
	$('#button-gamble').on('click', function() {
		if ($(this).css('background-position').trim().indexOf('0px 0px') != -1) {
			return false;
		}

		$('#bookblock-outer').hide();
		$('#rules-outer').hide()
		$('.rules-outer').hide();

		$('#paytable-content').slideUp('fast');
		openGamble();
	});

	// Start
	$('#button-start').off('click');
	$('#button-start').on('click', function() {
		if ($(this).css('background-position').trim().indexOf('0px 0px') != -1) {
			return false;
		}

		if (window.inGambleState) {
			$('#lastWinField').text(window.totalWin);
			
			transferWin(function() {
				$('.symbol-video').remove();
			});

			hideGamble();
			return;
		}
					
		$('#paytable-content').slideUp('fast');
		$('#rules-outer').hide();
		$('.rules-outer').hide();
		$('#bookblock-outer').hide();

		$('#button-more-info').hide();
		$('#button-gamble').show();

		if (window.reelsRunned || window.autoplay) {
			$('#button-autoplay').style('background-position', '0px 0px', 'important');
			$('#button-paytable').style('background-position', '0px 0px', 'important');
			$('#button-gamble').style('background-position', '0px 0px', 'important');
			$('#button-start').style('background-position', '0px 0px', 'important');

			// force reels stop
			forceReelsStop();
			return;
		}

		if (window.needCollect) {
			transferWin(function() {
				hideGamble();

				$('.symbol-video').remove();
				$('#label-info').find('span').text('Please place your bet');

				$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
				$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
				$('#button-gamble').style('background-position', '0 -222px', 'nonimportant');
				$('#button-start').style('background-position', '0 -222px', 'nonimportant');

				$('#button-start').text('Start');
				$('#button-autoplay').text('Autoplay');

				window.gameComplete = true;
			});
		} else {
			if (window.needExit) {
				window.gameComplete = true;
				return;
			}

			$('#button-start').text('Start');

			if (window.specGame) {
				$('#label-info > span').text('Bonus Games: ' + window.specGamesCount);

				if (!--window.specGamesCount) {
					if (window.gameConfig.endSpecialGame) {
						window.gameConfig.endSpecialGame();
					}
				}
			} else {
				$('#label-info').find('span').text('Please place your bet');
			}

			play(function() {
				blockBetButtons();
				stopSoundById('reel-run-sound');

				$('#button-autoplay').style('background-position', '0px 0px', 'important');
				$('#button-paytable').style('background-position', '0px 0px', 'important');
				$('#button-gamble').style('background-position', '0px 0px', 'important');
				$('#button-start').style('background-position', '0 -222px', 'nonimportant');
				$('#button-start').text('Stop');
			}, function() {
				if (window.needCollect) {
					$('#button-autoplay').style('background-position', '0px 0px', 'important');
					$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
					$('#button-gamble').style('background-position', '0 -222px', 'nonimportant');
					$('#button-start').style('background-position', '0 -222px', 'nonimportant');
					$('#button-start').text('Collect');
				} else {
					$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
					$('#button-paytable').style('background-position', '0 -222px', 'nonimportant');
					$('#button-gamble').style('background-position', '0px 0px', 'important');
					$('#button-start').style('background-position', '0 -222px', 'nonimportant');
					$('#button-start').text('Start');
				}

				unblockBetButtons();
				var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
				if (totalBet > getCredit()) {
					$('#button-start').style('background-position', '0px 0px', 'important');
					$('#button-autoplay').style('background-position', '0px 0px', 'important');
				} else {
					$('#button-start').style('background-position', '0 -222px', 'nonimportant');
					$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
				}

				//window.gameComplete = true;
			});
		}
	});

	///////////////////////////// Bet Buttons ////////////////////////////

	function blockBetButtons() {
		$('.b-plus').addClass('betLineButtonTemporaryDisabled');
	}

	function unblockBetButtons() {
		$('.b-plus').removeClass('betLineButtonTemporaryDisabled');
	}

	var minLineBet = window.gameConfig.minLineBet || 1;
	var maxLineBet = window.gameConfig.maxLineBet || 5000;

	var minLinesCount = window.gameConfig.minLines || 1;
	var maxLinesCount = window.gameConfig.maxLines || Math.floor($('.line-indicator').length / 2);

	$('#linesCountField').text(maxLinesCount);
	$('#betLineField').text(minLineBet || 1);
	$('#betLineMinus').addClass('betLineButtonDisabled');
	var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
	$('#betField').text(totalBet);;

	if (parseInt($('#betLineField').text()) <= minLineBet) {
		parseInt($('#betLineField').text(minLineBet));
		$('#betLineMinus').addClass('betLineButtonDisabled');
	}

	$('#betLineMinus').off('click');
	$('#betLineMinus').on('click', function() {
		if ($(this).hasClass('betLineButtonDisabled')) {
			return;
		}

		if ($(this).hasClass('betLineButtonTemporaryDisabled')) {
			return;
		}

		if (isSoundEnabled()) {
			playSoundById('changebet-sound');
		};

		window.stopLinesAnimation = true;
		removeAllWinBorders();

		$('#betLinePlus').removeClass('betLineButtonDisabled');
		var value = parseInt($('#betLineField').text());
		
		if (value <= minLineBet) {
			value = minLineBet;
			$('#betLineMinus').addClass('betLineButtonDisabled');
		} else {
			if (value >= 100000) {
				value -= 8000;
			} else if (value >= 20000) {
				value -= 2000;
			} else if (value >= 4000) {
				value -= 500;
			} else if (value >= 1000) {
				value -= 200;
			} else if (value >= 100) {
				value -= 80;
			} else if (value > 50) {
				value -= 20;
			} else if (value > 10) {
				value -= 5;
			} else if (value > 0) {
				value -= 5;
			} else {
				value -= 20000;
			}

			if (value <= minLineBet) {
				value = minLineBet;
				$('#betLineMinus').addClass('betLineButtonDisabled');
			} else {
				$('#betLineMinus').removeClass('betLineButtonDisabled');
			}

			if (value >= maxLineBet) {
				value = maxLineBet;
				$('#betLinePlus').addClass('betLineButtonDisabled');
			} else {
				$('#betLinePlus').removeClass('betLineButtonDisabled');
			}
		}

		$('#betLineField').text(value);
		var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
		$('#betField').text(totalBet);

		if (totalBet > getCredit()) {
			$('#button-start').style('background-position', '0 0', 'important');
			$('#button-autoplay').style('background-position', '0 0', 'important');
		} else {
			$('#button-start').style('background-position', '0 -222px', 'nonimportant');
			$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
		}

		correctPayTable();
		showAllLines();
	});

	if (parseInt($('#betLineField').text()) >= maxLineBet) {
		parseInt($('#betLineField').text(maxLineBet));
		$('#betLinePlus').addClass('betLineButtonDisabled');
	}

	$('#betLinePlus').off('click');
	$('#betLinePlus').on('click', function() {
		if ($(this).hasClass('betLineButtonDisabled')) {
			return;
		}

		if ($(this).hasClass('betLineButtonTemporaryDisabled')) {
			return;
		}

		if (isSoundEnabled()) {
			playSoundById('changebet-sound');
		}

		window.stopLinesAnimation = true;
		removeAllWinBorders();

		$('#betLineMinus').removeClass('betLineButtonDisabled');
		var value = parseInt($('#betLineField').text());

		if (value >= maxLineBet) {
			value = maxLineBet;
			$('#betLinePlus').addClass('betLineButtonDisabled');
		} else {
			if (value >= 100000) {
				value += 8000;
			} else if (value >= 20000) {
				value += 2000;
			} else if (value >= 4000) {
				value += 500;
			} else if (value >= 1000) {
				value += 200;
			} else if (value >= 100) {
				value += 80;
			} else if (value > 50) {
				value += 20;
			} else if (value > 10) {
				value += 5;
			} else if (value > 0) {
				value += 2;
			} else {
				value += 20000;
			}

			if (value <= minLineBet) {
				value = minLineBet;
				$('#betLineMinus').addClass('betLineButtonDisabled');
			} else {
				$('#betLineMinus').removeClass('betLineButtonDisabled');
			}

			if (value >= maxLineBet) {
				value = maxLineBet;
				$('#betLinePlus').addClass('betLineButtonDisabled');
			} else {
				$('#betLinePlus').removeClass('betLineButtonDisabled');
			}
		}

		$('#betLineField').text(value);
		var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
		$('#betField').text(totalBet);

		if (totalBet > getCredit()) {
			$('#button-start').style('background-position', '0 0', 'important');
			$('#button-autoplay').style('background-position', '0 0', 'important');
		} else {
			$('#button-start').style('background-position', '0 -222px', 'nonimportant');
			$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
		}

		correctPayTable();
		showAllLines();
	});

	function correctLineIndicatorsCount() {
		var linesCount = parseInt($('#linesCountField').text());
		$('.line-indicator').css('display', 'none');

		for (var j = 1; j <= linesCount; ++j) {
			$('#line-' + j + '-indicator-left').css('display', 'block');
			$('#line-' + j + '-indicator-right').css('display', 'block');
		}
	}

	if (parseInt($('#linesCountField').text()) <= minLinesCount) {
		$('#linesCountField').text(minLinesCount);
		$('#lineMinus').addClass('betLineButtonDisabled');
	}

	$('#lineMinus').off('click');
	$('#lineMinus').on('click', function() {
		if ($(this).hasClass('betLineButtonDisabled')) {
			return;
		}

		if ($(this).hasClass('betLineButtonTemporaryDisabled')) {
			return;
		}

		window.stopLinesAnimation = true;
		removeAllWinBorders();

		var linesCount = parseInt($('#linesCountField').text());

		if (linesCount > minLinesCount) {
			$('#linesCountField').text(linesCount - 1);

			if (isSoundEnabled()) {
				playSoundById('changebet-sound');
			}
		}

		var linesCount = parseInt($('#linesCountField').text());

		if (linesCount == minLinesCount) {
			$(this).addClass('betLineButtonDisabled');
		} else {
			$(this).removeClass('betLineButtonDisabled');
		}

		if (linesCount == maxLinesCount) {
			$('#linePlus').addClass('betLineButtonDisabled');
		} else {
			$('#linePlus').removeClass('betLineButtonDisabled');
		}

		var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
		$('#betField').text(totalBet);

		if (totalBet > getCredit()) {
			$('#button-start').style('background-position', '0 0', 'important');
			$('#button-autoplay').style('background-position', '0 0', 'important');
		} else {
			$('#button-start').style('background-position', '0 -222px', 'nonimportant');
			$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
		}

		correctLineIndicatorsCount();
		correctPayTable();
		showAllLines();
	});

	if (parseInt($('#linesCountField').text()) >= maxLinesCount) {
		$('#linesCountField').text(maxLinesCount);
		$('#linePlus').addClass('betLineButtonDisabled');
	}

	$('#linePlus').off('click');
	$('#linePlus').on('click', function() {
		if ($(this).hasClass('betLineButtonDisabled')) {
			return;
		}

		if ($(this).hasClass('betLineButtonTemporaryDisabled')) {
			return;
		}

		window.stopLinesAnimation = true;
		removeAllWinBorders();

		var linesCount = parseInt($('#linesCountField').text());

		if (linesCount < maxLinesCount) {
			$('#linesCountField').text(linesCount + 1);

			if (isSoundEnabled()) {
				playSoundById('changebet-sound');
			}
		}

		var linesCount = parseInt($('#linesCountField').text());

		if (linesCount == minLinesCount) {
			$('#lineMinus').addClass('betLineButtonDisabled');
		} else {
			$('#lineMinus').removeClass('betLineButtonDisabled');
		}

		if (linesCount == maxLinesCount) {
			$(this).addClass('betLineButtonDisabled');
		} else {
			$(this).removeClass('betLineButtonDisabled');
		}

		var totalBet = parseInt($('#linesCountField').text()) * parseInt($('#betLineField').text());
		$('#betField').text(totalBet);

		if (totalBet > getCredit()) {
			$('#button-start').style('background-position', '0 0', 'important');
			$('#button-autoplay').style('background-position', '0 0', 'important');
		} else {
			$('#button-start').style('background-position', '0 -222px', 'nonimportant');
			$('#button-autoplay').style('background-position', '0 -222px', 'nonimportant');
		}

		correctLineIndicatorsCount();
		correctPayTable();
		showAllLines();
	});

	/////////////////////////// Gamble Buttons ///////////////////////////

	$('#big-red-button').off('click');
	$('#big-red-button').on('click', function() {
		redGambleClick();
	});

	$('#big-black-button').off('click');
	$('#big-black-button').on('click', function() {
		blackGambleClick();
	});
}