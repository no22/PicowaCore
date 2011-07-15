<?php
/**
 * Pw_Handler_Exception
 *
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Handler_Exception extends Pico
{
	public function error($app, $aError, $isDevelopment = false)
	{
		if ($isDevelopment) {
			$callback = $isDevelopment ? $this->_outputError($app, $aError, $isDevelopment) : false ;
			return $app->error('500', $callback);
		}
		return $isDevelopment;
	}
	
	public function outputError($app, $aError, $isDevelopment = false)
	{
		$aError['isDev'] = $isDevelopment;
		return $app->render('exception', $aError);
	}

 	public function handleException($app, $exception)
	{
		$aError = array(
			'type' => get_class($exception),
			'code' => $exception->getCode(),
			'message' => $exception->getMessage(),
			'file' => $exception->getFile(),
			'line' => $exception->getLine(),
		);
		Pico::cfg()->use_debug_mail and dbgmail(print_r($aError, true), Pico::cfg()->admin_email);
		Pico::cfg()->use_debug_log and dbglogr($aError);
		$aError['trace'] = $exception->getTraceAsString();
		return $this->error($app, $aError, PICOWA_DEBUG_MODE);
	}
}
