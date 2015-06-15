<?php
namespace HTTP;

require '../vendor/autoload.php';

class Message implements \Psr\Http\Message\ResponseInterface {
	private static $headers = array();
	private static $body = '';

	public static function status($code, $init = false) {
		if($init) {
			Response\Status::getInstance()->set($code, $init);
		}
		else {
			$status = Response\Status::getInstance();
			$status->set($code, $init);
			self::$headers['status'] = $status;
		}
	}

	public function getProtocolVersion() {

	}

	public function withProtocolVersion($version) {

	}

	public function getHeaders() {

	}

	public function hasHeader($name) {

	}

	public function getHeader($name) {

	}

	public function getHeaderLine($name) {

	}

	public function withHeader($name, $value) {

	}

	public function withAddedHeader($name, $value) {

	}

	public function withoutHeader($name) {

	}

	public function getBody() {

	}

	public function withBody(\Psr\Http\Message\StreamInterface $body) {

	}

	public function getStatusCode() {
		return $this->headers['status']->code;
	}

	public function withStatus($code, $reasonPhrase = '') {

	}

	public function getReasonPhrase() {
		return $this->headers['status']->message;

	}

	/*public static function contentType($contentType, $init = false) {
		if($init) {
			Response\ContentType::getInstance()->set($contentType, $init);
		}
		else {
			$content = Response\ContentType::getInstance();
			$content->set($contentType, $init);
			self::$headers['type'] = $content;
		}
	}

	public static function negotiateContentType($strongNegotiation = false) {
		$negotiation = ContentNegotiation::negotiate(AcceptType::getContent(), TypeSupport::getSupport());

		$content = '';

		if(count($negotiation) > 0) {
			$content = $negotiation[0];
		}
		else {
			if($strongNegotiation) {
				self::fail();
			}
			else {
				$content = TypeSupport::getSupport();
				$content = $content[0];
			}
		}

		return $content;
	}

	public static function fail($statusCode) {
		$supported = TypeSupport::getSupport();
		$clientSupport = AcceptType::getContent();

		$supported = implode(',', $supported);
		$clientSupport = implode(',',$clientSupport);

		self::status(406);
		self::contentType(ContentType::TEXT);
		self::body("NOT SUPPORTED\nThis server does not support {$supported}.\nSupported formats: {$clientSupport}");
		self::sendResponse();
	}

	public static function language($language, $init = false) {
		if($init) {
			Language::getInstance()->set($language, $init);
		}
		else {
			$lang = Language::getInstance();
			$lang->set($language, $init);
			self::$headers['language'] = $lang;
		}
	}

	public static function authenticate($auth = array(), $init = false) {
		if($init) {
			WWWAuthenticate::getInstance()->set($auth, $init);
		}
		else {
			$authenticate = WWWAuthenticate::getInstance();
			$authenticate->set($auth, $init);
			self::$headers['authenticate'] = $authenticate;
		}
	}

	public static function cache($cache = null, $init = false) {
		if($init) {
			CacheControl::getInstance()->set($cache, $init);
		}
		else {
			$c = CacheControl::getInstance();
			$c->set($cache, $init);
			self::$headers['cache'] = $c;
		}
	}

	public static function body($content) {
		self::$body = $content;
	}

	public static function sendResponse() {
		foreach(self::$headers as $k => $header) {
			$header->sendHeader();
		}

		if( ! empty(self::$body)) {
			echo self::$body;
		}
	}*/
}
