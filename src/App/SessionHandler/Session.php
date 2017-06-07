<?php
namespace Valoon\App\Framework;
class Session {
	public static function startSession() {
		session_start();
		if (!self::lockSession()) {
			self::destroySession();
			redirect('/');
		}
	}
	public static function destroySession() {
		$_SESSION = array();
		$params = session_get_cookie_params();
		setcookie(
			session_name(),
			'',
			time() - 42000,
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);
		session_destroy();
	}
	public static function regenerate($deleteOldSession = true) {
		return session_regenerate_id($deleteOldSession);
	}
	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	public static function destroy($key) {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}
	public static function get($key, $default = null) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		return $default;
	}
	private static function lockSession() {
		if (is_null(self::get('userIp')) || is_null(self::get('userAgent'))) {
			$_SESSION = array();
			self::set('userIp', $_SERVER['REMOTE_ADDR']);
			self::set('userAgent', $_SERVER['HTTP_USER_AGENT']);
			return true;
		} elseif (!equals(self::get('userIp'), $_SERVER['REMOTE_ADDR']) || !equals(self::get('userAgent'), $_SERVER['HTTP_USER_AGENT'])) {
			return false;
		} else {
			return true;
		}
	}
}
?>
