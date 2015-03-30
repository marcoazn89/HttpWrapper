<?php
/*interface Status {
	const HTTP200 = 'OK';
	const HTTP201 = 'Created';
	const HTTP202 = 'Accepted';
	const HTTP203 = 'Non-Authoritative Information';
	const HTTP204 = 'No Content';
	const HTTP205 = 'Reset Content';
	const HTTP206 = 'Partial Content';

	const HTTP300 = 'Multiple Choices';
	const HTTP301 = 'Moved Permanently';
	const HTTP302 = 'Found';
	const HTTP303 = 'See Other';
	const HTTP304 = 'Not Modified';
	const HTTP305 = 'Use Proxy';
	const HTTP307 = 'Temporary Redirect';

	const HTTP400 = 'Bad Request';
	const HTTP401 = 'Unauthorized';
	const HTTP403 = 'Forbidden';
	const HTTP404 = 'Not Found';
	const HTTP405 = 'Method Not Allowed';
	const HTTP406 = 'Not Acceptable';
	const HTTP407 = 'Proxy Authentication Required';
	const HTTP408 = 'Request Timeout';
	const HTTP409 = 'Conflict';
	const HTTP410 = 'Gone';
	const HTTP411 = 'Length Required';
	const HTTP412 = 'Precondition Failed';
	const HTTP413 = 'Request Entity Too Large';
	const HTTP414 = 'Request-URI Too Long';
	const HTTP415 = 'Unsupported Media Type';
	const HTTP416 = 'Requested Range Not Satisfiable';
	const HTTP417 = 'Expectation Failed';
	const HTTP422 = 'Unprocessable Entity';

	const HTTP500 = 'Internal Server Error';
	const HTTP501 = 'Not Implemented';
	const HTTP502 = 'Bad Gateway';
	const HTTP503 = 'Service Unavailable';
	const HTTP504 = 'Gateway Timeout';
	const HTTP505 = 'HTTP Version Not Supported';
}*/
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
