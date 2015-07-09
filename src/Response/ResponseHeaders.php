<?php
namespace HTTP\Response;

interface ResponseHeaders {
	const CONTENT_TYPE = 'Content-Type';
	const CACHE_CONTROL = 'Cache-Control';
	const CONTENT_LENGTH = 'Content-Length';
	const LANGUAGE = 'Language';
	const ALLOW = 'Allow';
	const ACCESS_CONTROL_ALLOW_METHODS = 'Access-Control-Allow-Methods';
	const ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';
	const ACCESS_CONTROL_ALLOW_HEADERS = 'Access-Control-Allow-Headers';
}