<?php

namespace XoopsModules\Mtools\Common;

/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 *
 * @category        Module
 * @author          XOOPS Development Team
 * @copyright       XOOPS Project
 * @link            https://xoops.org
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Request;

//require __DIR__ . '/admin_header.php';

class Blocksadmin
{
    /**
     * @var \XoopsMySQLDatabase|null
     */
    public $db;
    public $modHelper;
    public $moduleDirName;
    public $moduleDirNameUpper;

    public function __construct($db, $modHelper)
    {
        $this->db           = $db;
        $this->modHelper    = $modHelper;
        $this->moduleDirName      = \basename(\dirname(__DIR__, 2));
        $this->moduleDirNameUpper = \mb_strtoupper($this->moduleDirName);
        \xoops_loadLanguage('admin', 'system');
        \xoops_loadLanguage('admin/blocksadmin', 'system');
        \xoops_loadLanguage('admin/groups', 'system');
        \xoops_loadLanguage('common', $this->moduleDirName);
        \xoops_loadLanguage('blocksadmin', $this->moduleDirName);
    }

    public function listBlocks()
    {
        global $xoopsModule, $pathIcon16;
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
        //        xoops_loadLanguage('admin', 'system');
        //        xoops_loadLanguage('admin/blocksadmin', 'system');
        //        xoops_loadLanguage('admin/groups', 'system');
        //        xoops_loadLanguage('common', $moduleDirName);
        //        xoops_loadLanguage('blocks', $moduleDirName);

        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        /** @var \XoopsMemberHandler $memberHandler */
        $memberHandler = \xoops_getHandler('member');
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = \xoops_getHandler('groupperm');
        $groups           = $memberHandler->getGroups();
        $criteria         = new \CriteriaCompo(new \Criteria('hasmain', 1));
        $criteria->add(new \Criteria('isactive', 1));
        $module_list     = $moduleHandler->getList($criteria);
        $module_list[-1] = \_AM_SYSTEM_BLOCKS_TOPPAGE;
        $module_list[0]  = \_AM_SYSTEM_BLOCKS_ALLPAGES;
        \ksort($module_list);
        echo "
        <h4 style='text-align:left;'>" . \constant('CO_' . $this->moduleDirNameUpper . '_' . 'BADMIN') . '</h4>';
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "' name='blockadmin' method='post'>";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<table width='100%' class='outer' cellpadding='4' cellspacing='1'>
        <tr valign='middle'><th align='center'>"
             . \_AM_SYSTEM_BLOCKS_TITLE
             . "</th><th align='center' nowrap='nowrap'>"
             . \constant('CO_' . $this->moduleDirNameUpper . '_' . 'SIDE')
             . '<br>'
             . _LEFT
             . '-'
             . _CENTER
             . '-'
             . _RIGHT
             . "</th><th align='center'>"
             . \constant(
                 'CO_' . $this->moduleDirNameUpper . '_' . 'WEIGHT'
             )
             . "</th><th align='center'>"
             . \constant('CO_' . $this->moduleDirNameUpper . '_' . 'VISIBLE')
             . "</th><th align='center'>"
             . \_AM_SYSTEM_BLOCKS_VISIBLEIN
             . "</th><th align='center'>"
             . \_AM_SYSTEM_ADGS
             . "</th><th align='center'>"
             . \_AM_SYSTEM_BLOCKS_BCACHETIME
             . "</th><th align='center'>"
             . \constant('CO_' . $this->moduleDirNameUpper . '_' . 'ACTION')
             . '</th></tr>
        ';
        $block_arr   = \XoopsBlock::getByModule($xoopsModule->mid());
        $block_count = \count($block_arr);
        $class       = 'even';
        $cachetimes  = [
            0 => _NOCACHE,
            30 => sprintf(_SECONDS, 30),
            60 => _MINUTE,
            300 => sprintf(_MINUTES, 5),
            1800 => sprintf(_MINUTES, 30),
            3600 => _HOUR,
            18000 => sprintf(_HOURS, 5),
            86400 => _DAY,
            259200 => sprintf(_DAYS, 3),
            604800 => _WEEK,
            2592000 => _MONTH,
        ];
        foreach ($block_arr as $i) {
            $groups_perms = $grouppermHandler->getGroupIds('block_read', $i->getVar('bid'));
            $sql          = 'SELECT module_id FROM ' . $this->db->prefix('block_module_link') . ' WHERE block_id=' . $i->getVar('bid');
            $result       = $this->db->query($sql);
            $modules      = [];
            while (false !== ($row = $this->db->fetchArray($result))) {
                $modules[] = (int)$row['module_id'];
            }

            $cachetime_options = '';
            foreach ($cachetimes as $cachetime => $cachetime_name) {
                if ($i->getVar('bcachetime') == $cachetime) {
                    $cachetime_options .= "<option value='$cachetime' selected='selected'>$cachetime_name</option>\n";
                } else {
                    $cachetime_options .= "<option value='$cachetime'>$cachetime_name</option>\n";
                }
            }

            $sel0 = $sel1 = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = $ssel5 = $ssel6 = $ssel7 = '';
            if (1 === $i->getVar('visible')) {
                $sel1 = ' checked';
            } else {
                $sel0 = ' checked';
            }
            if (\XOOPS_SIDEBLOCK_LEFT === $i->getVar('side')) {
                $ssel0 = ' checked';
            } elseif (\XOOPS_SIDEBLOCK_RIGHT === $i->getVar('side')) {
                $ssel1 = ' checked';
            } elseif (\XOOPS_CENTERBLOCK_LEFT === $i->getVar('side')) {
                $ssel2 = ' checked';
            } elseif (\XOOPS_CENTERBLOCK_RIGHT === $i->getVar('side')) {
                $ssel4 = ' checked';
            } elseif (\XOOPS_CENTERBLOCK_CENTER === $i->getVar('side')) {
                $ssel3 = ' checked';
            } elseif (\XOOPS_CENTERBLOCK_BOTTOMLEFT === $i->getVar('side')) {
                $ssel5 = ' checked';
            } elseif (\XOOPS_CENTERBLOCK_BOTTOMRIGHT === $i->getVar('side')) {
                $ssel6 = ' checked';
            } elseif (\XOOPS_CENTERBLOCK_BOTTOM === $i->getVar('side')) {
                $ssel7 = ' checked';
            }
            if ('' === $i->getVar('title')) {
                $title = '&nbsp;';
            } else {
                $title = $i->getVar('title');
            }
            $name = $i->getVar('name');
            echo "<tr valign='top'><td class='$class' align='center'><input type='text' name='title["
                 . $i->getVar('bid')
                 . "]' value='"
                 . $title
                 . "'></td><td class='$class' align='center' nowrap='nowrap'>
                    <div align='center' >
                    <input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_CENTERBLOCK_LEFT
                 . "'$ssel2>
                        <input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_CENTERBLOCK_CENTER
                 . "'$ssel3>
                    <input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_CENTERBLOCK_RIGHT
                 . "'$ssel4>
                    </div>
                    <div>
                        <span style='float:right;'><input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_SIDEBLOCK_RIGHT
                 . "'$ssel1></span>
                    <div align='left'><input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_SIDEBLOCK_LEFT
                 . "'$ssel0></div>
                    </div>
                    <div align='center'>
                    <input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_CENTERBLOCK_BOTTOMLEFT
                 . "'$ssel5>
                        <input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_CENTERBLOCK_BOTTOM
                 . "'$ssel7>
                    <input type='radio' name='side["
                 . $i->getVar('bid')
                 . "]' value='"
                 . \XOOPS_CENTERBLOCK_BOTTOMRIGHT
                 . "'$ssel6>
                    </div>
                </td><td class='$class' align='center'><input type='text' name='weight["
                 . $i->getVar('bid')
                 . "]' value='"
                 . $i->getVar('weight')
                 . "' size='5' maxlength='5'></td><td class='$class' align='center' nowrap><input type='radio' name='visible["
                 . $i->getVar('bid')
                 . "]' value='1'$sel1>"
                 . _YES
                 . "&nbsp;<input type='radio' name='visible["
                 . $i->getVar('bid')
                 . "]' value='0'$sel0>"
                 . _NO
                 . '</td>';

            echo "<td class='$class' align='center'><select size='5' name='bmodule[" . $i->getVar('bid') . "][]' id='bmodule[" . $i->getVar('bid') . "][]' multiple='multiple'>";
            foreach ($module_list as $k => $v) {
                echo "<option value='$k'" . (\in_array($k, $modules) ? " selected='selected'" : '') . ">$v</option>";
            }
            echo '</select></td>';

            echo "<td class='$class' align='center'><select size='5' name='groups[" . $i->getVar('bid') . "][]' id='groups[" . $i->getVar('bid') . "][]' multiple='multiple'>";
            foreach ($groups as $grp) {
                echo "<option value='" . $grp->getVar('groupid') . "' " . (\in_array($grp->getVar('groupid'), $groups_perms) ? " selected='selected'" : '') . '>' . $grp->getVar('name') . '</option>';
            }
            echo '</select></td>';

            // Cache lifetime
            echo '<td class="' . $class . '" align="center"> <select name="bcachetime[' . $i->getVar('bid') . ']" size="1">' . $cachetime_options . '</select>
                                    </td>';

            // Actions

            echo "<td class='$class' align='center'><a href='blocksadmin.php?op=edit&amp;bid=" . $i->getVar('bid') . "'><img src=" . $pathIcon16 . '/edit.png' . " alt='" . _EDIT . "' title='" . _EDIT . "'>
                 </a> <a href='blocksadmin.php?op=clone&amp;bid=" . $i->getVar('bid') . "'><img src=" . $pathIcon16 . '/editcopy.png' . " alt='" . _CLONE . "' title='" . _CLONE . "'>
                 </a>";
            //            if ('S' !== $i->getVar('block_type') && 'M' !== $i->getVar('block_type')) {
            //                echo "&nbsp;<a href='" . XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin&amp;op=delete&amp;bid=' . $i->getVar('bid') . "'><img src=" . $pathIcon16 . '/delete.png' . " alt='" . _DELETE . "' title='" . _DELETE . "'>
            //                     </a>";
            //            }

            if ('S' !== $i->getVar('block_type') && 'M' !== $i->getVar('block_type')) {
                echo "&nbsp;
                <a href='blocksadmin.php?op=delete&amp;bid=" . $i->getVar('bid') . "'><img src=" . $pathIcon16 . '/delete.png' . " alt='" . _DELETE . "' title='" . _DELETE . "'>
                     </a>";
            }
            echo "
            <input type='hidden' name='oldtitle[" . $i->getVar('bid') . "]' value='" . $i->getVar('title') . "'>
            <input type='hidden' name='oldside[" . $i->getVar('bid') . "]' value='" . $i->getVar('side') . "'>
            <input type='hidden' name='oldweight[" . $i->getVar('bid') . "]' value='" . $i->getVar('weight') . "'>
            <input type='hidden' name='oldvisible[" . $i->getVar('bid') . "]' value='" . $i->getVar('visible') . "'>
            <input type='hidden' name='oldgroups[" . $i->getVar('groups') . "]' value='" . $i->getVar('groups') . "'>
            <input type='hidden' name='oldbcachetime[" . $i->getVar('bid') . "]' value='" . $i->getVar('bcachetime') . "'>
            <input type='hidden' name='bid[" . $i->getVar('bid') . "]' value='" . $i->getVar('bid') . "'>
            </td></tr>
            ";
            $class = ('even' === $class) ? 'odd' : 'even';
        }
        echo "<tr><td class='foot' align='center' colspan='8'>
        <input type='hidden' name='op' value='order'>
        " . $GLOBALS['xoopsSecurity']->getTokenHTML() . "
        <input type='submit' name='submit' value='" . _SUBMIT . "'>
        </td></tr></table>
        </form>
        <br><br>";
    }

