<?php
/**
 * Picowa Bootstrap
 *
 * @package		Picowa
 * @since		2010-04-09
 */

/**
 * Application Constants
 */
	// define directory separator short alias
	!defined('DS') and define('DS', DIRECTORY_SEPARATOR);

	// define root application directory
	!defined('PICOWA_ROOT') and define('PICOWA_ROOT', dirname(dirname(dirname(__FILE__))) . DS);

	// define app path
	!defined('PICOWA_BASE_PATH') and define('PICOWA_BASE_PATH', dirname(dirname(__FILE__)) . DS);

	// define lib path
	!defined('PICOWA_LIB_PATH') and define('PICOWA_LIB_PATH', dirname(__FILE__) . DS);

	// define extra lib path
	!defined('PICOWA_EXTRA_LIB_PATH') and define('PICOWA_EXTRA_LIB_PATH', PICOWA_ROOT . '_lib' . DS);

	// define application library path
	!defined('PICOWA_APP_LIB_PATH') and define('PICOWA_APP_LIB_PATH', PICOWA_BASE_PATH . 'app' . DS);

	// define view path
	!defined('PICOWA_VIEW_PATH') and define('PICOWA_VIEW_PATH', PICOWA_BASE_PATH . 'views' . DS);

	// define config path
	!defined('PICOWA_CONF_PATH') and define('PICOWA_CONF_PATH', PICOWA_BASE_PATH . 'config' . DS);

	// define temporary path
	!defined('PICOWA_TEMP_PATH') and define('PICOWA_TEMP_PATH', PICOWA_BASE_PATH . 'temp' . DS);

	// define root url
	if (!defined('PICOWA_ROOT_URL')) {
		$s = isset($_SERVER['HTTPS']) ? 's' : '' ;
		$httpHost = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '' ;
		$port = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '' ;
		define('PICOWA_ROOT_URL', 'http'.$s.'://'.$httpHost.$port);
	}

	// define mount point
	$url = '';
	if (!defined('PICOWA_MOUNT_POINT')) {
		$url = isset($_GET['__url__']) ? $_GET['__url__'] : '' ;
		list($reqUrl) = explode('?',$_SERVER['REQUEST_URI']);
		define('PICOWA_MOUNT_POINT', substr($reqUrl, 0, -strlen($url)-1));
	}
	!defined('PICOWA_REQUEST_URL') and define('PICOWA_REQUEST_URL', '/'.$url);

/**
 * Include Picowa Autoload
 */
	if (!include(PICOWA_LIB_PATH . 'PreLoad.php')) {
		trigger_error("Picowa library could not be found ". PICOWA_LIB_PATH . 'PreLoad.php'.".", E_USER_ERROR);
	}

	if (!include(PICOWA_LIB_PATH . 'AutoLoad.php')) {
		trigger_error("Picowa library could not be found ". PICOWA_LIB_PATH . 'AutoLoad.php'.".", E_USER_ERROR);
	}

/**
 * Environment Constants
 */
 	$oCfg = Pw_Component_Factory::set('Pw_Component_Builder')->build('Pw_Config',
		array(
			'production' => array(
				'server' => '',
				'admin_email' => '',
				'session_name' => 'picowa_session',
				'cookie_lifetime' => 0,
				'use_debug_mail' => 1,
				'use_debug_log' => 1,
				'use_error_handler' => 1,
				'builder' => 'Pw_Component_Builder',
			),
			'development' => array(
				'server' => '127.0.0.1',
				'admin_email' => '',
				'session_name' => 'picowa_session',
				'cookie_lifetime' => 0,
				'use_debug_mail' => 0,
				'use_debug_log' => 0,
				'use_error_handler' => 0,
				'builder' => 'Pw_Component_Builder',
			),
		), 'picowa_config'
	);

	// define debug mode
	if (!defined('PICOWA_DEBUG_MODE')) {
		$devServer = $oCfg->development('server');
		$prdServer = $oCfg->production('server');
		$debugMode = 0;
		if ($devServer !== '' && ($_SERVER['SERVER_ADDR'] === $devServer || $_SERVER['SERVER_NAME'] === $devServer)) {
			$debugMode = 1;
		}
		else if ($prdServer !== '' && ($_SERVER['SERVER_ADDR'] === $prdServer || $_SERVER['SERVER_NAME'] === $prdServer)) {
			$debugMode = 0;
		}
		define('PICOWA_DEBUG_MODE', $debugMode);
	}
	$configKey = PICOWA_DEBUG_MODE ? 'development' : 'production' ;
	Pw_Component_Factory::set($oCfg->{$configKey}('builder'));
	Pico::cfg(Pw_Component_Factory::get('Pw_Wrapper_Array', $oCfg->{$configKey}));

	define('PICOWA_BOOTSTRAP', true);