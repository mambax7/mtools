<?php

use Xmf\Module\Admin;
use XoopsModules\Mtools\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */


include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    //    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}

$adminmenu = [];

//$i = 1;
//$adminmenu[$i]['title'] = _MI_MTOOLS_ADMIN_HOME;
//$adminmenu[$i]['link'] = 'admin/index.php';
//$adminmenu[$i]['desc'] = _MI_MTOOLS_ADMIN_HOME;
//$adminmenu[$i]['icon'] = 'images/admin/home.png';
//
//++$i;
//$adminmenu[$i]['title'] = _MI_MTOOLS_ADMENU1;
//$adminmenu[$i]['link'] = 'admin/main.php';
//$adminmenu[$i]['desc'] = _MI_MTOOLS_ADMENU1;
//$adminmenu[$i]['icon'] = 'images/admin/folder_txt.png';
//
//++$i;
//$adminmenu[$i]['title'] = _MI_MTOOLS_ADMIN_ABOUT;
//$adminmenu[$i]['link'] = 'admin/about.php';
//$adminmenu[$i]['desc'] = _MI_MTOOLS_ADMIN_ABOUT;
//$adminmenu[$i]['icon'] = 'images/admin/about.png';


$adminmenu[] = [
    'title' => _MI_MTOOLS_MENU_HOME,
    'link' => 'admin/index.php',
    'icon' => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_MTOOLS_MENU_01,
    'link' => 'admin/main.php',
    'icon' => $pathIcon32 . '/manage.png',
];

// Blocks Admin
//$adminmenu[] = [
//    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
//    'link' => 'admin/blocksadmin.php',
//    'icon' => $pathIcon32 . '/block.png',
//];
//
//if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
//    $adminmenu[] = [
//        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
//        'link' => 'admin/migrate.php',
//        'icon' => $pathIcon32 . '/database_go.png',
//    ];
//}

$adminmenu[] = [
    'title' => _MI_MTOOLS_MENU_ABOUT,
    'link' => 'admin/about.php',
    'icon' => $pathIcon32 . '/about.png',
];
