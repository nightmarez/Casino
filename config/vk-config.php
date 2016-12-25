<?php
	define('vk_client_id', '5519267');
	define('vk_client_secret', '68127JoRY5PtrHYuAn12');
	define('vk_redirect_uri', 'http://casino.nightmarez.net/vk-auth.php');

	function genVkAuthLink()
	{
		$url = 'http://oauth.vk.com/authorize';

		$params = array(
		    'client_id'     => vk_client_id,
		    'redirect_uri'  => vk_redirect_uri,
		    'response_type' => 'code'
		);

		return $url . '?' . urldecode(http_build_query($params));
	}

	function getVkToken($code)
	{
		$params = array(
	        'client_id' => vk_client_id,
	        'client_secret' => vk_client_secret,
	        'code' => $code,
	        'redirect_uri' => vk_redirect_uri
	    );

	    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
	    return $token;
	}
?>
