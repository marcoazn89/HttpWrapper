<?php
namespace HTTP\Header;

class CacheControl extends Interfaces\Header
{

	const CACHE_PUBLIC = 'public';
	const CACHE_PRIVATE = 'private';
	const NO_CACHE = 'no-cache';
	const NO_RESPONSE = 'no-response';
	const REVALIDATE = 'must-revalidate';
	const PROXY_REVALIDATE = 'proxy-revalidate';

	const EXP_MAX_AGE = 'max-age';
	const EXP_S_MAX_AGE = 's_maxage';

	const STALE_IF_ERROR = 'stale-if-error';
	const STALE_WHILE_REVALIDATE = 'stale-while-revalidate';

	public static function expire($type = self::EXP_MAX_AGE, $duration = 3600) {
		return "{$type}={$duration}";
	}

	public static function channel($url) {
		return "channel={$url}";
	}

	public static function stale($stale, $value) {
		return "{$stale}={$value}";
	}
}
