<?php
/**
 * Picowa Array wrapper
 *
 * @package		Picowa
 * @since		2010-04-06
 */
class Pw_Wrapper_Array
{
	protected $subject;

	public function __construct(&$subject = array())
	{
		$this->subject = &$subject;
	}

	public function __get($key)
	{
		return isset($this->subject[$key]) ? $this->subject[$key] : null;
	}

	public function __set($key, $value)
	{
		$this->subject[$key] = $value;
		return $value;
	}

	public function __call($sName, $aArgs)
	{
		if (count($aArgs) < 2) {
			list($sKey) = $aArgs;
			return isset($this->subject[$sName][$sKey]) ? $this->subject[$sName][$sKey] : null ;
		}
		else {
			list($sKey, $mValue) = $aArgs;
			$this->subject[$sName][$sKey] = $mValue;
			return $mValue;
		}
	}

	public function &_()
	{
		return $this->subject;
	}
}

