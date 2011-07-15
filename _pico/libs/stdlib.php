<?php
/**
 * startsWith, endsWith, matchesIn
 * http://blog.anoncom.net/2009/02/20/124.html
 */
/**
 * startsWith
 * >>> eq(startsWith('ABC1234', 'ABC'), true);
 * @param string $sHaystack
 * @param string $sNeedle
 * @return boolean
 */
if (!function_exists('startsWith')) {
	function startsWith($sHaystack, $sNeedle) {
		return strpos($sHaystack, $sNeedle, 0) === 0;
	}
}
/**
 * endsWith
 * >>> eq(endsWith('ABC1234', '234'), true);
 * @param string $sHaystack
 * @param string $sNeedle
 * @return boolean
 */
if (!function_exists('endsWith')) {
	function endsWith($sHaystack, $sNeedle) {
		$iLength = (strlen($sHaystack) - strlen($sNeedle));
		if ($iLength < 0) { return false; }
		return strpos($sHaystack, $sNeedle, $iLength) !== false;
	}
}
/**
 * matchesIn
 * >>> eq(matchesIn('ABC1234', 'C12'), true);
 * @param string $sHaystack
 * @param string $sNeedle
 * @return boolean
 */
if (!function_exists('matchesIn')) {
	function matchesIn($sHaystack, $sNeedle) {
		return strpos($sHaystack, $sNeedle) !== false;
	}
}
/**
 * dbglog
 * @param string $sText
 * @param string $sFilePath = null
 * @return
 */
if (!function_exists('dbglog')) {
	function dbglog($sText,$sFilePath = null)
	{
		$sFilePath = is_null($sFilePath) ? PICOWA_TEMP_PATH.'logs/debug.log' : $sFilePath ;
		error_log(date('Y-m-d H:i:s: ').$sText."\n", 3, $sFilePath);
	}
}


if (!function_exists('dbglogr')) {
	function dbglogr($array)
	{
		dbglog(print_r($array,true));
	}
}

/**
 * dbgmail
 * @param string $sText
 * @param string $sMail
 * @return
 */
if (!function_exists('dbgmail')) {
	function dbgmail($sText, $sMail)
	{
		error_log(date('Y-m-d H:i:s: ').$sText."\n", 1, $sMail);
	}
}
/**
 * ref
 * http://d.hatena.ne.jp/anatoo/20090320/1237530764
 * @param mixed $obj
 * @return mixed
 */
if (!function_exists('ref')) {
	function ref($obj) { return $obj; }
}
