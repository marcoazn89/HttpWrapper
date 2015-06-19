<?php
namespace HTTP\Response;

abstract class Header {
	protected $values = [];

	//Singleton Pattern: Can't create an instance
	final protected function __construct() {}
	//Singleton Pattern: Can't clone
	final protected function __clone() {}
	//Singleton Pattern: Can't deserialize
	final protected function __wakeup() {}

	abstract public function getName();

	abstract protected function setDefaults();

	final public function set($values) {
		$this->values = is_array($values) ? $values : [$values];

		return $this;
	}

	final public function add($value) {
		if( ! is_array($value)) {
			$value = [$value];
		}

		foreach($value as $v) {
			$key = array_search($v, $this->values);

			if( ! $key) {
				$this->values[] = $v;
			}
			else {
				$this->values[$key] = $v;
			}
		}

		return $this;
	}

	final public function getValues() {
		return $this->values;
	}

	final public function getString() {
		return empty($this->values) ? '' : implode(',', $this->values);
	}

	/**
	 * Send header string
	 */
	final public function send() {
		if(empty($this->values)) {
			$this->setDefaults();
		}

		header("{$this->getName()}: {$this->getString()}");
	}

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
