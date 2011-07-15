<?php
/**
 * Pw_Template
 * Picowa HTML Template Class
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Template extends Pw_Abstract_Template
{
	public function render($context, $filename = null)
	{
		if (isset($context['layout']) && !empty($context['layout'])) {
			$context['content'] = $this->renderTemplate($context, $filename);
			return $this->renderTemplate($context, $context['layout']);
		}
		return $this->renderTemplate($context, $filename);
	}
}

