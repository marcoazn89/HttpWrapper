<?php
namespace HTTP\Header;

class Status
{

	protected static $status = array(
		200	=> 'OK',
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
		404	=> 'Not Found',
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
		429	=> 'Too Many Requests',

		500	=> 'Internal Server Error',
		501	=> 'Not Implemented',
		502	=> 'Bad Gateway',
		503	=> 'Service Unavailable',
		504	=> 'Gateway Timeout',
		505	=> 'HTTP Version Not Supported'
	);

	protected static $protocol = '1.1';
	protected static $code = 200;
	protected static $message = 'OK';


	public static function set($code = 200, $protocol = '1.1')
	{
		self::$protocol = $protocol;
		self::$setCode($code);
		self::$setMessage(self::$status[$code]);
	}

	public static function getString()
	{
		return sprintf('HTTP/%s %s %s', self::$protocol, self::$code, self::$message);
	}

	public static function getCode() {
		return self::$code;
	}

	public static function setCode($code = 200) {
		if(array_key_exists($code, self::$status) || is_null($code)) {
			self::$code = is_null($code) ? 200 : $code;
			self::$message = self::$status[self::$code];
		}
		else {
			throw new \InvalidArgumentException("Invalid HTTP status code {$code}");
		}
	}

	public static function getProtocol() {
		return self::$protocol;
	}

	public static function setProtocol($protocol = '1.1') {
		if(is_string($protocol)) {
			self::$protocol = $protocol;
		}
		else {
			throw new \Exception("Invalid protocol {$protocol}", 1);
		}
	}

	public static function setMessage($message = 'OK') {
		self::$message = $message;
	}

	public static function getMessage() {
		return self::$message;
	}
}
