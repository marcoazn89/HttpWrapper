<?php

require_once('HttpHeader.php');

Class Status implements HTTPHeader {

	public $status = array(
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

		500	=> 'Internal Server Error',
		501	=> 'Not Implemented',
		502	=> 'Bad Gateway',
		503	=> 'Service Unavailable',
		504	=> 'Gateway Timeout',
		505	=> 'HTTP Version Not Supported'
		);

	public $code;
	public $message;

	public function __construct($statusCode = 200, $send = false) {
		if(array_key_exists($statusCode, $this->status)) {
			$this->code = $statusCode;
		}
		else {
			die("Unsupported HTTP status {$statusCode}");
		}

		if($send) {
			$this->sendHeader();
		}
	}

	public function setMessage($statusCode) {
		$this->message = $this->status[$statusCode];
	}

	public function sendHeader() {
		return header("{$_SERVER['SERVER_PROTOCOL']} {$this->code} {$this->message}");
	}
}
