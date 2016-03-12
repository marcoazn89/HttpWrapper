<?php
namespace HTTP\Header;

class WWWAuthenticate extends Interfaces\Header
{

	const BASIC = 'Basic';
	const DIGEST = 'Digest';
	const CLIENT_CERT = 'Client-Cert';
	const FORM = 'Form';
}
