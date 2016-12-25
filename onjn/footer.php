<footer>
	<script type="text/javascript">
		///////////////////////////////////////////////////// user search field /////////////////////////////////////////////////////
		window.disableDefaultSearch = true;
		
		$(document).ready(function() {
			var lastResult = [];

            var func = function (text, f) {
                $.get('listusers.php', function(result) {
                	result = JSON.parse(result);
                	lastResult = [];
                	var count = 0;

                	text = text.toLowerCase();
                    _.each(result, function (item) {
                    	if (lastResult.length > 10) {
                    		return;
                    	}

                        if (item[1].length >= text.length && text == item[1].substr(0, text.length).toLowerCase()) {
                            lastResult.push(item);
                        } 
                    });

                    //lastResult = lastResult.concat([[0, '...']]);
                	f(_.map(lastResult, function(r) { return r[1]; }));
                });
            };

            $('#search-area').searchPlugin(func, function (keyResult) {
            	location.href = 'user.php?id=' + lastResult[keyResult][0];
            });
		});
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	</script>
</footer>