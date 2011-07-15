<?php
/**
 * Pw_Component_Factory
 * 
 * @package		Picowa
 * @since		2010-04-15
 */
class Pw_Component_Factory
{
	protected static $defaultBuilderClass = 'Pw_Component_Builder';
	protected static $factory = null;
	protected $builder = null;
	
	public static function init($sContainer = null)
	{
		$sContainer = is_null($sContainer) ? self::$defaultBuilderClass : $sContainer ;
		self::$defaultBuilderClass = $sContainer;
	}

	public static function getInstance($sContainer = null)
	{
		if (is_null(self::$factory)) {
			self::$factory = new self($sContainer);
		}
		return self::$factory;
	}

	public static function get()
	{
		$args = func_get_args();
		$sClass = array_shift($args);
		$sMethod = 'build' . $sClass;
		$factory = self::getInstance();
		if (method_exists($factory->builder(), $sMethod)) {
			return call_user_func_array(array($factory->builder(), $sMethod), $args);
		}
		return $factory->newObj($sClass, $args);
	}

	public static function set($sContainer)
	{
		self::init($sContainer);
		$factory = self::getInstance();
		return $factory->builder(new $sContainer($factory));
	}

	public function __construct($sContainer = null)
	{
		$sContainer = is_null($sContainer) ? self::$defaultBuilderClass : $sContainer ;
		$this->builder = new $sContainer($this);
	}
	
	public function builder($oBuilder = null)
	{
		if (is_null($oBuilder)) return $this->builder;
		$this->builder = $oBuilder;
		return $this;
	}
	
	public function build()
	{
		$args = func_get_args();
		$sClass = array_shift($args);
		return $this->buildArray($sClass, $args);
	}

	public function buildArray($sClass, $args)
	{
		$sMethod = 'build' . $sClass;
		if (method_exists($this->builder(), $sMethod)) {
			return call_user_func_array(array($this->builder(), $sMethod), $args);
		}
		return $this->newObj($sClass, $args);
	}
	
	protected function newObj($sClass, $args = array())
	{
		if (!$sClass) {
			throw new Pw_Exception_NewInstance('Class name must be a valid object or a string');
		}
		if (count($args) === 0) return new $sClass;
		$refMethod = new ReflectionMethod($sClass,  '__construct');
		$params = $refMethod->getParameters();
		$re_args = array();
		foreach($params as $key => $param) {
			if (isset($args[$key])) {
				if ($param->isPassedByReference()) {
					$re_args[$key] = &$args[$key];
				}
				else {
					$re_args[$key] = $args[$key];
				}
			}
		}
		$refClass = new ReflectionClass($sClass);
		return $refClass->newInstanceArgs((array) $re_args);
	}
}
