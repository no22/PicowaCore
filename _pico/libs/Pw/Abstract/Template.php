<?php
/**
 * Pw_Abstract_Template
 * Picowa HTML Template Abstract Class
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Abstract_Template extends Pico
{
	public $uses = array(
		'-dirpath' => PICOWA_VIEW_PATH,
		'-filename' => null,
	);

	public function dirpath($value = null)
	{
		if (is_null($value)) return $this->options->dirpath;
		$this->options->dirpath = $value;
		return $this;
	}

	public function name($value = null)
	{
		if (is_null($value)) return $this->options->filename;
		$this->options->filename = $value;
		return $this;
	}

	public function renderTemplate($context, $filename = null)
	{
		$filename = is_null($filename) ? $this->options->filename : $filename ;
		extract($context);
		ob_start();
		include($this->options->dirpath . $filename . '.php');
		return ob_get_clean();
	}

	public function render($context, $filename = null)
	{
		return $this->renderTemplate($context, $filename);
	}
}
