<?php
namespace HTTP\Response;

class AllowOrigin extends Header {

	public function getName() {
		return 'ACCESS_CONTROL_ALLOW_ORIGIN';
	}
}
