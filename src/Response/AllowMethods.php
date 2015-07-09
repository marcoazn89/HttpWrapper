<?php
namespace HTTP\Response;

class AllowMethods extends Header {

	public function getName() {
		return 'ACCESS_CONTROL_ALLOW_METHODS';
	}
}
