<?php
	define('ok_client_id', '1239898368');
	define('ok_client_public', 'CBAKFIOKEBABABABA');
	define('ok_client_secret', '2D18F9AB55B644E86765CBCA');
	define('ok_redirect_uri', 'http://casino.nightmarez.net/ok-auth.php');

	function genOkAuthLink()
	{
		$url = 'http://www.odnoklassniki.ru/oauth/authorize';

		$params = array(
		    'client_id'     => ok_client_id,
		    'response_type' => 'code',
		    'redirect_uri'  => ok_redirect_uri
		);

		return $url . '?' . urldecode(http_build_query($params));
	}

	function getOkToken($code)
	{
		$params = array(
	        'code' => $code,
	        'redirect_uri' => ok_redirect_uri,
	        'grant_type' => 'authorization_code',
	        'client_id' => ok_client_id,
	        'client_secret' => ok_client_secret
	    );

	    $url = 'http://api.odnoklassniki.ru/oauth/token.do';
	    
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    $result = curl_exec($curl);
	    curl_close($curl);

	    $token = json_decode($result, true);
	    return $token;
	}
?>