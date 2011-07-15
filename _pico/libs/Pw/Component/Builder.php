<?php
/**
 * Pw_Component_Builder
 * 
 * @package		Picowa
 * @since		2010-04-15
 */
class Pw_Component_Builder
{
	protected $factory = null;
	
	public function __construct($oFactory)
	{
		$this->factory = $oFactory;
	}
}
