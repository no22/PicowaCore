<?php
/**
 * Quotation
 * メソッド取り出しのためのラッパークラス
 * http://d.hatena.ne.jp/anatoo/20090402/1238603946
 * @package		Bind
 * @since		2009-8-29
 */
 
!count(debug_backtrace()) and require_once dirname(__FILE__)."/AutoLoad.php";

/**
 * Quotation
 */
class Quotation
{
	protected $obj;
	function __construct($obj)
	{
		$this->obj = $obj;
	}
	function __get($name)
	{
		return array($this->obj, $name);
	}
	function __call($name, $args)
	{
		return Curry::make($this->{$name}, $args);
	}
}

//
// DocTest
//
!count(debug_backtrace()) and doctest(__FILE__);
