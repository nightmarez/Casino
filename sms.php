<?php
	require_once('config/sms-config.php');

	function sendSms($to, $msg)
	{
		$body = file_get_contents('http://sms.ru/sms/send?api_id=' . SMS_API . '&to=' . $to . '&text=' . urlencode(iconv('windows-1251', 'utf-8', $msg)));
	}
?>