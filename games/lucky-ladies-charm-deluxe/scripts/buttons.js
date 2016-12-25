// More info button
$(document).ready(function() {
	$('#button-more-info').off('click');
	$('#button-more-info').on('click', function() {
		var page0 = $('#paytable-outer');
		var page1 = $('#rules-outer-1');
		var page2 = $('#rules-outer-2');

		if (page0.css('display') !== 'none') {
			page0.hide();
			page1.show();
		} else if (page1.css('display') !== 'none') {
			page1.hide();
			page2.show();
		}  else if (page2.css('display') !== 'none') {
			page2.hide();
			page0.show();
		}
	});
});