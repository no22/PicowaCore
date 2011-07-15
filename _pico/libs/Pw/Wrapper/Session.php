<?php
/**
 * Picowa Session wrapper
 *
 * @package		Picowa
 * @since		2010-04-13
 */
 class Pw_Wrapper_Session extends Pw_Wrapper_Array
 {
	public function __construct()
	{
		$this->subject = &$_SESSION;
	}

	public function __set($key, $value)
	{
		if (is_null($value)) {
			unset($this->subject[$key]);
			return $value;
		}
		else {
			$this->subject[$key] = $value;
			return $value;
		}
	}
}