    /**
     * @param int $bid
     */
    public function deleteBlock($bid)
    {
//        \xoops_cp_header();

        \xoops_loadLanguage('admin', 'system');
        \xoops_loadLanguage('admin/blocksadmin', 'system');
        \xoops_loadLanguage('admin/groups', 'system');

        $myblock = new \XoopsBlock($bid);

        $sql = \sprintf('DELETE FROM %s WHERE bid = %u', $this->db->prefix('newblocks'), $bid);
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        $sql = \sprintf('DELETE FROM %s WHERE block_id = %u', $this->db->prefix('block_module_link'), $bid);
        $this->db->queryF($sql);

        \redirect_header('blocksadmin.php?op=list', 1, _AM_DBUPDATED);
    }

    /**
     * @param int $bid
     */
    public function cloneBlock($bid)
    {
        //require __DIR__ . '/admin_header.php';
//        \xoops_cp_header();

        \xoops_loadLanguage('admin', 'system');
        \xoops_loadLanguage('admin/blocksadmin', 'system');
        \xoops_loadLanguage('admin/groups', 'system');

        $myblock = new \XoopsBlock($bid);
        $sql     = 'SELECT module_id FROM ' . $this->db->prefix('block_module_link') . ' WHERE block_id=' . (int)$bid;
        $result  = $this->db->query($sql);
        $modules = [];
        while (false !== ($row = $this->db->fetchArray($result))) {
            $modules[] = (int)$row['module_id'];
        }
        $is_custom = ('C' === $myblock->getVar('block_type') || 'E' === $myblock->getVar('block_type'));
        $block     = [
            'title'      => $myblock->getVar('title') . ' Clone',
            'form_title' => \constant('CO_' . $this->moduleDirNameUpper . '_' . 'BLOCKS_CLONEBLOCK'),
            'name'       => $myblock->getVar('name'),
            'side'       => $myblock->getVar('side'),
            'weight'     => $myblock->getVar('weight'),
            'visible'    => $myblock->getVar('visible'),
            'content'    => $myblock->getVar('content', 'N'),
            'modules'    => $modules,
            'is_custom'  => $is_custom,
            'ctype'      => $myblock->getVar('c_type'),
            'bcachetime' => $myblock->getVar('bcachetime'),
            'op'         => 'clone_ok',
            'bid'        => $myblock->getVar('bid'),
            'edit_form'  => $myblock->getOptions(),
            'template'   => $myblock->getVar('template'),
            'options'    => $myblock->getVar('options'),
        ];
        echo '<a href="blocksadmin.php">' . constant('CO_' . $this->moduleDirNameUpper . '_' . 'BADMIN') . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . \_AM_SYSTEM_BLOCKS_CLONEBLOCK . '<br><br>';
        //        $form = new Blockform();
        //        $form->render();

        echo $this->render($block);
        //        xoops_cp_footer();
        //        require_once __DIR__ . '/admin_footer.php';
        //        exit();
    }

