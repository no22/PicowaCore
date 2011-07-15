<?php
/**
 * Curry
 * カリー化もどきを実現するクラス
 * http://d.hatena.ne.jp/anatoo/20090402/1238603946
 * @package		Bind
 * @since		2009-8-29
 */
!count(debug_backtrace()) and require_once dirname(__FILE__)."/AutoLoad.php";
/**
 * Curry
 */
class Curry
{
	protected $callback, $bind, $before, $after, $around;
	
	protected function __construct($callback, Array $bind)
	{
		if(!is_callable($callback)) throw new InvalidArgumentException('$callback must be callable');
		list($this->callback, $this->bind) = func_get_args();
	}
	
	static function make($callback, Array $bind)
	{
		return $bind ? array(new self($callback, $bind), 'invoke') : $callback;
	}

	static function makeWithFilter($callback, $before = null, $after = null, $around = null)
	{
		return array(ref(new self($callback, array()))->before($before)->after($after)->around($around), 'invoke');
	}
	
	function before($before = null)
	{
		$this->before = $before;
		return $this;
	}

	function after($after = null)
	{
		$this->after = $after;
		return $this;
	}

	function around($around = null)
	{
		$this->around = $around;
		return $this;
	}
	
	function invoke()
	{
		$args = func_get_args();
		if ($this->before) {
			$returnArgs = call_user_func($this->before, $args);
			if (!is_array($returnArgs) && !is_null($returnArgs)) {
				return $returnArgs;
			}
			$args = !is_null($returnArgs) ? $returnArgs : $args ;
		}
		$args = array_merge($this->bind, $args);
		if ($this->around) {
			$returnValue = call_user_func_array($this->around, array($this->callback, $args));
		}
		else {
			$returnValue = call_user_func_array($this->callback, $args);
		}
		if ($this->after) {
			return call_user_func($this->after, $returnValue);
		}
		return $returnValue;
	}
	/**
	 * __invoke
	 * PHP5.3用
	 */
	public function __invoke()
	{
		return call_user_func_array(array($this,'invoke'),func_get_args());
	}

	public function getParams()
	{
		return array_slice(callbackParams($this->callback), count($this->bind));
	}

	static function debug($callback,$tag) {
		dbglog($tag);
		if(is_string($callback)) {
			dbglog($callback);
		}
		elseif(is_array($callback)) {
			if(is_object($callback[0])) {
				dbglog(get_class($callback[0]).'->'.$callback[1]);
			}
			else if(is_string($callback[0])) {
				dbglog($callback[0].'::'.$callback[1]);
			}
		}
		else if (is_object($callback)) {
			dbglog(get_class($callback));
		}
	}
}

//
// DocTest
//
!count(debug_backtrace()) and doctest(__FILE__);
