<?php
namespace HTTP\Response;

class AllowHeaders extends Header {

	public function getName() {
		return 'ACCESS_CONTROL_ALLOW_HEADERS';
	}
}
