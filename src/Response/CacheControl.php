<?php
namespace HTTP\Response;

class CacheControl extends Header {

	const CACHE_PUBLIC = 'public';
	const CACHE_PRIVATE = 'private';
	const NO_CACHE = 'no-cache';
	const NO_RESPONSE = 'no-response';
	const REVALIDATE = 'must-revalidate';
	const PROXY_REVALIDATE = 'proxy-revalidate';

	const MAX_AGE = 'max-age';
	const S_MAX_AGE = 's_maxage';

	const STALE_IF_ERROR = 'stale-if-error';
	const STALE_WHILE_REVALIDATE = 'stale-while-revalidate';

	public function getName() {
		return 'Cache-Control';
	}

	public function setExpirationType($type = self::MAX_AGE, $duration = 3600) {
		$this->values['expiration'] = "{$type}={$duration}";

		return $this;
	}

	public function setMode($mode = self::CACHE_PUBLIC) {
		$this->values['mode'] = $mode;

		return $this;
	}

	public function setChannel($url) {
		$this->values['channel'] = "channel={$url}";

		return $this;
	}

	public function setStale($stale) {
		$this->values['stale'] = $stale;

		return $this;
	}
}
