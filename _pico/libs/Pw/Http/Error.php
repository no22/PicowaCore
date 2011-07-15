<?php
/**
 * Pw_Http_Error
 *
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Http_Error extends Pico
{
	protected $httpError = array(
		'404' => 'HTTP/1.0 404 Not Found',
		'500' => 'HTTP/1.0 500 Server Error',
		'401' => 'HTTP/1.0 401 Unauthorized',
	);

	public function render($app, $err, $fnCallback = false)
	{
		$header = isset($this->httpError[$err]) ? $this->httpError[$err] : $err ;
		header($header);
		$output = $fnCallback ? call($fnCallback) : null ;
		echo is_null($output) ? $app->render($err) : $output ;
		die();
	}
}
