<?php

declare(strict_types=1);

namespace XoopsModules\Mtools\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *
 * @category        Module
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Request;
use Xmf\Yaml;
use XoopsModules\Mtools\Helper;
/** @var Helper $helper */

/**
 * Class SysUtility
 */
class TestdataButtons
{

    //functions for import buttons
    public static function loadButtonConfig($adminObject, $modhelper): void
    {
        $moduleDirName      = basename(dirname(__DIR__, 2));
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);
        $yamlFile           = $modhelper->path('config/admin.yml');
        $config             = Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
        $displaySampleButton = $config['displaySampleButton'];


//        $helper = Helper::getInstance();

//        if (1 == $displaySampleButton) {
//            xoops_loadLanguage('admin/modulesadmin', 'system');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA'), $modhelper->url('testdata/index.php?op=load'), 'download');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), $modhelper->url('testdata/index.php?op=save'), 'upload');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA'), $modhelper->url('testdata/index.php?op=clear'), 'alert');
//            //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), $modhelper->url( 'testdata/index.php?op=exportschema'), 'add');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
//        } else {
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
//        }

        $sampleData = new TestdataSample($modhelper);

        if (1 == $displaySampleButton) {
            xoops_loadLanguage('admin/modulesadmin', 'system');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA'), $sampleData->loadData(), 'download');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), $sampleData->saveData(), 'upload');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA'), $sampleData->clearData(), 'alert');
//            //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), $sampleData->exportSchema(), 'add');
//            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), $modhelper->url('admin/index.php?op=hide_buttons'), 'delete');



                        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA'), $modhelper->url('testdata/index.php?op=load'), 'download');
                        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), $modhelper->url('testdata/index.php?op=save'), 'upload');
                        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA'), $modhelper->url('testdata/index.php?op=clear'), 'alert');
                        //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), $modhelper->url( 'testdata/index.php?op=exportschema'), 'add');
                        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');






        } else {
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), $modhelper->url('admin/index.php?op=show_buttons'), 'add');
        }




    }


    //$modhelper->url('admin/index.php?op=show_buttons')

    public static function hideButtons($modhelper): void
    {
        $yamlFile            = $modhelper->path('config/admin.yml');
        $app                        = [];
        $app['displaySampleButton'] = 0;
        Yaml::save($app, $yamlFile);
        redirect_header($modhelper->url('admin/index.php'), 0, '');
    }

    public static function showButtons($modhelper): void
    {
        $yamlFile            = $modhelper->path('config/admin.yml');
        $app                        = [];
        $app['displaySampleButton'] = 1;
        Yaml::save($app, $yamlFile);
        redirect_header($modhelper->url('admin/index.php'), 0, '');
    }
}
