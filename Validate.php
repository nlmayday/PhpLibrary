<?php
class Validate
{
	public static function int(&$int, $default = 0, $min = 0, $max = PHP_INT_MAX)
	{
		return isset($int) ? filter_var($int, FILTER_VALIDATE_INT, array('options' => array('default' => $default, 'min_range' => $min, 'max_range' => $max))) : $default;
	}
	
	public static function float(&$float, $default = 0, $min = 0, $max = PHP_INT_MAX, $decimal = '.')
	{
		return isset($float) && $float >= $min && $float <= $max ? filter_var($float, FILTER_VALIDATE_FLOAT, array('options' => array('default' => $default, 'decimal' => $decimal))) : $default;
	}
	
	public static function bool(&$bool, $default = false)
	{
		return isset($bool) ? filter_var($bool, FILTER_VALIDATE_BOOLEAN, array('options' => array('default' => $default))) : $default;
	}

	public static function md5(&$md5, $default = false)
	{
		return isset($md5) && preg_match('/^[a-f0-9A-F]{32}$/', $md5) ? $md5 : $default;
	}
	
	public static function nl2br(&$str){
		return isset($str) ? str_replace(array("\r\n", "\r", "\n"), '<br />', $str) : '';
	}
	
	public static function ip2long(&$ip){
		return sprintf('%u', ip2long($ip));
	}
	
	public static function crc32(&$url){
		return sprintf('%u', crc32($url));
	}
	
	public static function mb_substr(&$str, $length, $encoding = 'utf-8')
	{
		return isset($str) ? mb_substr($str, 0, $length, $encoding).($length < mb_strlen($str, $encoding) ? '...' : '') : '';
	}
	
	public static function strip_tags(&$str, $default = false){
	    return isset($str) ? filter_var($str, FILTER_SANITIZE_STRING, array('options' => array('default' => $default))) : $default;
	}
	
	public static function url(&$url, $default = false, $flags = FILTER_FLAG_SCHEME_REQUIRED|FILTER_FLAG_HOST_REQUIRED|FILTER_FLAG_PATH_REQUIRED|FILTER_FLAG_QUERY_REQUIRED)
	{
		return isset($url) ? filter_var($url, FILTER_VALIDATE_URL, array('options' => array('default' => $default), 'flags' => $flags)) : $default;
	}
	
	public static function email(&$email, $default = false)
	{
		return isset($email) ? filter_var($email, FILTER_VALIDATE_EMAIL, array('options' => array('default' => $default))) : $default;
	}

	public static function order(&$order, $default = 'desc')
	{
		return isset($order) && $order === 'asc' ? $order : $default;
	}
}
