<?php
namespace HTTP\Request;

class AcceptLanguage extends RequestHeader {

	public static function getDefault() {
		return array('en-US','en','es');
	}

	public static function getHeader() {
		return static::contentPriority($_SERVER['HTTP_ACCEPT_LANGUAGE']);
	}
}
