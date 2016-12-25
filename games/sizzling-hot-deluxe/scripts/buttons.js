// More info button
$(document).ready(function() {
	$('#button-more-info').off('click');
	$('#button-more-info').on('click', function() {
		$('#paytable-outer').toggle();
		$('#rules-outer').toggle();
	});
});