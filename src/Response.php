<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/codeguy/Slim
 * @copyright Copyright (c) 2011-2015 Josh Lockhart
 * @license   https://github.com/codeguy/Slim/blob/master/LICENSE (MIT License)
 */

namespace HTTP;

class Response implements \Psr\Http\Message\ResponseInterface, Response\ResponseHeaders {
	protected $status = null;
	protected $headers = array();
	protected $body;

	public function __construct() {
		$thisClass = new \ReflectionClass(__CLASS__);
		$allClasses = get_declared_classes();

		foreach($thisClass->getConstants() as $class) {
			$header = sprintf('HTTP\Response\%s',$class);

			if(in_array($header, $allClasses)) {
				$this->headers[$class] = $header::getInstance();
			}
		}

		if( ! array_key_exists('status', $this->headers)) {
			$this->status = Response\Status::getInstance()->set();
		}
	}

	public function status($code, $init = false) {
		$this->status = Response\Status::getInstance()->setCode($code);
	}

	public function protocol($protocol) {
		Response\Status::getInstance()->setProtocol($protocol);
	}

	public function message($message) {
		Response\Response\Status::getInstance()->setMessage($message);
	}

	public function getProtocolVersion() {
		return Response\Status::getInstance()->getProtocol();
	}

	public function withProtocolVersion($version) {
		Response\Status::getInstance()->setProtocol($version);

		return clone $this;
	}

	public function getHeaders() {
		$headers = [];
		foreach($this->headers as $key => $header) {
			$headers[$key] = $header->getValues();
		}
		return $headers;
	}

	public function hasHeader($name) {
		return isset($this->headers[$name]) ? true : false;
	}

	public function getHeader($name) {
		return isset($this->headers[$name]) ? $this->headers[$name]->getValues() : [];
	}

	public function getHeaderLine($name) {
		return isset($this->headers[$name]) ? $this->headers[$name]->getString() : '';
	}

	public function mapHeader($name) {
		switch(strtolower($name)) {
			case strtolower(self::CACHE_CONTROL):
				return Response\CacheControl::getInstance();
				break;
			case strtolower(self::CONTENT_LENGTH):
				return Response\ContentLength::getInstance();
				break;
			case strtolower(self::ContentType):
				return Response\ContentType::getInstance();
				break;
			default:
				die("shit");
		}
	}

	public function withHeader($name, $value) {
		$new = clone $this;
		$header = sprintf('HTTP\Response\%s',$name);

		$new->headers[$name] = $this->mapHeader($name)->set($value);

		return $new;
	}

	public function withAddedHeader($name, $value) {
		$new = clone $this;
		$header = sprintf('HTTP\Response\%s',$name);
		$header = $this->mapHeader($name);

		if( ! array_key_exists($name, $new->headers)) {
			$new->headers[$name] = $header;
		}

		$new->headers[$name]->add($value);

		return $new;
	}

	public function withoutHeader($name) {
		$new = clone $this;
		unset($new->headers[$name]);

		return $new;
	}

	public function getBody() {
		return $this->body;
	}

	public function withBody(\Psr\Http\Message\StreamInterface $body) {
		$new = clone $this;
		$new->body = $body;

		return $new;
	}

	public function getStatusCode() {
		return $this->status->getCode();
	}

	public function withStatus($code, $reasonPhrase = '') {
		$new = clone $this;
		$new->status->setCode($code);

		if( ! empty($reasonPhrase)) {
			$new->status->setMessage($reasonPhrase);
		}

		return $new;
	}

	public function getReasonPhrase() {
		return $this->status->getMessage();

	}

	public function send() {
		$this->status->send();
		foreach($this->headers as $header) {
			$header->send();
		}
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
