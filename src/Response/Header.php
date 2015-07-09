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

	/**
	 * Return the name of the header in a format that's HTTP compliant
	 * @return string Name of the header
	 */
	abstract public function getName();

	/**
	 * Set & override any value(s) of the HTTP header. This doesn't enforce
	 * any standard values as the developer is allowed to add anything.
	 * @param HTTP\Response\Header  $value    An instance of the object.
	 */
	final public function set($values) {
		$this->values = is_array($values) ? $values : [$values];

		return $this;
	}

	/**
	 * Add any value(s) to the HTTP header. This doesn't enforce
	 * any standard values as the developer is allowed to add anything.
	 * @param HTTP\Response\Header  $value    An instance of the object.
	 */
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

	/**
	 * [getValues description]
	 * @return [type] [description]
	 */
	final public function getValues() {
		return $this->values;
	}

	/**
	 * Return a string representation of the header values
	 * @return string Header values concatenated by a comma
	 */
	final public function getString() {
		return empty($this->values) ? '' : implode(',', $this->values);
	}

	/**
	 * Send header string
	 */
	final public function send() {
		if(empty($this->values)) {
			throw new \Exception("No values were set for header: {$this->getName()}");
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
