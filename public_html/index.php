<?php
require_once dirname(dirname(__FILE__)).'/_pico/libs/Picowa.php';

if (!include(PICOWA_BASE_PATH . 'app.php')) {
	trigger_error("Picowa application could not be found.", E_USER_ERROR);
}
