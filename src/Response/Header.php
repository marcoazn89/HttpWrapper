<?php
namespace HTTP\Response;

abstract class Header
{
	public static function headerName()
	{
		$className = explode('\\', get_called_class());
		return preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className[count($className) - 1]);
	}

	public static function values(array $values)
	{
		return implode(',', $values);
	}
}
