<?php
namespace HTTP\Response;

class Status {
	const CODE200	= 200;
	const CODE201	= 201;
	const CODE202	= 202;
	const CODE203	= 203;
	const CODE204	= 204;
	const CODE205	= 205;
	const CODE206	= 206;

	const CODE300	= 300;
	const CODE301	= 301;
	const CODE302	= 302;
	const CODE303	= 303;
	const CODE304	= 304;
	const CODE305	= 305;
	const CODE307	= 307;

	const CODE400	= 400;
	const CODE401	= 401;
	const CODE403	= 403;
	const CODE404	= 404;
	const CODE405	= 405;
	const CODE406	= 406;
	const CODE407	= 407;
	const CODE408	= 408;
	const CODE409	= 409;
	const CODE410	= 410;
	const CODE411	= 411;
	const CODE412	= 412;
	const CODE413	= 413;
	const CODE414	= 414;
	const CODE415	= 415;
	const CODE416	= 416;
	const CODE417	= 417;
	const CODE422	= 422;

	const CODE500	= 500;
	const CODE501	= 501;
	const CODE502	= 502;
	const CODE503	= 503;
	const CODE504	= 504;
	const CODE505	= 505;

	protected $status = array(
		self::CODE200	=> 'OK',
		self::CODE201	=> 'Created',
		self::CODE202	=> 'Accepted',
		self::CODE203	=> 'Non-Authoritative Information',
		self::CODE204	=> 'No Content',
		self::CODE205	=> 'Reset Content',
		self::CODE206	=> 'Partial Content',

		self::CODE300	=> 'Multiple Choices',
		self::CODE301	=> 'Moved Permanently',
		self::CODE302	=> 'Found',
		self::CODE303	=> 'See Other',
		self::CODE304	=> 'Not Modified',
		self::CODE305	=> 'Use Proxy',
		self::CODE307	=> 'Temporary Redirect',

		self::CODE400	=> 'Bad Request',
		self::CODE401	=> 'Unauthorized',
		self::CODE403	=> 'Forbidden',
		self::CODE404	=> 'Not Found',
		self::CODE405	=> 'Method Not Allowed',
		self::CODE406	=> 'Not Acceptable',
		self::CODE407	=> 'Proxy Authentication Required',
		self::CODE408	=> 'Request Timeout',
		self::CODE409	=> 'Conflict',
		self::CODE410	=> 'Gone',
		self::CODE411	=> 'Length Required',
		self::CODE412	=> 'Precondition Failed',
		self::CODE413	=> 'Request Entity Too Large',
		self::CODE414	=> 'Request-URI Too Long',
		self::CODE415	=> 'Unsupported Media Type',
		self::CODE416	=> 'Requested Range Not Satisfiable',
		self::CODE417	=> 'Expectation Failed',
		self::CODE422	=> 'Unprocessable Entity',

		self::CODE500	=> 'Internal Server Error',
		self::CODE501	=> 'Not Implemented',
		self::CODE502	=> 'Bad Gateway',
		self::CODE503	=> 'Service Unavailable',
		self::CODE504	=> 'Gateway Timeout',
		self::CODE505	=> 'HTTP Version Not Supported'
		);

	protected $protocol = '1.1';
	protected $code = self::CODE200;
	protected $message = 'OK';

	public function set($code = self::CODE200, $protocol = '1.1') {
		$this->protocol = $protocol;
		$this->setCode($code);
		$this->setMessage($this->status[$code]);

		return $this;
	}

	public function getString() {
		return "HTTP/{$this->protocol} {$this->code} {$this->message}";
	}

	public function getCode() {
		return $this->code;
	}

	public function setCode($code = self::CODE200) {
		if(array_key_exists($code, $this->status)) {
			$this->code = $code;
			$this->message = $this->status[$code];

			return $this;
		}
		else {
			throw new E\xception("Unkown HTTP response code {$code}", 1);
		}
	}

	public function getProtocol() {
		return $this->protocol;
	}

	public function setProtocol($protocol = '1.1') {
		if(is_string($protocol)) {
			$this->protocol = $protocol;

			return $this;
		}
		else {
			throw new \Exception("Invalid protocol {$protocol}", 1);
		}
	}

	public function setMessage($message = 'OK') {
		$this->message = $message;

		return $this;
	}

	public function getMessage() {
		return $this->message;
	}

	public function send() {
		header($this->getString());
	}

	//Singleton Pattern: Can't create an instance
	final protected function __construct() {}
	//Singleton Pattern: Can't clone
	final protected function __clone() {}
	//Singleton Pattern: Can't deserialize
	final protected function __wakeup() {}

	/**
	 * Get the header instance
	 * @return HttpHeader
	 */
	final public static function getInstance() {
		static $instance = null;

		if(is_null($instance)) {
			$instance = new static();
		}

		return $instance;
	}
}
