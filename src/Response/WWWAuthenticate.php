<?php
namespace HTTP\Response;

class WWWAuthenticate extends Header {

	public $auth = [];
	public $type = 'Basic';

	/**
	 * Set the authentication type.
	 * Default is Basic
	 * @param String $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	public function auth($key, $value) {
		$this->auth[$key] = $value;
	}

	public function getName() {
		return 'WWW-Authenticate';
	}

	protected function setDefaults() {
		$this->values[] = $this->type;
	}
}
