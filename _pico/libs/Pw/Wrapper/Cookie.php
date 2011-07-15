<?php
/**
 * Picowa Cookie wrapper
 *
 * @package		Picowa
 * @since		2010-04-13
 */	
class Pw_Wrapper_Cookie extends Pw_Wrapper_Array
{
	public function __construct() 
	{
		$this->subject = &$_COOKIE;
	}

	public function __set($key, $value) 
	{
		if (is_null($value)) {
			setcookie($key, '', time() - 3600);
			unset($this->subject[$key]);
			return $value;
		}
		else if (is_string($value)) {
			setcookie($key, $value);
			$this->subject[$key] = $value;
			return $value;
		}
		else if (is_array($value)) {
			@list($val, $expire, $path, $domain, $secure, $httponly) = $value;
			$expire = is_null($expire) ? 0 : $expire ;
			$secure = is_null($secure) ? false : $secure ;
			$httponly = is_null($httponly) ? false : $httponly ;
			setcookie($key, $val, $expire, $path, $domain, $secure, $httponly);
			$this->subject[$key] = $val;
			return $val;
		}
		return null;
	}

	public function __call($sName, $aArgs)
	{
		if (count($aArgs) < 2) {
			list($sKey) = $aArgs;
			$sValue = $this->{$sName};
			if (is_null($sValue)) return null;
			$aValue = json_decode(base64_decode($sValue, true), true);
			return isset($aValue[$sKey]) ? $aValue[$sKey] : null ;
		}
		else {
			list($sKey, $mValue) = $aArgs;
			$sValue = $this->{$sName};
			$aValue = is_null($sValue) ? array() : json_decode(base64_decode($sValue, true), true) ;
			$aValue[$sKey] = $mValue;
			$sValue = base64_encode(json_encode($aValue));
			$this->{$sName} = $sValue;
			return $mValue;
		}
	}
}
