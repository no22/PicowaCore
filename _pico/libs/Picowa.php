<?php
/**
 * Picowa Lightweight Web Application Class
 *
 * @package		Picowa
 * @since		2010-04-06
 */
!defined('PICOWA_BOOTSTRAP') AND require dirname(__FILE__).'/bootstrap.php';

class Picowa extends Pico
{
	protected $filters = array(
		'before' => array(),
		'after' => array(),
		'around' => array(),
	);
	protected $mappings = array();
	public $success;
	public $error;
	public $uses = array(
		'session' => 'Pw_Wrapper_Session',
		'cookie' => 'Pw_Wrapper_Cookie',
		'request' => 'Pw_Wrapper_Request',
		'post' => 'Pw_Wrapper_Post',
		'get' => 'Pw_Wrapper_Get',
		'files' => 'Pw_Wrapper_Files',
		'file' => 'Pw_File',
		'server' => 'Pw_Wrapper_Server',
		'url' => 'Pw_Url',
		'config' => 'Pw_Config',
		'template' => 'Pw_Template',
		'httpError' => 'Pw_Http_Error',
		'errorHandler' => 'Pw_Handler_Error',
		'exceptionHandler' => 'Pw_Handler_Exception',
		'context' => array('Pw_Wrapper_Array', array()),
	);

	public function __construct($options = array())
	{
		parent::__construct($options);
		$this->initializeSession();
		session_start();
		$this->initializeErrorHandler();
		$this->initializeExceptionHandler();
		$this->afterInit('url', $this->_afterInitUrl());
	}

	public function afterInitUrl($obj)
	{
		return $obj->init($this);
	}
	
	protected function initializeErrorHandler()
	{
		Pico::cfg()->use_error_handler and set_error_handler(
			$this->_errorHandler->_handleError($this), error_reporting()
		);
	}

	protected function initializeExceptionHandler()
	{
		Pico::cfg()->use_error_handler and set_exception_handler(
			$this->_exceptionHandler->_handleException($this)
		);
	}

	protected function initializeSession()
	{
		ini_set('session.serialize_handler', 'php');
		ini_set('session.name', Pico::cfg()->session_name);
		ini_set('session.cookie_lifetime', Pico::cfg()->cookie_lifetime);
		ini_set('session.auto_start', 0);
		ini_set('session.save_path', PICOWA_TEMP_PATH . 'sessions');
	}

	public function error($err, $fnCallback = false)
	{
		$this->httpError->render($this, $err, $fnCallback);
	}

	public function http($method, $url, $callback, $conditions=array())
	{
		$this->event($method, $url, $callback, $conditions, $this->mappings);
	}

	public function get($url, $callback, $conditions=array())
	{
		$this->event('get', $url, $callback, $conditions, $this->mappings);
	}

	public function post($url, $callback, $conditions=array())
	{
		$this->event('post', $url, $callback, $conditions, $this->mappings);
	}

	public function put($url, $callback, $conditions=array())
	{
		$this->event('put', $url, $callback, $conditions, $this->mappings);
	}

	public function delete($url, $callback, $conditions=array())
	{
		$this->event('delete', $url, $callback, $conditions, $this->mappings);
	}

	protected function event($httpMethod, $url, $callback, $conditions=array(), &$mappings)
	{
		if (is_string($callback)) {
			array_push($mappings, array($httpMethod, $url, $callback, $conditions));
		}
		else if (is_array($callback)) {
			array_push($mappings, array($httpMethod, $url, $callback, $conditions));
		}
		else if (is_object($callback)) {
			array_push($mappings, array($httpMethod, $url, $callback, $conditions));
		}
	}

	public function init($sPath = '')
	{
		$this->url->initRoute($this, $this->uses, $sPath);
		return $this;
	}

	public function run()
	{
		echo $this->processRequest();
	}

	protected function processRequest()
	{
		$url = $this->url;
		foreach ($this->mappings as $mapping) {
			if ($url->match($mapping[0], $mapping[1], $mapping[3])) {
				return $this->execute($mapping[2], $url, $mapping[0]);
			}
		}
		return $this->error('404');
	}

	public function setSessionValue($args)
	{
		if ($this->session->error) {
			$this->error = $this->session->error;
			$this->session->error = null;
		}
		if ($this->session->success) {
			$this->success = $this->session->success;
			$this->session->success = null;
		}
	}

	protected function setFilterCallback($kind, $callback, $url)
	{
		if (!isset($this->filters[$kind])) return $callback;
		$filters = $kind !== 'after' ? array_reverse($this->filters[$kind]) : $this->filters[$kind] ;
		foreach ($filters as $filter) {
			if ($url->match($filter[0], $filter[1], $filter[3])) {
				$callback = $kind($callback, $filter[2]);
			}
		}
		return $callback;
	}

	protected function execute($callback, $url, $method)
	{
		$method = strtoupper($method);
		$params = $url->params;
		$callback = before($callback, quote($this)->setSessionValue);
		$urlMatch = $this->factory->build('Pw_Url', array('-mountPoint' => $url->options->mountPoint));
		$this->afterInitUrl($urlMatch);
		foreach (array('around','before','after') as $kind) {
			$callback = $this->setFilterCallback($kind, $callback, $urlMatch);
		}
		$this->attach('args', array('Pw_Wrapper_Array', $params));
		return call_user_func_array($callback, $params);
	}

	public function before($httpMethod, $urls, $callback, $conditions=array())
	{
		$this->event($httpMethod, $urls, $callback, $conditions, $this->filters['before']);
	}
	public function after($httpMethod, $urls, $callback, $conditions=array())
	{
		$this->event($httpMethod, $urls, $callback, $conditions, $this->filters['after']);
	}

	public function around($httpMethod, $urls, $callback, $conditions=array())
	{
		$this->event($httpMethod, $urls, $callback, $conditions, $this->filters['around']);
	}

	public function redirect($path, $isFull = false)
	{
		$path = $this->replacePathArgs($path);
		$uri = $isFull ? $path : $this->url->path($path);
		$this->session->error = $this->error;
		$this->session->success = $this->success;
		header("Location: {$uri}");
		die();
	}

	public function render($viewName, $variableArray=array())
	{
		$variableArray['app'] = $this;
		$variableArray['viewName'] = $viewName;
		if(isset($this->error)) {
			$variableArray['error'] = $this->error;
		}
		if(isset($this->success)) {
			$variableArray['success'] = $this->success;
		}
		if (!isset($variableArray['layout'])) {
			$variableArray['layout'] = $this->options->layout;
		}
		return $this->template->render($variableArray, $viewName);
	}

	public function sendFile($filename, $contentType, $path, $type = 'attachment')
	{
		return $this->file->send($filename, $contentType, $path, $type);
	}

	public function sendDownload($filename, $path)
	{
		return $this->file->download($filename, $path);
	}

	public function replacePathArgs($path)
	{
		return $this->url->replacePathArgs($path, $this->args->_());
	}
}
