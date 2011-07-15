<?php
/**
 * Picowa AutoLoad 
 *
 * @package		Picowa
 * @since		2010-04-09
 */

class PicowaAutoload
{
	public static function autoload($sClass)
	{
		$sPath = strtr($sClass, array('_'=>'/')).'.php';
		if (file_exists(PICOWA_APP_LIB_PATH . $sPath)) {
			include(PICOWA_APP_LIB_PATH . $sPath);
		} 
		else if (file_exists(PICOWA_LIB_PATH . $sPath)) {
			include(PICOWA_LIB_PATH . $sPath);
		}
		else if (file_exists(PICOWA_EXTRA_LIB_PATH . $sPath)) {
			include(PICOWA_EXTRA_LIB_PATH . $sPath);
		}
	}
}
function_exists('__autoload') and spl_autoload_register('__autoload');
spl_autoload_register('PicowaAutoload::autoload');
