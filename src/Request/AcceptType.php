<?php
namespace HTTP\Request;

class AcceptType extends RequestHeader {

	public static function getDefault() {
		return array('text/html','application/xhtml+xml','application/xml', 'application/json','text/plain');
	}

	public static function getHeader() {
		return static::contentPriority(empty($_SERVER['HTTP_ACCEPT']) ? null : $_SERVER['HTTP_ACCEPT']);
	}
}
