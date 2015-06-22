<?php
/**
 * HTTP Wrapper
 *
 * This a PSR-7 compatible package used as a wrapper for HTTP response headers. It allows
 * you to easily write proper headers with the use of the constants that can be found in
 * each of the header classes. Also, it has the capability to do content negotiation.
 *
 * @link      https://github.com/marcoazn89/http-wrapper
 * @copyright Copyright (c) 2015 Marco A. Chang
 * @license   https://github.com/marcoazn89/http-wrapper/blob/master/LICENSE (MIT)
 * @author  	Marco A. Chang 		contact@marcochang.com
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

		$this->body = new Body(fopen('php://temp', 'r+'));
	}

	public function withStatus($code, $reasonPhrase = '') {
		$new = clone $this;
		$new->status->setCode($code);

		if( ! empty($reasonPhrase)) {
			$new->status->setMessage($reasonPhrase);
		}

		return $new;
	}

	public function getStatusCode() {
		return $this->status->getCode();
	}

	/**
   * Retrieves the HTTP protocol version as a string.
   *
   * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
   *
   * @return string HTTP protocol version.
   */
	public function getProtocolVersion() {
		return Response\Status::getInstance()->getProtocol();
	}

	/**
   * Return an instance with the specified HTTP protocol version.
   *
   * The version string MUST contain only the HTTP version number (e.g.,
   * "1.1", "1.0").
   *
   * @param string $version HTTP protocol version
   * @return self
   */
	public function withProtocolVersion($version) {
		Response\Status::getInstance()->setProtocol($version);

		return clone $this;
	}

	public function getReasonPhrase() {
		return $this->status->getMessage();

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

	public function withMime($value) {
		$new = clone $this;

		$new->headers[self::CONTENT_TYPE] = Response\ContentType::getInstance()->set($value);

		return $new;
	}

	public function withLanguage($value) {
		$new = clone $this;

		$new->headers[self::LANGUAGE] = Response\Language::getInstance()->set($value);

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

	public function withAddedMime($value) {
		$new = clone $this;

		if( ! array_key_exists(self::CONTENT_TYPE, $new->headers)) {
			$new->headers[self::CONTENT_TYPE] = Response\ContentType::getInstance();
		}

		$new->headers[self::CONTENT_TYPE]->add($value);

		return $new;
	}

	public function withAddedLanguage($value) {
		$new = clone $this;

		if( ! array_key_exists(self::LANGUAGE, $new->headers)) {
			$new->headers[self::LANGUAGE] = Response\ContentType::getInstance();
		}

		$new->headers[self::LANGUAGE]->add($value);

		return $new;
	}

	public function withoutHeader($name) {
		$new = clone $this;
		unset($new->headers[$name]);

		return $new;
	}

	public function withoutMime() {
		$new = clone $this;
		unset($new->headers[self::CONTENT_TYPE]);

		return $new;
	}

	public function withoutLanguage() {
		$new = clone $this;
		unset($new->headers[self::LANGUAGE]);

		return $new;
	}

	public function negotiateContentType($strongNegotiation = false) {
		$negotiation = $this->negotiate(Request\AcceptType::getContent(), Support\TypeSupport::getSupport());

		$content = '';

		if(count($negotiation) > 0) {
			$content = $negotiation[0];
		}
		else {
			if($strongNegotiation) {
				$this->fail();
			}
			else {
				$content =  Support\TypeSupport::getSupport();
				$content = $content[0];
			}
		}

		return $content;
	}

	public function negotiate($requestedContent, $supportedContent) {
		$negotiation = array_intersect($requestedContent, $supportedContent);

		$result = [];

		foreach($negotiation as $support) {
			$result[] = $support;
		}

		return $result;
	}

	public function fail() {
		$supported =  Request\TypeSupport::getSupport();
		$clientSupport =  Request\AcceptType::getContent();

		$supported = implode(',', $supported);
		$clientSupport = implode(',',$clientSupport);

		self::status(406);
		self::contentType(Response\ContentType::TEXT);
		self::body("NOT SUPPORTED\nThis server does not support {$supported}.\nSupported formats: {$clientSupport}");
		self::sendResponse();
	}

	public function body($body) {
		$this->body = $body;
	}

	public function getBody() {
		return $this->body;
	}

	public function withBody(\Psr\Http\Message\StreamInterface $body) {
		$new = clone $this;
		$new->body = $body;

		return $new;
	}

	public function write($data) {
    $this->getBody()->write($data);

    return $this;
  }

	public function send() {
		$this->status->send();

		foreach($this->headers as $header) {
			$header->send();
		}

		$body = $this->getBody();
     if ($body->isAttached()) {
        $body->rewind();
        while (!$body->eof()) {
          echo $body->read(4096);
        }
     }
	}
}
