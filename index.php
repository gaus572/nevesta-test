<?php

define('DOCROOT', dirname(__FILE__).'/');
define('MODELROOT', DOCROOT.'classes/model/');
define('CONTROLLERROOT', DOCROOT.'classes/controller/');
define('VIEWROOT', DOCROOT.'views/');
define('SYSTEMROOT', DOCROOT.'system/');

require_once DOCROOT.'config/db.php';
require_once DOCROOT.'classes/db.php';
require_once DOCROOT.'classes/url.php';

require_once SYSTEMROOT.'router.php';

?>