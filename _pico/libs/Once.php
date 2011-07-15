<?php
/**
 * Once
 * @package
 * @since		2009-8-31
 */
class Once
{
	protected $callback = null;
	protected $value = null;
	protected $invoked = false;

	/**
	 * __construct
	 * @param func $fnCallback
	 * @return
	 */
	public function __construct($fnCallback)
	{
		$this->callback = $fnCallback;
		$this->value = null;
		$this->invoked = false;
	}

	/**
	 * __invoke
	 * @param
	 * @return
	 */
	public function __invoke()
	{
		return $this->invoke();
	}

	/**
	 * invoke
	 * @param
	 * @return
	 */
	public function invoke()
	{
		if (!$this->invoked) {
			$this->value = call_user_func($this->callback);
			$this->invoked = true;
		}
		return $this->value;
	}
}
