<?php
	define('DB_NAME', 'dealer');
	define('DB_USER', 'root');

	if (strpos(gethostname(), 'nightmarez') !== false) {
		// for local testing
	    define('DB_PASS', 'Righoo3oophip7d@');
	} else {
		// for production server
		define('DB_PASS', 'Righoo3oophip7d@');
	}
	
	define('DB_SALT', '78fdsh4--3hdj');
?>