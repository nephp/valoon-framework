<?php
namespace App\Framework;
defined('APP') || (header('HTTP/1.1 403 Forbidden') & die('403.14 - Directory listing denied.'));
function redirect($url) {
	$isExternal = stripos($url, "http://") !== false || stripos($url, "https://") !== false;
	if (!$isExternal) {
		$url = rtrim(SCRIPT_URL, '/') . '/' . ltrim($url, '/');
	}
	if (!headers_sent()) {
		header('Location: ' . $url, true, 302);
	} else {
		echo '<script type="text/javascript">';
		echo 'window.location.href="' . e($url) . '";';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="0;url=' . e($url) . '" />';
		echo '</noscript>';
	}
	exit;
}
function equals($knownString, $userString) {
	$ret = 0;
	if (strlen($knownString) !== strlen($userString)) {
		$userString = $knownString;
		$ret = 1;
	}
	$res = $knownString ^ $userString;
	for ($i = strlen($res) - 1; $i >= 0; --$i) {
		$ret |= ord($res[$i]);
	}
	return !$ret;
}
function e($value) {
	return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
}
function respond(array $data, $statusCode = 200) {
    $response = new Response();
    $response->send($data, $statusCode);
}
function app($service = null) {
	$c = Container::getInstance();
	if (is_null($service)) {
		return $c;
	}
    return $c[$service];
}
?>
