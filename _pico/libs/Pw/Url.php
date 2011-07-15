<?php
/**
 * Pw_Url
 *
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Url extends Pico
{
	public $uses = array(
		'-mountPoint' => PICOWA_MOUNT_POINT,
	);
	public $url;
	public $method;
	public $conditions;
	public $params = array();
	public $match = false;
	public $serverReqUri;
	public $requestUrl;
	public $requestMethod;

	public function init($app)
	{
		$mountPoint = $this->options->mountPoint;
		$this->requestMethod = $app->server->REQUEST_METHOD;
		$this->serverReqUri = $app->server->REQUEST_URI;
		$this->requestUrl = str_replace($mountPoint, '', $this->serverReqUri);
		return $this;	
	}

	public function match($httpMethod, $url, $conditions=array(), $mountPoint = null)
	{
		$requestUri = is_null($mountPoint) ? $this->requestUrl : str_replace($mountPoint, '', $this->serverReqUri) ;
		$requestMethod = $this->requestMethod;
		$this->method = strtoupper($httpMethod);
		$this->url = $url;
		$this->conditions = $conditions;
		$this->match = false;
		$httpMethods = explode('|', strtoupper($httpMethod));
		if ($httpMethod === '*' || in_array($requestMethod, $httpMethods)) {
			$paramNames = array();
			$paramValues = array();
			preg_match_all('@:([a-zA-Z]+)@', $url, $paramNames, PREG_PATTERN_ORDER);
			$paramNames = $paramNames[1];
			$regexedUrl = preg_replace_callback('@:[a-zA-Z_]+@', array($this, 'regexValue'), $url);
			if (preg_match('@^' . $regexedUrl . '(?:\?.*)?$@', $requestUri, $paramValues)) {
				array_shift($paramValues);
				foreach ($paramNames as $i => $paramName) {
					$this->params[$paramName] = $paramValues[$i];
				}
				$this->match = true;
			}
		}
		return $this->match;
	}

	public function path($path)
	{
		return PICOWA_ROOT_URL . $this->options->mountPoint . $path;
	}

	protected function regexValue($matches)
	{
		$key = strtr($matches[0], array(':' => ''));
		if (array_key_exists($key, $this->conditions)) {
			return '(' . $this->conditions[$key] . ')';
		}
		else {
			return '([a-zA-Z0-9_]+)';
		}
	}

	public function extractQuery($sUrl = null)
	{
		$sUrl = is_null($sUrl) ? $this->requestUrl : $sUrl ;
		$aUrl = parse_url($sUrl);
		$aQuery = array();
		if (isset($aUrl['query'])) {
			parse_str($aUrl['query'], $aQuery);
		}
		return $aQuery;
	}

	public function makeQuery($aQuery, $sPath = null, $bShort = false)
	{
		$sPath = is_null($sPath) ? PICOWA_REQUEST_URL : $sPath ;
		$sQuery = http_build_query($aQuery);
		if ($bShort) {
			return $sPath.'?'.$sQuery;
		}
		return $this->path($sPath).'?'.$sQuery;
	}

	public function initRoute($app, $aComponents, $sPath = '', $aConditions = array(), $pico = null)
	{
		$pico = is_null($pico) ? $app : $pico ;
		foreach ($aComponents as $route => $controller) {
			if (startsWith($route, '/')) {
				$route = substr($route, 1);
				$this->match('*', "{$sPath}/{$route}/.*") and $pico->{$route}->init($app,"{$sPath}/{$route}", $aConditions);
			}
		}
	}

	public function replacePathArgs($path, $args)
	{
		if (strpos($path, ':') === false) return $path;
		$params = array();
		foreach ($args as $k => $v) {
			$params[':'.$k] = $v;
		}
		return strtr($path, $params);
	}
}
