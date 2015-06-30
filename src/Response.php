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
	protected $bodySize = 4096;

	public function __construct() {
		$thisClass = new \ReflectionClass(__CLASS__);
		$allClasses = get_declared_classes();

		// Sync the status code
		$this->status = Response\Status::getInstance();

		// Sync headers
		foreach($thisClass->getConstants() as $class) {
			$header = sprintf('HTTP\Response\%s',$class);

			if(in_array($header, $allClasses)) {
				$this->headers[$class] = $header::getInstance();
			}
		}

		$this->body = new Body(fopen('php://temp', 'r+'));
	}

	/**
	 * Set the byte limit size for the body response
	 * @param  int $bytes  	The byte size limit for the body. The default value is 4096
	 * @return $this        The Response object
	 */
	public function bodySize($bytes) {
		$this->bodySize = $bytes;
	}

	/**
   * Gets the response status code.
   *
   * The status code is a 3-digit integer result code of the server's attempt
   * to understand and satisfy the request.
   *
   * @return int Status code.
   */
	public function getStatusCode() {
		return $this->status->getCode();
	}

	/**
  * Gets the response reason phrase associated with the status code.
  *
  * @link http://tools.ietf.org/html/rfc7231#section-6
  * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
  * @return string 		Reason phrase or must an empty string if none present.
  */
	public function getReasonPhrase() {
		return $this->status->getMessage();

	}

	/**
   * Return an instance with the specified status code and, optionally, reason phrase.
   *
   * @link http://tools.ietf.org/html/rfc7231#section-6
   * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
   * @param int $code The 3-digit integer result code to set.
   * @param string $reasonPhrase The reason phrase to use with the
   *     provided status code; if none is provided, implementations MAY
   *     use the defaults as suggested in the HTTP specification.
   * @return self
   * @throws \InvalidArgumentException For invalid status code arguments.
   */
	public function withStatus($code, $reasonPhrase = '') {
		$new = clone $this;
		$new->status->setCode($code);

		if( ! empty($reasonPhrase)) {
			$new->status->setMessage($reasonPhrase);
		}

		return $new;
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

	/**
   * Retrieves the HTTP protocol version as a string.
   *
   * @return string 	HTTP protocol version.
   */
	public function getProtocolVersion() {
		return Response\Status::getInstance()->getProtocol();
	}

	/**
   * Retrieves all message header values.
   *
   * The keys represent the header name as it will be sent over the wire, and
   * each value is an array of strings associated with the header.
   *
   * While header names are not case-sensitive, getHeaders() will preserve the
   * exact case in which headers were originally specified.
   *
   * @return array Returns an associative array of the message's headers.
   */
	public function getHeaders() {
		$headers = [];
		foreach($this->headers as $key => $header) {
			$headers[$key] = $header->getValues();
		}
		return $headers;
	}

	/**
   * Checks if a header exists by the given case-insensitive name.
   *
   * @param string $name Case-insensitive header field name.
   * @return bool Returns true if any header names match the given header
   *     name using a case-insensitive string comparison. Returns false if
   *     no matching header name is found in the message.
   */
	public function hasHeader($name) {
		return isset($this->headers[$name]) ? true : false;
	}

	/**
   * Retrieves a message header value by the given case-insensitive name.
   *
   * This method returns an array of all the header values of the given
   * case-insensitive header name.
   *
   * @param string $name Case-insensitive header field name.
   * @return string[] An array of string values as provided for the given
   *    header. If the header does not appear in the message, this method
   *    returns an empty array.
   */
	public function getHeader($name) {
		return isset($this->headers[$name]) ? $this->headers[$name]->getValues() : [];
	}

	/**
   * Retrieves a comma-separated string of the values for a single header.
   *
   * This method returns all of the header values of the given
   * case-insensitive header name as a string concatenated together using
   * a comma.
   *
   * NOTE: Not all header values may be appropriately represented using
   * comma concatenation. For such headers, use getHeader() instead
   * and supply your own delimiter when concatenating.
   *
   * @param string $name Case-insensitive header field name.
   * @return string A string of values as provided for the given header
   *    concatenated together using a comma. If the header does not appear in
   *    the message, this method returns an empty string.
   */
	public function getHeaderLine($name) {
		return isset($this->headers[$name]) ? $this->headers[$name]->getString() : '';
	}

	/**
	 * Method used to strict header mapping to avoid creating non-standard headers
	 *
	 * @param  string $name
	 * @return \HTTP\Response\Header 		An object of type Header
	 */
	protected function mapHeader($name) {
		switch(strtolower($name)) {
			case strtolower(self::CACHE_CONTROL):
				return Response\CacheControl::getInstance();
				break;
			case strtolower(self::CONTENT_LENGTH):
				return Response\ContentLength::getInstance();
				break;
			case strtolower(self::CONTENT_TYPE):
				return Response\ContentType::getInstance();
				break;
			case strtolower(self::ALLOW):
				return Response\Allow::getInstance();
				break;
			default:
				throw new \InvalidArgumentException("Invalid HTTP header {$name}");
		}
	}

	/**
   * Return an instance with the provided value replacing the specified header.
   *
   * While header names are case-insensitive, the casing of the header will
   * be preserved by this function, and returned from getHeaders().
   *
   * @param string $name Case-insensitive header field name.
   * @param string|string[] $value Header value(s).
   * @return self
   * @throws \InvalidArgumentException for invalid header names or values.
   */
	public function withHeader($name, $value) {
		$new = clone $this;
		$header = sprintf('HTTP\Response\%s',$name);

		$new->headers[$name] = $this->mapHeader($name)->set($value);

		return $new;
	}

	/**
   * Return an instance with the provided value replacing the Content-Type header.
   *
   * While header names are case-insensitive, the casing of the header will
   * be preserved by this function, and returned from getHeaders().
   *
   * @param string $name Case-insensitive header field name.
   * @param string|string[] $value Header value(s).
   * @return self
   * @throws \InvalidArgumentException for invalid header names or values.
   */
	public function withType($value) {
		$new = clone $this;

		$new->headers[self::CONTENT_TYPE] = Response\ContentType::getInstance()->set($value);

		return $new;
	}

	/**
   * Return an instance with the provided value replacing the Language header.
   *
   * While header names are case-insensitive, the casing of the header will
   * be preserved by this function, and returned from getHeaders().
   *
   * @param string $name Case-insensitive header field name.
   * @param string|string[] $value Header value(s).
   * @return self
   * @throws \InvalidArgumentException for invalid header names or values.
   */
	public function withLanguage($value) {
		$new = clone $this;

		$new->headers[self::LANGUAGE] = Response\Language::getInstance()->set($value);

		return $new;
	}

	/**
	 * Negotiate Mime types
	 * @param  boolean $strongNegotiation 	Enfore a strong negotiation
	 * @todo	 Implement weights
	 * @return [type]                     [description]
	 */
	public function withTypeNegotiation($strongNegotiation = false) {
		$negotiation = array_intersect(Request\AcceptType::getContent(), Support\TypeSupport::getSupport());

		$content = '';

		if(count($negotiation) > 0) {
			$content = current($negotiation);
		}
		else {
			if($strongNegotiation) {
				$this->failTypeNegotiation();
			}
			else {
				$content =  Support\TypeSupport::getSupport();
				$content = $content[0];
			}
		}

		return $this->withType($content);
	}

	/**
   * Return an instance with the specified header appended with the given value.
   *
   * Existing values for the specified header will be maintained. The new
   * value(s) will be appended to the existing list. If the header did not
   * exist previously, it will be added.
   *
   * @param string $name Case-insensitive header field name to add.
   * @param string|string[] $value Header value(s).
   * @return self
   * @throws \InvalidArgumentException for invalid header names or values.
   */
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

	/**
   * Return an instance with the Content-Type header appended with the given value.
   *
   * Existing values for the specified header will be maintained. The new
   * value(s) will be appended to the existing list. If the header did not
   * exist previously, it will be added.
   *
   * @param string $name Case-insensitive header field name to add.
   * @param string|string[] $value Header value(s).
   * @return self
   * @throws \InvalidArgumentException for invalid header names or values.
   */
	public function withAddedType($value) {
		$new = clone $this;

		if( ! array_key_exists(self::CONTENT_TYPE, $new->headers)) {
			$new->headers[self::CONTENT_TYPE] = Response\ContentType::getInstance();
		}

		$new->headers[self::CONTENT_TYPE]->add($value);

		return $new;
	}

	/**
   * Return an instance with the Language header appended with the given value.
   *
   * Existing values for the specified header will be maintained. The new
   * value(s) will be appended to the existing list. If the header did not
   * exist previously, it will be added.
   *
   * @param string $name Case-insensitive header field name to add.
   * @param string|string[] $value Header value(s).
   * @return self
   * @throws \InvalidArgumentException for invalid header names or values.
   */
	public function withAddedLanguage($value) {
		$new = clone $this;

		if( ! array_key_exists(self::LANGUAGE, $new->headers)) {
			$new->headers[self::LANGUAGE] = Response\ContentType::getInstance();
		}

		$new->headers[self::LANGUAGE]->add($value);

		return $new;
	}

	/**
   * Return an instance without the specified header.
   *
   * Header resolution is done without case-sensitivity.
   *
   * @param string $name Case-insensitive header field name to remove.
   * @return self
   */
	public function withoutHeader($name) {
		$new = clone $this;
		unset($new->headers[$name]);

		return $new;
	}

	/**
   * Return an instance without Content-Type header.
   *
   * Header resolution is done without case-sensitivity.
   *
   * @param string $name Case-insensitive header field name to remove.
   * @return self
   */
	public function withoutType() {
		$new = clone $this;
		unset($new->headers[self::CONTENT_TYPE]);

		return $new;
	}


	/**
   * Return an instance without Language header.
   *
   * Header resolution is done without case-sensitivity.
   *
   * @param string $name Case-insensitive header field name to remove.
   * @return self
   */
	public function withoutLanguage() {
		$new = clone $this;
		unset($new->headers[self::LANGUAGE]);

		return $new;
	}

	/**
	 * Send a 406 response for failed content negotiation
	 */
	protected function failTypeNegotiation() {
		$supported =  Request\TypeSupport::getSupport();
		$clientSupport =  Request\AcceptType::getContent();

		$supported = implode(',', $supported);
		$clientSupport = implode(',',$clientSupport);

		$this->withStatus(406)->withType(Response\ContentType::TEXT)
		->write("NOT SUPPORTED\nThis server does not support {$supported}.\nSupported formats: {$clientSupport}")
		->send();
		exit;
	}

	/**
   * Gets the body of the message.
   *
   * @return StreamInterface 	Returns the body as a stream.
   */
	public function getBody() {
		return $this->body;
	}

	/**
   * Return an instance with the specified message body.
   *
   * The body MUST be a StreamInterface object.
   *
   * @param StreamInterface $body Body.
   * @return self
   * @throws \InvalidArgumentException When the body is not valid.
   */
	public function withBody(\Psr\Http\Message\StreamInterface $body) {
		$new = clone $this;
		$new->body = $body;

		return $new;
	}

	/**
	 * Write content to the stream
	 * @param  mixed $data 		Data to be added to the body
	 * @return Response     	The Response object
	 */
	public function write($data) {
    $this->getBody()->write($data);

    return $this;
  }

  /**
	 * Write content to the stream by overwriting existing content
	 * @param  mixed $data 		Data to be written to the body
	 * @return Response     	The Response object
	 */
  public function overwrite($data) {
  	$body = $this->getBody();
    $body->rewind();
    $body->write($data);

    return $this;
  }

  /**
   * Write Json content and overwrite existant content if any. This method
   * enforces the use of key-valued json structures
   * @param  array $value 	The array to be converted to json
   * @return Response       The response object
   */
  public function writeJson(array $value) {
  	return $this->overwrite(json_encode($value))->withType('application/json;charset=utf-8');
  }

  /**
   * Redirect.
   *
   * This method prepares the response object to return an HTTP Redirect
   * response to the client.
   *
   * @param  string $url    The redirect destination.
   * @return self
   */
  public function withRedirect($url) {
    return $this->withStatus(302)->withHeader('Location', $url);
  }

  /**
   * Send the Response object over the wire
   */
	public function send() {
		$this->status->send();

		foreach($this->headers as $header) {
			$header->send();
		}

		$body = $this->getBody();
     if ($body->isAttached()) {
        $body->rewind();
        while (!$body->eof()) {
          echo $body->read($this->bodySize);
        }
     }
	}
}
