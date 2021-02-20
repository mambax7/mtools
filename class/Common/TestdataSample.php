<?php

namespace XoopsModules\Mtools\Common;

use XoopsModules\Mtools\{Helper,
    Utility
};
use \Xmf\Database\TableLoad;

class TestdataSample
{
    public $language;
    public $xoopsConfig;
    public $modHelper;
    public $moduleDirName;
    public $moduleDirNameUpper;

    /**
     * TestdataSample constructor.
     * @param $modHelper
     */
    public function __construct($modHelper)
    {
        global $xoopsConfig;
        $this->modHelper          = $modHelper;
        $helper                   = Helper::getInstance();
        $this->moduleDirName      = $helper->getDirname();
        $this->moduleDirNameUpper = mb_strtoupper($helper->getDirname());
        $helper->loadLanguage('common');

        $this->language = 'english/';
        if (is_dir(__DIR__ . '/' . $xoopsConfig['language'])) {
            $this->language = $xoopsConfig['language'] . '/';
        }
    }

    // XMF TableLoad for SAMPLE data

    public function loadData(): void
    {
        $utility      = new Utility();
        $configurator = new Configurator($this->modHelper->path());
        $tables = $this->modHelper->getModule()->getInfo('tables');

        // load module tables
        foreach ($tables as $table) {
            $tabledata = \Xmf\Yaml::readWrapped($this->language . $table . '.yml');
            \Xmf\Database\TableLoad::truncateTable($table);
            \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
        }

        // load permissions
        $table     = 'group_permission';
        $tabledata = \Xmf\Yaml::readWrapped($this->language . $table . '.yml');
        $mid = $this->modHelper->getModule()->getVar('mid');
        $this->loadTableFromArrayWithReplace($table, $tabledata, 'gperm_modid', $mid);

        //  ---  COPY test folder files ---------------
        if (is_array($configurator->copyTestFolders) && count($configurator->copyTestFolders) > 0) {
            //        $file =  dirname(__DIR__) . '/testdata/images/';
            foreach (array_keys($configurator->copyTestFolders) as $i) {
                $src  = $configurator->copyTestFolders[$i][0];
                $dest = $configurator->copyTestFolders[$i][1];
                $utility::rcopy($src, $dest);
            }
        }
        redirect_header($this->modHelper->url('admin/index.php'), 1, constant('CO_' . $this->moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA_SUCCESS'));
    }

    public function saveData(): void
    {
        global $xoopsConfig;
        $tables = $this->modHelper->getModule()->getInfo('tables');

        $languageFolder = $this->modHelper->path('testdata/' . $this->language);
        if (!file_exists($languageFolder . '/')) {
            Utility::createFolder($languageFolder . '/');
        }
        $exportFolder = $languageFolder . '/Exports-' . date('Y-m-d-H-i-s') . '/';
        Utility::createFolder($exportFolder);

        // save module tables
        foreach ($tables as $table) {
            TableLoad::saveTableToYamlFile($table, $exportFolder . $table . '.yml');
        }

        // save permissions
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('gperm_modid', $this->modHelper->getModule()->getVar('mid')));
        $skipColumns[] = 'gperm_id';
        TableLoad::saveTableToYamlFile('group_permission', $exportFolder . 'group_permission.yml', $criteria, $skipColumns);
        unset($criteria);

        redirect_header($this->modHelper->url('admin/index.php'), 1, constant('CO_' . $this->moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA_SUCCESS'));
    }

    public function exportSchema(): void
    {
        try {
            // TODO set exportSchema
            //        $migrate = new Migrate($moduleDirName);
            //        $migrate->saveCurrentSchema();
            //
            //        redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS'));
        } catch (\Throwable $e) {
            exit(constant('CO_' . $this->moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR'));
        }
    }

    public function clearData(): void
    {
        // Load language files
        $this->modHelper->loadLanguage('common');
        $tables = $this->modHelper->getModule()->getInfo('tables');
        // truncate module tables
        foreach ($tables as $table) {
            \Xmf\Database\TableLoad::truncateTable($table);
        }
        redirect_header($this->modHelper->url('admin/index.php'), 1, constant('CO_' . $this->moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA_OK'));
    }

    /**
     * loadTableFromArrayWithReplace
     *
     * @param string $table  value with should be used insead of original value of $search
     *
     * @param array  $data   array of rows to insert
     *                       Each element of the outer array represents a single table row.
     *                       Each row is an associative array in 'column' => 'value' format.
     * @param string $search name of column for which the value should be replaced
     * @param        $replace
     * @return void number of rows inserted
     */
    private function loadTableFromArrayWithReplace($table, $data, $search, $replace)
    {
        /** @var \XoopsMySQLDatabase $db */
        $db = \XoopsDatabaseFactory::getDatabaseConnection();

        $prefixedTable = $db->prefix($table);
        $count         = 0;

        $sql = 'DELETE FROM ' . $prefixedTable . ' WHERE `' . $search . '`=' . $db->quote($replace);

        $result = $db->queryF($sql);

        foreach ($data as $row) {
            $insertInto  = 'INSERT INTO ' . $prefixedTable . ' (';
            $valueClause = ' VALUES (';
            $first       = true;
            foreach ($row as $column => $value) {
                if ($first) {
                    $first = false;
                } else {
                    $insertInto  .= ', ';
                    $valueClause .= ', ';
                }

                $insertInto .= $column;
                if ($search === $column) {
                    $valueClause .= $db->quote($replace);
                } else {
                    $valueClause .= $db->quote($value);
                }
            }

            $sql = $insertInto . ') ' . $valueClause . ')';

            $result = $db->queryF($sql);
            if (false !== $result) {
                ++$count;
            }
        }
    }
}
