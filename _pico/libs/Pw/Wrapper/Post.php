<?php
/**
 * Picowa $_POST wrapper
 *
 * @package		Picowa
 * @since		2010-04-06
 */	
class Pw_Wrapper_Post extends Pw_Wrapper_Array
{
	public function __construct() 
	{
		$this->subject = &$_POST;
	}
}
