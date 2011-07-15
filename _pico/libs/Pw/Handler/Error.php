<?php
/**
 * Pw_Handler_Error
 *
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Handler_Error extends Pico
{
	protected $errorLevel = array(
		E_ERROR => 'Fatal Error',
		E_WARNING => 'Warning',
		E_NOTICE => 'Notice',
		E_USER_ERROR => 'Fatal Error',
		E_USER_WARNING => 'Warning',
		E_USER_NOTICE => 'Notice',
		E_STRICT => 'Suggestion',
	);

	public function error($app, $aError, $isDevelopment = false)
	{
		$errno = $aError['errno'];
		if ($errno === E_ERROR || $errno === E_USER_ERROR || $isDevelopment) {
			$callback = $isDevelopment ? $this->_outputError($app, $aError, $isDevelopment) : false ;
			return $app->error('500', $callback);
		}
		return $isDevelopment;
	}
	
	public function outputError($app, $aError, $isDevelopment = false)
	{
		$aError['isDev'] = $isDevelopment;
		$aError['type'] = isset($this->errorLevel[$aError['errno']]) ? $this->errorLevel[$aError['errno']] : 'Error' ;
		return $app->render('error', $aError);
	}

 	public function handleError($app, $errno, $message, $file, $line)
	{
		$aError = compact('errno', 'message', 'file', 'line');
		Pico::cfg()->use_debug_mail and dbgmail(print_r($aError, true), Pico::cfg()->admin_email);
		Pico::cfg()->use_debug_log and dbglogr($aError);
		return $this->error($app, $aError, PICOWA_DEBUG_MODE);
	}	
}
