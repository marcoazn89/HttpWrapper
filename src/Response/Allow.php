<?php
namespace HTTP\Response;

class Allow extends Header {

	const GET = 'get';
	const POST = 'post';
	const PUT = 'put';
	const PATCH = 'patch';
	const OPTIONS = 'options';
	const HEAD = 'head';

	public function getName() {
		return 'Allow';
	}

	protected function setDefaults() {
		$this->values[] = self::OPTIONS;
	}
}
