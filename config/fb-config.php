<?php
	define('fb_client_id', '196553980693169');
	define('fb_client_secret', '23fab83ee8412cd4af6ee347ac4d2e28');
	define('fb_redirect_uri', 'http://casino.nightmarez.net/fb-auth.php');

	function genFbAuthLink()
	{
		$url = 'https://www.facebook.com/dialog/oauth';

		$params = array(
		    'client_id'     => fb_client_id,
		    'redirect_uri'  => fb_redirect_uri,
		    'response_type' => 'code',
		    'scope'         => 'email,user_birthday'
		);

		return $url . '?' . urldecode(http_build_query($params));
	}

	function getFbToken($code)
	{
		$params = array(
	        'client_id'     => fb_client_id,
	        'redirect_uri'  => fb_redirect_uri,
	        'client_secret' => fb_client_secret,
	        'code'          => $code
	    );

	    $url = 'https://graph.facebook.com/oauth/access_token';
	    $token = null;
		parse_str(file_get_contents($url . '?' . http_build_query($params)), $token);
		return $token;
	}
?>