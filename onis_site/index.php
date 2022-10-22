<?php

use Onis\Framework\Web_app;

define('ROOT_APP', realpath('.'). DIRECTORY_SEPARATOR);
define('TMP_DIR', ROOT_APP .'app'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR);

require_once(realpath(ROOT_APP . '..'). DIRECTORY_SEPARATOR .'onis_files'. DIRECTORY_SEPARATOR . 'autoloader.php');

Web_app::getInstance(ROOT_APP)->processRequest();