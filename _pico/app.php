<?php
/**
 * Pico Web Application Framework Sample
 * @package		Picowa
 * @since		2010-04-09
 */

class Application extends Picowa
{
	function hello()
	{
		$name = $this->args->name;
		return "Hello {$name}!\n";
	}
}

$app = new Application;

$app->get('/:name', $app->_hello(), array('name'=>'.*'));

$app->init()->run();
