<?php
namespace HTTP\Response;

class Status {
	const CODE200 = 200;
	const CODE404 = 404;
	const CODE500 = 500;

	public $status = array(
		self::CODE200	=> 'OK',
		201	=> 'Created',
		202	=> 'Accepted',
		203	=> 'Non-Authoritative Information',
		204	=> 'No Content',
		205	=> 'Reset Content',
		206	=> 'Partial Content',

		300	=> 'Multiple Choices',
		301	=> 'Moved Permanently',
		302	=> 'Found',
		303	=> 'See Other',
		304	=> 'Not Modified',
		305	=> 'Use Proxy',
		307	=> 'Temporary Redirect',

		400	=> 'Bad Request',
		401	=> 'Unauthorized',
		403	=> 'Forbidden',
		self::CODE404	=> 'Not Found',
		405	=> 'Method Not Allowed',
		406	=> 'Not Acceptable',
		407	=> 'Proxy Authentication Required',
		408	=> 'Request Timeout',
		409	=> 'Conflict',
		410	=> 'Gone',
		411	=> 'Length Required',
		412	=> 'Precondition Failed',
		413	=> 'Request Entity Too Large',
		414	=> 'Request-URI Too Long',
		415	=> 'Unsupported Media Type',
		416	=> 'Requested Range Not Satisfiable',
		417	=> 'Expectation Failed',
		422	=> 'Unprocessable Entity',

		self::CODE500	=> 'Internal Server Error',
		501	=> 'Not Implemented',
		502	=> 'Bad Gateway',
		503	=> 'Service Unavailable',
		504	=> 'Gateway Timeout',
		505	=> 'HTTP Version Not Supported'
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
