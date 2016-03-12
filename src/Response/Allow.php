<?php
namespace HTTP\Response;

class Allow extends Header {

	const GET = 'get';
	const POST = 'post';
	const PUT = 'put';
	const PATCH = 'patch';
	const OPTIONS = 'options';
	const HEAD = 'head';
}
