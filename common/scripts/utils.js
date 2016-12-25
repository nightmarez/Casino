function isiOS() {
	return !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
}

function isAndroid() {
	return /Android/i.test(navigator.userAgent);
}

function isChrome() {
	return !isAndroid() && !!window.chrome && !/OPR/i.test(navigator.userAgent);
}

function isOpera() {
	return isDesktop() && /OPR/i.test(navigator.userAgent);
}

function isSafari() {
	return navigator.userAgent.toLowerCase().indexOf('safari/') > -1;
}

function isMobile() {
	return isiOS() || isAndroid() || /webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function isDesktop() {
	return !isMobile();
}

(function($) {    
	if ($.fn.style) {
		return;
	}

	var escape = function(text) {
		return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
	};

	var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue;

	if (!isStyleFuncSupported) {
		CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
			return this.getAttribute(a);
		};

		CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
			this.setAttribute(styleName, value);
			var priority = typeof priority != 'undefined' ? priority : '';
			
			if (priority != '') {
				var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) + '(\\s*;)?', 'gmi');
				this.cssText = this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';');
			}
		};

		CSSStyleDeclaration.prototype.removeProperty = function(a) {
			return this.removeAttribute(a);
		};

		CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
			var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?', 'gmi');
			return rule.test(this.cssText) ? 'important' : '';
		}
	}

	$.fn.style = function(styleName, value, priority) {
		var node = this.get(0);

		if (typeof node == 'undefined') {
			return this;
		}

		var style = this.get(0).style;

		if (typeof styleName != 'undefined') {
			if (typeof value != 'undefined') {
				priority = typeof priority != 'undefined' ? priority : '';
				style.removeProperty(styleName);
				style.setProperty(styleName, value, priority);
				return this;
			} else {
				return style.getPropertyValue(styleName);
			}
		} else {
			return style;
		}
	};
})(jQuery);

function randomInteger(min, max) {
	var rand = min + Math.random() * (max - min);
	rand = Math.round(rand);
	return rand;
}

function supportsLocalStorage() {
	try {
		return 'localStorage' in window && window['localStorage'] !== null;
	} catch (e) {
		return false;
	}
}

Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };

function GUID() {
	function s4() {
    	return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }

	return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}