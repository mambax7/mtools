<?php

use XoopsModules\Mtools\{
    Helper
};

if (!defined('XOOPS_ROOT_PATH')) {
    require_once dirname(dirname(__DIR__)) . '/mainfile.php';
} else {
    require_once XOOPS_ROOT_PATH . '/mainfile.php';
}
$moduleDirName = basename(__DIR__);

if (!defined('MTOOLS_PATH')) {
    define('MTOOLS_PATH', XOOPS_ROOT_PATH . '/modules/mtools');
}

if (!defined('MTOOLS_URL')) {
    define('MTOOLS_URL', XOOPS_URL . '/modules/mtools');
}

require __DIR__ . '/preloads/autoloader.php';

xoops_loadLanguage('main', $moduleDirName);
