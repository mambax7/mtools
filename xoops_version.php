<?php

global $xoopsConfig;

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$modversion                            = [];
$modversion['version']                 = '0.1';
$modversion['release_date']            = '2020/09/04';
$modversion['module_status']           = 'Alpha 1';
$modversion['name']                    = _MI_MTOOLS_NAME;
$modversion['description']             = _MI_MTOOLS_DESC;
$modversion['author']                  = 'Mamba (mambax7@gmail.com)';
$modversion['credits']                 = '';
$modversion['help']                    = 'page=help';
$modversion['license']                 = 'GNU GPL 2.0 or later';
$modversion['license_url']             = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image']                   = 'assets/images/logo'. ucfirst($xoopsConfig['language']) . '.png';
$modversion['dirname']                 = $moduleDirName;
$modversion['module_website_url']      = 'https://xoops.org';
$modversion['module_website_name']     = 'XOOPS';
$modversion['author_website_url']      = 'https://xoops.org';
$modversion['author_website_name']     = 'Mamba';
$modversion['min_php']                 = '7.2';
$modversion['min_xoops']               = '2.5.10';
$modversion['min_admin']               = '1.2';
$modversion['min_db']                  = ['mysql' => '5.5'];
$modversion['paypal']                  = [];
$modversion['paypal']['business']      = 'mambax7@gmail.com';
$modversion['paypal']['item_name']     = 'Donation : ' . _MI_MTOOLS_NAME;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---Table structure---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][1]        = 'mtools_setup';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---管理介面設定---//
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 0;

//---安裝設定---//
$modversion['onInstall']   = 'include/onInstall.php';
$modversion['onUpdate']    = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---樣板設定---//
$i                                          = 1;
$modversion['templates'][$i]['file']        = 'mtools_adm_index.tpl';
$modversion['templates'][$i]['description'] = 'mtools_adm_index.tpl';

//---偏好設定---//
$i = 1;

$modversion['config'][$i]['name']        = 'use_pin';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_TITLE4';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_DESC4';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'auto_charset';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_TITLE5';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_DESC5';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'syntaxhighlighter_themes';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_TITLE6';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_DESC6';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'shThemeMonokai';
$modversion['config'][$i]['options']     = [
    'shThemeDefault'    => 'shThemeDefault',
    'shThemeDjango'     => 'shThemeDjango',
    'shThemeEclipse'    => 'shThemeEclipse',
    'shThemeEmacs'      => 'shThemeEmacs',
    'shThemeFadeToGrey' => 'shThemeFadeToGrey',
    'shThemeMDUltra'    => 'shThemeMDUltra',
    'shThemeMidnight'   => 'shThemeMidnight',
    'shThemeRDark'      => 'shThemeRDark',
    'shThemeMonokai'    => 'shThemeMonokai',
];

$i++;
$modversion['config'][$i]['name']        = 'syntaxhighlighter_version';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_TITLE7';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_DESC7';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'syntaxhighlighter';
$modversion['config'][$i]['options']     = ['syntaxhighlighter 2' => 'syntaxhighlighter_2', 'syntaxhighlighter 3' => 'syntaxhighlighter'];

$i++;
$modversion['config'][$i]['name']        = 'uploadcare_publickey';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_TITLE8';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_DESC8';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

$i++;
$modversion['config'][$i]['name']        = 'use_codemirror';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_USE_CODEMIRROR';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_USE_CODEMIRROR_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'image_max_width';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_IMAGE_MAX_WIDTH';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_IMAGE_MAX_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1280';

$i++;
$modversion['config'][$i]['name']        = 'image_max_height';
$modversion['config'][$i]['title']       = '_MI_MTOOLS_IMAGE_MAX_HEIGHT';
$modversion['config'][$i]['description'] = '_MI_MTOOLS_IMAGE_MAX_HEIGHT_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1280';

$i                                       = 0;
$modversion['blocks'][$i]['file']        = 'mtools_qrcode.php';
$modversion['blocks'][$i]['name']        = _MI_MTOOLS_QRCODE_BLOCK_NAME;
$modversion['blocks'][$i]['description'] = _MI_MTOOLS_QRCODE_BLOCK_DESC;
$modversion['blocks'][$i]['show_func']   = 'mtools_qrcode';
$modversion['blocks'][$i]['template']    = 'mtools_qrcode_block.tpl';
$modversion['blocks'][$i]['edit_func']   = 'mtools_qrcode_edit';
$modversion['blocks'][$i]['options']     = '120';

$i++;
$modversion['blocks'][$i]['file']        = 'mtools_app.php';
$modversion['blocks'][$i]['name']        = _MI_MTOOLS_APP_BLOCK_NAME;
$modversion['blocks'][$i]['description'] = _MI_MTOOLS_APP_BLOCK_DESC;
$modversion['blocks'][$i]['show_func']   = 'mtools_app';
$modversion['blocks'][$i]['template']    = 'mtools_app_block.tpl';
$modversion['blocks'][$i]['edit_func']   = 'mtools_app_edit';
$modversion['blocks'][$i]['options']     = '120|v';
