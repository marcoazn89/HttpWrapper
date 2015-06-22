<?php
namespace HTTP\Support;

class TypeSupport extends ContentSupport {

	public static function getDefault() {
		return array('text/html','application/xhtml+xml','application/xml', 'text/plain', 'application/json');
	}
}
