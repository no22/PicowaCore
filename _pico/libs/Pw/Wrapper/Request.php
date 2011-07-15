<?php
/**
 * Picowa $_REQUEST wrapper
 *
 * @package		Picowa
 * @since		2010-04-06
 */	
class Pw_Wrapper_Request extends Pw_Wrapper_Array 
{
	public function __construct() 
	{
		$this->subject = &$_REQUEST;
	}
}