    /**
     * @param int $bid
     * @param     $bside
     * @param     $bweight
     * @param     $bvisible
     * @param     $bcachetime
     * @param     $bmodule
     * @param     $options
     */
    public function isBlockCloned($bid, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options)
    {
        \xoops_loadLanguage('admin', 'system');
        \xoops_loadLanguage('admin/blocksadmin', 'system');
        \xoops_loadLanguage('admin/groups', 'system');

        /** @var \XoopsBlock $block */
        $block = new \XoopsBlock($bid);
        $clone = $block->xoopsClone();
        if (empty($bmodule)) {
//            \xoops_cp_header();
            \xoops_error(\sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
            \xoops_cp_footer();
            exit();
        }
        $clone->setVar('side', $bside);
        $clone->setVar('weight', $bweight);
        $clone->setVar('visible', $bvisible);
        //$clone->setVar('content', $_POST['bcontent']);
        $clone->setVar('title', Request::getString('btitle', '', 'POST'));
        $clone->setVar('bcachetime', $bcachetime);
        if (isset($options) && (\count($options) > 0)) {
            $options = \implode('|', $options);
            $clone->setVar('options', $options);
        }
        $clone->setVar('bid', 0);
        if ('C' === $block->getVar('block_type') || 'E' === $block->getVar('block_type')) {
            $clone->setVar('block_type', 'E');
        } else {
            $clone->setVar('block_type', 'D');
        }
        $newid = $clone->store();
        if (!$newid) {
//            \xoops_cp_header();
            $clone->getHtmlErrors();
            \xoops_cp_footer();
            exit();
        }
        if ('' !== $clone->getVar('template')) {
            /** @var \XoopsTplfileHandler $tplfileHandler */
            $tplfileHandler = \xoops_getHandler('tplfile');
            $btemplate      = $tplfileHandler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
            if (\count($btemplate) > 0) {
                $tplclone = $btemplate[0]->xoopsClone();
                $tplclone->setVar('tpl_id', 0);
                $tplclone->setVar('tpl_refid', $newid);
                $tplfileHandler->insert($tplclone);
            }
        }

        foreach ($bmodule as $bmid) {
            $sql = 'INSERT INTO ' . $this->db->prefix('block_module_link') . ' (block_id, module_id) VALUES (' . $newid . ', ' . $bmid . ')';
            $this->db->query($sql);
        }
        $groups = &$GLOBALS['xoopsUser']->getGroups();
        foreach ($groups as $iValue) {
            $sql = 'INSERT INTO ' . $this->db->prefix('group_permission') . ' (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (' . $iValue . ', ' . $newid . ", 1, 'block_read')";
            $this->db->query($sql);
        }
        \redirect_header('blocksadmin.php?op=list', 1, _AM_DBUPDATED);
    }

    /**
     * @param int    $bid
     * @param string $title
     * @param int    $weight
     * @param bool   $visible
     * @param string $side
     * @param int    $bcachetime
     */
    public function setOrder($bid, $title, $weight, $visible, $side, $bcachetime)
    {
        $myblock = new \XoopsBlock($bid);
        $myblock->setVar('title', $title);
        $myblock->setVar('weight', $weight);
        $myblock->setVar('visible', $visible);
        $myblock->setVar('side', $side);
        $myblock->setVar('bcachetime', $bcachetime);
        //        $myblock->store();
        /** @var \XoopsBlockHandler $blockHandler */
        $blockHandler = \xoops_getHandler('block');
        return $blockHandler->insert($myblock);
    }

    /**
     * @param int $bid
     */
    public function editBlock($bid)
    {
        //        require_once \dirname(__DIR__,2) . '/admin/admin_header.php';
//        \xoops_cp_header();
        \xoops_loadLanguage('admin', 'system');
        \xoops_loadLanguage('admin/blocksadmin', 'system');
        \xoops_loadLanguage('admin/groups', 'system');
        //        mpu_adm_menu();
        $myblock = new \XoopsBlock($bid);
        $sql     = 'SELECT module_id FROM ' . $this->db->prefix('block_module_link') . ' WHERE block_id=' . (int)$bid;
        $result  = $this->db->query($sql);
        $modules = [];
        while (false !== ($row = $this->db->fetchArray($result))) {
            $modules[] = (int)$row['module_id'];
        }
        $is_custom = ('C' === $myblock->getVar('block_type') || 'E' === $myblock->getVar('block_type'));
        $block     = [
            'title'      => $myblock->getVar('title'),
            'form_title' => \_AM_SYSTEM_BLOCKS_EDITBLOCK,
            //        'name'       => $myblock->getVar('name'),
            'side'       => $myblock->getVar('side'),
            'weight'     => $myblock->getVar('weight'),
            'visible'    => $myblock->getVar('visible'),
            'content'    => $myblock->getVar('content', 'N'),
            'modules'    => $modules,
            'is_custom'  => $is_custom,
            'ctype'      => $myblock->getVar('c_type'),
            'bcachetime' => $myblock->getVar('bcachetime'),
            'op'         => 'edit_ok',
            'bid'        => $myblock->getVar('bid'),
            'edit_form'  => $myblock->getOptions(),
            'template'   => $myblock->getVar('template'),
            'options'    => $myblock->getVar('options'),
        ];
        echo '<a href="blocksadmin.php">' . constant('CO_' . $this->moduleDirNameUpper . '_' . 'BADMIN') . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . \_AM_SYSTEM_BLOCKS_EDITBLOCK . '<br><br>';

        /** @var \XoopsThemeForm $form */ //                $form = new Blockform();
        //        $form->render($block);
        //        $form = new \XoopsThemeForm();

        echo $this->render($block);
    }

    /**
     * @param int               $bid
     * @param string            $btitle
     * @param string            $bside
     * @param int               $bweight
     * @param bool              $bvisible
     * @param int               $bcachetime
     * @param array             $bmodule
     * @param null|array|string $options
     * @param null|array        $groups
     */
    public function updateBlock($bid, $btitle, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options, $groups)
    {
        $myblock = new \XoopsBlock($bid);
        $myblock->setVar('title', $btitle);
        $myblock->setVar('weight', $bweight);
        $myblock->setVar('visible', $bvisible);
        $myblock->setVar('side', $bside);
        $myblock->setVar('bcachetime', $bcachetime);
        //update block options
        if (isset($options)) {
            $options_count = \count($options);
            if ($options_count > 0) {
                //Convert array values to comma-separated
                foreach ($options as $i => $iValue) {
                    if (\is_array($iValue)) {
                        $options[$i] = \implode(',', $iValue);
                    }
                }
                $options = \implode('|', $options);
                $myblock->setVar('options', $options);
            }
        }
        //        $myblock->store();
        /** @var \XoopsBlockHandler $blockHandler */
        $blockHandler = \xoops_getHandler('block');
        return $blockHandler->insert($myblock);

        if (!empty($bmodule) && \count($bmodule) > 0) {
            $sql = \sprintf('DELETE FROM `%s` WHERE block_id = %u', $GLOBALS['xoopsDB']->prefix('block_module_link'), $bid);
            $GLOBALS['xoopsDB']->query($sql);
            if (\in_array(0, $bmodule)) {
                $sql = \sprintf('INSERT INTO `%s` (block_id, module_id) VALUES (%u, %d)', $GLOBALS['xoopsDB']->prefix('block_module_link'), $bid, 0);
                $GLOBALS['xoopsDB']->query($sql);
            } else {
                foreach ($bmodule as $bmid) {
                    $sql = \sprintf('INSERT INTO `%s` (block_id, module_id) VALUES (%u, %d)', $GLOBALS['xoopsDB']->prefix('block_module_link'), $bid, (int)$bmid);
                    $GLOBALS['xoopsDB']->query($sql);
                }
            }
        }
        $sql = \sprintf('DELETE FROM `%s` WHERE gperm_itemid = %u', $GLOBALS['xoopsDB']->prefix('group_permission'), $bid);
        $GLOBALS['xoopsDB']->query($sql);
        if (!empty($groups)) {
            foreach ($groups as $grp) {
                $sql = \sprintf("INSERT INTO `%s` (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (%u, %u, 1, 'block_read')", $GLOBALS['xoopsDB']->prefix('group_permission'), $grp, $bid);
                $GLOBALS['xoopsDB']->query($sql);
            }
        }
        \redirect_header($_SERVER['SCRIPT_NAME'], 1, \constant('CO_' . $this->moduleDirNameUpper . '_' . 'UPDATE_SUCCESS'));
    }

    public function render($block = null)
    {
        \xoops_load('XoopsFormLoader');
        \xoops_loadLanguage('common', $this->moduleDirNameUpper);

        $form = new \XoopsThemeForm($block['form_title'], 'blockform', 'blocksadmin.php', 'post', true);
        if (isset($block['name'])) {
            $form->addElement(new \XoopsFormLabel(\_AM_SYSTEM_BLOCKS_NAME, $block['name']));
        }
        $side_select = new \XoopsFormSelect(\_AM_SYSTEM_BLOCKS_TYPE, 'bside', $block['side']);
        $side_select->addOptionArray(
            [
                0 => \_AM_SYSTEM_BLOCKS_SBLEFT,
                1 => \_AM_SYSTEM_BLOCKS_SBRIGHT,
                3 => \_AM_SYSTEM_BLOCKS_CBLEFT,
                4 => \_AM_SYSTEM_BLOCKS_CBRIGHT,
                5 => \_AM_SYSTEM_BLOCKS_CBCENTER,
                7 => \_AM_SYSTEM_BLOCKS_CBBOTTOMLEFT,
                8 => \_AM_SYSTEM_BLOCKS_CBBOTTOMRIGHT,
                9 => \_AM_SYSTEM_BLOCKS_CBBOTTOM,
            ]
        );
        $form->addElement($side_select);
        $form->addElement(new \XoopsFormText(\constant('CO_' . $this->moduleDirNameUpper . '_' . 'WEIGHT'), 'bweight', 2, 5, $block['weight']));
        $form->addElement(new \XoopsFormRadioYN(\constant('CO_' . $this->moduleDirNameUpper . '_' . 'VISIBLE'), 'bvisible', $block['visible']));
        $mod_select = new \XoopsFormSelect(\constant('CO_' . $this->moduleDirNameUpper . '_' . 'VISIBLEIN'), 'bmodule', $block['modules'], 5, true);
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $criteria      = new \CriteriaCompo(new \Criteria('hasmain', 1));
        $criteria->add(new \Criteria('isactive', 1));
        $module_list     = $moduleHandler->getList($criteria);
        $module_list[-1] = \_AM_SYSTEM_BLOCKS_TOPPAGE;
        $module_list[0]  = \_AM_SYSTEM_BLOCKS_ALLPAGES;
        \ksort($module_list);
        $mod_select->addOptionArray($module_list);
        $form->addElement($mod_select);
        $form->addElement(new \XoopsFormText(_AM_SYSTEM_BLOCKS_TITLE, 'btitle', 50, 255, $block['title']), false);
        if ($block['is_custom']) {
            $textarea = new \XoopsFormDhtmlTextArea(\_AM_SYSTEM_BLOCKS_CONTENT, 'bcontent', $block['content'], 15, 70);
            $textarea->setDescription('<span style="font-size:x-small;font-weight:bold;">' . \_AM_SYSTEM_BLOCKS_USEFULTAGS . '</span><br><span style="font-size:x-small;font-weight:normal;">' . \sprintf(_AM_BLOCKTAG1, '{X_SITEURL}', XOOPS_URL . '/') . '</span>');
            $form->addElement($textarea, true);
            $ctype_select = new \XoopsFormSelect(\_AM_SYSTEM_BLOCKS_CTYPE, 'bctype', $block['ctype']);
            $ctype_select->addOptionArray(
                [
                    'H' => \_AM_SYSTEM_BLOCKS_HTML,
                    'P' => \_AM_SYSTEM_BLOCKS_PHP,
                    'S' => \_AM_SYSTEM_BLOCKS_AFWSMILE,
                    'T' => \_AM_SYSTEM_BLOCKS_AFNOSMILE,
                ]
            );
            $form->addElement($ctype_select);
        } else {
            if ('' !== $block['template']) {
                /** @var \XoopsTplfileHandler $tplfileHandler */
                $tplfileHandler = \xoops_getHandler('tplfile');
                $btemplate      = $tplfileHandler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $block['bid']);
                if (\count($btemplate) > 0) {
                    $form->addElement(new \XoopsFormLabel(\_AM_SYSTEM_BLOCKS_CONTENT, '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id=' . $btemplate[0]->getVar('tpl_id') . '">' . \_AM_SYSTEM_BLOCKS_EDITTPL . '</a>'));
                } else {
                    $btemplate2 = $tplfileHandler->find('default', 'block', $block['bid']);
                    if (\count($btemplate2) > 0) {
                        $form->addElement(new \XoopsFormLabel(\_AM_SYSTEM_BLOCKS_CONTENT, '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id=' . $btemplate2[0]->getVar('tpl_id') . '" target="_blank">' . \_AM_SYSTEM_BLOCKS_EDITTPL . '</a>'));
                    }
                }
            }
            if (false !== $block['edit_form']) {
                $form->addElement(new \XoopsFormLabel(\_AM_SYSTEM_BLOCKS_OPTIONS, $block['edit_form']));
            }
        }
        $cache_select = new \XoopsFormSelect(\_AM_SYSTEM_BLOCKS_BCACHETIME, 'bcachetime', $block['bcachetime']);
        $cache_select->addOptionArray(
            [
                0       => _NOCACHE,
                30      => sprintf(_SECONDS, 30),
                60      => _MINUTE,
                300     => sprintf(_MINUTES, 5),
                1800    => sprintf(_MINUTES, 30),
                3600    => _HOUR,
                18000   => sprintf(_HOURS, 5),
                86400   => _DAY,
                259200  => sprintf(_DAYS, 3),
                604800  => _WEEK,
                2592000 => _MONTH,
            ]
        );
        $form->addElement($cache_select);

        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = \xoops_getHandler('groupperm');
        $groups           = $grouppermHandler->getGroupIds('block_read', $block['bid']);

        $form->addElement(new \XoopsFormSelectGroup(\_AM_SYSTEM_BLOCKS_GROUP, 'groups', true, $groups, 5, true));

        if (isset($block['bid'])) {
            $form->addElement(new \XoopsFormHidden('bid', $block['bid']));
        }
        $form->addElement(new \XoopsFormHidden('op', $block['op']));
        $form->addElement(new \XoopsFormHidden('fct', 'blocksadmin'));
        $buttonTray = new \XoopsFormElementTray('', '&nbsp;');
        if ($block['is_custom']) {
            $buttonTray->addElement(new \XoopsFormButton('', 'previewblock', _PREVIEW, 'submit'));
        }

        //Submit buttons
        $buttonTray    = new \XoopsFormElementTray('', '');
        $submit_button = new \XoopsFormButton('', 'submitblock', _SUBMIT, 'submit');
        $buttonTray->addElement($submit_button);

        $cancel_button = new \XoopsFormButton('', '', _CANCEL, 'button');
        $cancel_button->setExtra('onclick="history.go(-1)"');
        $buttonTray->addElement($cancel_button);

        $form->addElement($buttonTray);
        $form->display();
    }
}


