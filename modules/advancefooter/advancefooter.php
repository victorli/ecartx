<?php if (!defined('_PS_VERSION_')) exit;
include_once (dirname(__file__) . '/class/FBlock.php');
include_once (dirname(__file__) . '/class/FItem.php');
include_once (dirname(__file__) . '/class/FRow.php');
class AdvanceFooter extends Module
{
    public $hookAssign = array();
    public function __construct()
    {
        $this->name = 'advancefooter';
        $this->tab = 'front_office_features';
        $this->version = '1.3';
        $this->author = 'OvicSoft';
        parent::__construct();
        $this->displayName = $this->l('Supershop - Advanced Footer');
        $this->description = $this->l('Advanced Footer Module.');
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        $this->hookAssign = array(
            'rightcolumn',
            'leftcolumn',
            'home',
            'top',
            'footer');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }
    // this also works, and is more future-proof
    public function install()
    {
        if (!parent::install() || !$this->registerHook('header') || !$this->registerHook('footer') || !$this->registerHook
            ('displayBackOfficeHeader') || !$this->installDB() || !$this->installSampleData()) return false;
        return true;
    }
    private function installDB()
    {
        $res = Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_footer_blocks` (
        `id_block` int(6) NOT NULL AUTO_INCREMENT,
        `id_row` int(6) NOT NULL DEFAULT \'1\',
        `display_title` TINYINT(1) DEFAULT \'1\',
        `position` int(3),
        `bclass` varchar(200),
        `width` int(6),
        PRIMARY KEY(`id_block`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        $res &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_footer_blocks_lang` (
        `id_block` int(6) NOT NULL,
        `id_lang` int(10) unsigned NOT NULL,
        `title` varchar(255) DEFAULT NULL,
        PRIMARY KEY(`id_block`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        $res &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_footer_block_items` (
        `id_item` int(6) NOT NULL AUTO_INCREMENT,
        `id_block` int(6) NOT NULL,
        `display_title` TINYINT(1) DEFAULT \'1\',
        `position` int(3),
        `target` varchar(50),
        `itemtype` varchar(10),
        `content_key` varchar(50),
        `content_value` varchar(50),
        PRIMARY KEY(`id_item`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        $res &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_footer_block_items_lang` (
        `id_item` int(6) NOT NULL,
        `id_lang` int(10) unsigned NOT NULL,
        `title` varchar(255) DEFAULT NULL,
        `text` TEXT,
        PRIMARY KEY(`id_item` ,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        $res &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_footer_shop` (
        `id_block` int(10) unsigned NOT NULL,
        `id_shop` int(10) unsigned NOT NULL,
        PRIMARY KEY(`id_block`,`id_shop`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8') && Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_footer_row` (
			`id_row` int(6) NOT NULL AUTO_INCREMENT,
			`rclass` varchar(200),
            `position` int(3) DEFAULT 0,
            `active` TINYINT(1) unsigned DEFAULT 1,
			PRIMARY KEY(`id_row`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        return $res;
    }
    private function installSampleData()
    {
        $sql = "INSERT INTO `"._DB_PREFIX_."advance_footer_row` (`id_row`, `rclass`, `position`, `active`) VALUES
                (1, '', 2, 1),
                (2, 'row_footer_info', 1, 1),
                (3, '', 4, 1),
                (4, 'blocktags_footer', 3, 1),
                (5, 'logo_footer_row', 0, 1);";
        $result = Db::getInstance()->execute($sql);
        $sql = "INSERT INTO `"._DB_PREFIX_."advance_footer_blocks` (`id_block`, `id_row`, `display_title`, `position`, `bclass`, `width`) VALUES
                (2, 1, 0, 1, '', 6),
                (3, 1, 0, 0, '', 6),
                (4, 2, 0, 1, '', 4),
                (5, 2, 0, 0, 'horizontal-list', 8),
                (6, 3, 0, 0, 'ft_copyright', 8),
                (8, 4, 1, 0, '', 3),
                (10, 3, 0, 0, 'payment_logo', 4),
                (11, 4, 0, 0, '', 12),
                (12, 5, 0, 0, 'logo_footer', 12);";
        $result = Db::getInstance()->execute($sql);
        
        $sql = "INSERT INTO `"._DB_PREFIX_."advance_footer_blocks_lang` (`id_block`, `id_lang`, `title`) VALUES ";
            foreach (Language::getLanguages(false) as $lang){
                $sql .= "(2,".$lang['id_lang'].",'Connect with Us'),";
                $sql .= "(3,".$lang['id_lang'].",'newsletter'),";
                $sql .= "(4,".$lang['id_lang'].",'Contact us'),";
                $sql .= "(5,".$lang['id_lang'].",'group'),";
                $sql .= "(6,".$lang['id_lang'].",'copytight'),";
                $sql .= "(10,".$lang['id_lang'].",'paymentlogo'),";
                $sql .= "(11,".$lang['id_lang'].",'Block Tags on Footer'),";
                $sql .= "(12,".$lang['id_lang'].",'Logo footer row'),";
            }    
        $sql = rtrim($sql, ",").";";
        $result &= Db::getInstance()->execute($sql);
        
        $sql = "INSERT INTO `"._DB_PREFIX_."advance_footer_block_items` (`id_item`, `id_block`, `display_title`, `position`, `target`, `itemtype`, `content_key`, `content_value`) VALUES
                (2, 2, 0, NULL, '', 'module', 'blocksocial', 'displayFooter'),
                (3, 3, 0, NULL, '', 'module', 'blocknewsletter', 'displayFooter'),
                (4, 4, 0, 0, '', 'module', 'blockcontactinfos', 'displayFooter'),
                (5, 5, 0, 1, '', 'module', 'blockmyaccountfooter', 'displayFooter'),
                (6, 5, 0, 2, '', 'module', 'ovicblockcms', 'displayFooter'),
                (7, 5, 0, NULL, '', 'html', '', ''),
                (8, 5, 0, 0, '', 'html', '', ''),
                (9, 6, 0, 0, '', 'html', '', ''),
                (12, 10, 0, 0, '', 'html', '', ''),
                (13, 10, 0, 0, '', 'html', '', ''),
                (14, 11, 0, 0, '', 'module', 'oviccustomtags', 'displayFooter'),
                (15, 12, 0, 0, '', 'html', '', '');";
        $result = Db::getInstance()->execute($sql);
        
        $sql = "INSERT INTO `"._DB_PREFIX_."advance_footer_block_items_lang` (`id_item`, `id_lang`, `title`, `text`) VALUES";        
                foreach (Language::getLanguages(false) as $lang){
                    $sql .= "(2, ".$lang['id_lang'].", 'social', ''),";
                    $sql .= "(3, ".$lang['id_lang'].", 'newsletter', ''),";
                    $sql .= "(4, ".$lang['id_lang'].", 'Contact us', ''),";
                    $sql .= "(5, ".$lang['id_lang'].", 'my_accoun', ''),";
                    $sql .= "(6, ".$lang['id_lang'].", 'cmsblock', ''),";
                    $sql .= "(8, ".$lang['id_lang'].", 'Customer Service', '<div class=\"footer-block\">\r\n<h4 class=\"title_block mainFont\">Customer Service</h4>\r\n<ul class=\"toggle-footer bullet\">\r\n<li><a href=\"#\" title=\"\">Ask in Forum</a></li>\r\n<li><a href=\"#\" title=\"\">Help Desk</a></li>\r\n<li><a href=\"#\" title=\"\">Payment Methods</a></li>\r\n<li><a href=\"#\" title=\"\">Custom Work</a></li>\r\n<li><a href=\"#\" title=\"\">Promotions</a></li>\r\n</ul>\r\n</div>'),";
                    $sql .= "(9, ".$lang['id_lang'].", 'copyright', '&copy; 2015 Prestashop Demo SuperShop Online. All Rights Reserved.'),";
                    $sql .= "(13, ".$lang['id_lang'].", 'paymentlogo', '<img src=\"http://kutethemes.com/demo/supershop/option1/img/cms/payment_logo.png\" alt=\"\" height=\"30\" width=\"249\" />'),";
                    $sql .= "(14, ".$lang['id_lang'].", 'Categories tags', ''),";
                    $sql .= "(15, ".$lang['id_lang'].", 'logo_footer_block', '<div id=\"footer_logo_block\"><img src=\"http://kutethemes.com/demo/supershop/option1/img/cms/logo_footer.png\" alt=\"\" width=\"142\" height=\"45\" />\r\n<div class=\"link_list_footer\"><a href=\"#\">Online Shopping</a> <a href=\"#\">Buy</a> <a href=\"#\">Sell</a> <a href=\"#\">All Promotions</a> <a href=\"#\">My Orders</a> <a href=\"#\">Help</a> <a href=\"#\">Site Map</a> <a href=\"#\">Customer Service</a> <a href=\"#\">About</a> <a href=\"#\">Contact</a></div>\r\n</div>'),";
                }    
        $sql = rtrim($sql, ",").";";
        $result = Db::getInstance()->execute($sql);
        
        $sql = "INSERT INTO `"._DB_PREFIX_."advance_footer_shop` (`id_block`, `id_shop`) VALUES
                (2, 1),
                (3, 1),
                (4, 1),
                (5, 1),
                (6, 1),
                (10, 1),
                (11, 1),
                (12, 1);";
        $result = Db::getInstance()->execute($sql);
        return $result;
    }
    public function uninstall()
    {
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
        if (!parent::uninstall() || !$this->delConfig() || !$this->uninstallDB()) return false;
        return true;
    }
    private function delConfig()
    {
        Configuration::deleteByName('NB_ROWS');
        return true;
    }
    private function uninstallDb()
    {

        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_footer_blocks`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_footer_blocks_lang`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_footer_block_items`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_footer_block_items_lang`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_footer_shop`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_footer_row`');
        return true;
    }
    public function getContent()
    {
        $output = '';
        $errors = array();
        if (Tools::getValue('confirm_msg'))
        {
            $output .= $this->displayConfirmation(Tools::getValue('confirm_msg'));
        }
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        $lang_ul = '<ul class="dropdown-menu">';
        foreach ($languages as $lg)
        {
            $lang_ul .= '<li><a href="javascript:hideOtherLanguage(' . $lg['id_lang'] . ');" tabindex="-1">' . $lg['name'] .
                '</a></li>';
        }
        $lang_ul .= '</ul>';
        $this->context->smarty->assign(array(
            'postAction' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite
                ('AdminModules'),
            'lang_ul' => $lang_ul,
            'langguages' => array(
                'default_lang' => $id_lang_default,
                'all' => $languages,
                'lang_dir' => _THEME_LANG_DIR_)));
        if (Tools::isSubmit('submitnewrow'))
        {
            $id_row = (int)Tools::getValue('id_row');
            if ($id_row && Validate::isUnsignedId($id_row))
            {
                $footer_row = new FRow($id_row);
            }
            else
            {
                $footer_row = new FRow();
            }
            $footer_row->rclass = Tools::getValue('row_class');
            $footer_row->active = (int)Tools::getValue('active');
            if ($id_row && Validate::isUnsignedId($id_row))
            {
                if (!$footer_row->update())
                {
                    $errors[] = 'An error occurred while update data.';
                }
                else
                {
                    $output .= $this->displayConfirmation('Row successfully updated');
                }
            }
            else
            {
                if (!$footer_row->add())
                {
                    $errors[] = 'An error occurred while saving data.';
                }
                else
                {
                    $confirm_msg = $this->l('New row successfully added');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                    Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                        Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                }
            }
        }
        elseif (Tools::isSubmit('submitSaveBlock'))
        {
            $id_block = (int)Tools::getValue('id_block');
            if ($id_block && Validate::isUnsignedId($id_block))
            {
                $block = new FBlock($id_block);
            }
            else
            {
                $block = new FBlock();
            }
            $block->id_row = Tools::getValue('block_row');
            $block->display_title = Tools::getValue('title_show');
            $block->bclass = Tools::getValue('block_class');
            $block->width = Tools::getValue('block_width');
            $blocktitle_set = false;
            foreach ($languages as $language)
            {
                $blocktitle = Tools::getValue('blocktitle_' . $language['id_lang']);
                if (strlen($blocktitle) > 0)
                {
                    $blocktitle_set = true;
                }
                $block->title[$language['id_lang']] = $blocktitle;
            }
            if (!$blocktitle_set)
            {
                $lang_title = Language::getLanguage($this->context->language->id);
                $errors[] = 'This block title field is required at least in ' . $lang_title['name'];
            }
            if (!count($errors))
                if ($id_block && Validate::isUnsignedId($id_block))
                {
                    if ($block->update())
                    {
                        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                        $output .= $this->displayConfirmation('Block Updated');
                    }
                    else
                    {
                        $errors[] = 'An error occurred while saving block.';
                    }
                }
                else
                {
                    if ($block->add())
                    {
                        $confirm_msg = $this->l('Block successfully saved.');
                        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                        Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                            Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                    }
                    else
                    {
                        $errors[] = 'An error occurred while update block.';
                    }
                }
        }
        elseif (Tools::isSubmit('submitRemoveBlock'))
        {
            $block_id = Tools::getValue('id_block');
            if ($this->deleteBlock($block_id))
            {
                Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                $output .= '<div class="alert alert-success">' . $this->l('Block deleted') . '</div>';
            }
            else
            {
                $errors[] = 'An error occurred while delete block.';
            }
        }
        elseif (Tools::isSubmit('submitSaveItem'))
        {
            $id_item = Tools::getValue('id_item');
            if ($id_item && Validate::isUnsignedId($id_item))
            {
                $id_item = Tools::getValue('id_item');
                $item = new FItem($id_item);
            }
            else  $item = new FItem();
            $item->id_block = Tools::getValue('block_id');
            $itemtitle_set = false;
            foreach ($languages as $language)
            {
                $item_title = Tools::getValue('itemtitle_' . $language['id_lang']);
                if (strlen($item_title) > 0)
                {
                    $itemtitle_set = true;
                }
                $item->title[$language['id_lang']] = $item_title;
            }
            if (!$itemtitle_set)
            {
                $lang_title = Language::getLanguage($this->context->language->id);
                $errors[] = 'This item title field is required at least in ' . $lang_title['name'];
            }
            $item->display_title = Tools::getValue('title_show');
            $item->itemtype = Tools::getValue('item_type');
            switch ($item->itemtype)
            {
                case 'link':
                    $item->target = Tools::getValue('target');
                    $item->content_key = Tools::getValue('linktype');
                    $k = 'id_' . trim($item->content_key);
                    $item->content_value = Tools::getValue($k);
                    break;
                case 'html':
                    foreach ($languages as $language) $item->text[$language['id_lang']] = Tools::getValue('htmlbody_' .
                            $language['id_lang']);
                    break;
                case 'module':
                    $item->content_key = Tools::getValue('module');
                    $item->content_value = Tools::getValue('hook');
                    break;
            }
            if (!count($errors))
                if ($id_item && Validate::isUnsignedId($id_item))
                {
                    if ($item->update())
                    {
                        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                        $output .= $this->displayConfirmation('Item Updated');
                    }
                    else
                    {
                        $errors[] = 'An error occurred while update item.';
                    }
                }
                else
                {
                    if ($item->add())
                    {
                        $confirm_msg = $this->l('Item successfully saved.');
                        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                        Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                            Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                    }
                    else
                    {
                        $errors[] = 'An error occurred while add new item.';
                    }
                }
        }
        elseif (Tools::getValue('removeitem') == 1)
        {
            $id_item = Tools::getValue('id_item');
            if ($id_item && Validate::isUnsignedId($id_item))
            {
                $item = new FItem($id_item);
                if ($item->delete())
                {
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                    $output .= $this->displayConfirmation('Item deleted');
                }
                else
                {
                    $errors[] = 'An error occurred while delete item.';
                }
            }
        }
        elseif (Tools::isSubmit('changestatus'))
        {
            $id_row = (int)Tools::getValue('id_row');
            if ($id_row && Validate::isUnsignedId($id_row))
            {
                $f_row = new FRow($id_row);
                $f_row->active = !$f_row->active;
                if (!$f_row->update())
                {
                    $errors[] = 'An error occurred while chanage status.';
                }
                else
                {
                    $confirm_msg = $this->l('Row successfully updated.');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                    Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                        Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                }
            }
        }
        elseif (Tools::isSubmit('submit_del_row'))
        {
            $id_row = (int)Tools::getValue('id_row');
            if ($id_row && Validate::isUnsignedId($id_row))
            {
                if (!$this->deleteRow($id_row))
                {
                    $errors[] = 'An error occurred while delete row.';
                }
                else
                {
                    $output .= $this->displayConfirmation('Delete successful');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
                }
            }
        }
        if (count($errors) > 0)
        {
            if (isset($errors) && count($errors)) $output .= $this->displayError(implode('<br />', $errors));
        }
        if (Tools::isSubmit('submitItem') || (Tools::isSubmit('submitSaveItem') && count($errors) > 0))
                return $output . $this->displayItemForm();
        elseif (Tools::isSubmit('submitBlock') || (Tools::isSubmit('submitSaveBlock') && count($errors) > 0))
                return $output . $this->displayBlockForm();
        elseif (Tools::isSubmit('submitRow')) return $output . $this->displayRowForm();
        else  return $output . $this->displayForm();
    }
    private function getItemByBlock($id_block = null)
    {
        $results = array();
        if (is_null($id_block) || !Validate::isUnsignedId($id_block))
        {
            return $results;
        }
        $block = new FBlock($id_block);
        $items = $block->getItems();
        if ($items && count($items) > 0)
        {
            foreach ($items as $it)
            {
                $item = new FItem($it['id_item']);
                $results[] = $item;
            }
        }
        return $results;
    }
    public function updateRowPosition($order = null)
    {
        if (is_null($order) || strlen($order) < 1) return false;
        $position = explode('::', $order);
        $res = false;
        if (count($position) > 0)
            foreach ($position as $key => $id_row)
            {
                $res = Db::getInstance()->execute('
                    UPDATE `' . _DB_PREFIX_ . 'advance_footer_row`
                    SET `position` = ' . $key . '
                    WHERE `id_row` = ' . (int)$id_row);
                if (!$res) break;
            }
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
        return $res;
    }
    public function updateItemPosition($order = null)
    {
        if (is_null($order) || strlen($order) < 1) return false;
        $position = explode('::', $order);
        $res = false;
        if (count($position) > 0)
            foreach ($position as $key => $id_item)
            {
                $res = Db::getInstance()->execute('
                    UPDATE `' . _DB_PREFIX_ . 'advance_footer_block_items`
                    SET `position` = ' . $key . '
                    WHERE `id_item` = ' . (int)$id_item);
                if (!$res) break;
            }
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
        return $res;
    }
    public function updateBlockPosition($order = null)
    {
        if (is_null($order) || strlen($order) < 1) return false;
        $position = explode('::', $order);
        $res = false;
        if (count($position) > 0)
            foreach ($position as $key => $id_block)
            {
                $res = Db::getInstance()->execute('
                    UPDATE `' . _DB_PREFIX_ . 'advance_footer_blocks`
                    SET `position` = ' . $key . '
                    WHERE `id_block` = ' . (int)$id_block);
                if (!$res) break;
            }
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('advfooter.tpl'));
        return $res;
    }
    private function deleteRow($id_row = null)
    {
        if (is_null($id_row) || !Validate::isUnsignedId($id_row)) return false;
        $blocks = $this->getBlocks($id_row);
        $del = true;
        if ($blocks && count($blocks) > 0)
            foreach ($blocks as $bl)
            {
                $del &= $this->deleteBlock($bl['id_block']);
            }
        if ($del)
        {
            $frow = new FRow($id_row);
            return $frow->delete();
        }
        return false;
    }
    private function deleteBlock($id_block = null)
    {
        if (is_null($id_block) || !Validate::isUnsignedId($id_block)) return false;
        $items = $this->getItemByBlock($id_block);
        $del = true;
        if ($items && count($items) > 0)
            foreach ($items as $it)
            {
                $del &= $it->delete();
            }
        if ($del)
        {
            $block = new FBlock($id_block);
            return $block->delete();
        }
        return false;
    }
    /**
     * Render Configuration From for user making settings.
     *
     * @return context
     */
    private function displayForm()
    {
        $footer_rows = $this->getFooterRow();
        if (count($footer_rows))
        {
            //$footer_data = array();
            foreach ($footer_rows as &$row)
            {
                $blocks = $this->getBlocks($row['id_row']);
                if ($blocks && count($blocks) > 0)
                    foreach ($blocks as &$bl)
                    {
                        $block_obj = new FBlock($bl['id_block']);
                        $bl = (array )$block_obj;
                        $items = $this->getItemByBlock($block_obj->id);
                        $bl['items'] = $items;
                    }
                $row['blocks'] = $blocks;
                //$footer_data[$i]['blocks']= $blocks;
            }
        }
        $this->context->smarty->assign(array(
            'footer_data' => $footer_rows,
            'imgpath' => $this->_path . 'img/',
            'ajaxUrl' => $this->_path . 'ajax.php?secure_key=' . $this->secure_key,
            ));
        return $this->display(__file__, 'views/templates/admin/main.tpl');
    }
    public function displayRowForm()
    {
        $id_row = Tools::getValue('id_row');
        if ($id_row && Validate::isUnsignedId($id_row))
        {
            $footer_row = new FRow($id_row);
        }
        else
        {
            $footer_row = new FRow();
        }
        $this->context->smarty->assign(array('footer_row' => $footer_row));
        return $this->display(__file__, 'views/templates/admin/row_form.tpl');
    }
    public function displayBlockForm()
    {
        $id_block = (int)Tools::getValue('id_block');
        if ($id_block && Validate::isUnsignedId($id_block))
        {
            $fblock = new FBlock($id_block);
        }
        else
        {
            $fblock = new FBlock();
        }
        $fblock->id_row = Tools::getValue('block_row');
        if (Tools::isSubmit('submitSaveBlock'))
        {
            $languages = Language::getLanguages(false);
            $fblock->display_title = Tools::getValue('title_show');
            $fblock->bclass = Tools::getValue('block_class');
            $fblock->width = Tools::getValue('block_width');
            foreach ($languages as $language)
            {
                $fblock->title[$language['id_lang']] = Tools::getValue('blocktitle_' . $language['id_lang']);
            }
        }
        $this->context->smarty->assign(array('block_obj' => $fblock));
        return $this->display(__file__, 'views/templates/admin/block_form.tpl');
    }
    public function displayItemForm()
    {
        $id_item = Tools::getValue('id_item');
        if ($id_item && Validate::isUnsignedId($id_item))
        {
            $edit = true;
            $item = new FItem($id_item);
        }
        else
        {
            $edit = false;
            $item = new FItem();
        }
        $item->id_block = (int)Tools::getValue('block_id');
        if (Tools::isSubmit('submitSaveItem'))
        {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language)
            {
                $item->title[$language['id_lang']] = Tools::getValue('itemtitle_' . $language['id_lang']);
            }
            $item->display_title = Tools::getValue('title_show');
            $item->itemtype = Tools::getValue('item_type');
            switch ($item->itemtype)
            {
                case 'link':
                    $item->target = Tools::getValue('target');
                    $item->content_key = Tools::getValue('linktype');
                    $k = 'id_' . trim($item->content_key);
                    $item->content_value = Tools::getValue($k);
                    break;
                case 'html':
                    foreach ($languages as $language) $item->text[$language['id_lang']] = Tools::getValue('htmlbody_' .
                            $language['id_lang']);
                    break;
                case 'module':
                    $item->content_key = Tools::getValue('module');
                    $item->content_value = Tools::getValue('hook');
                    break;
            }
        }
        if ($edit && strlen($item->content_value) > 0)
        {
            $categoryOption = $this->getCategoryOption($item->content_value);
            $cmsOption = $this->getCMSOptions($item->content_value);
            $supplierOption = $this->getSupplierOption($item->content_value);
            $manufacturerOption = $this->getManufacturerOption($item->content_value);
            $pageOption = $this->getPagesOption($item->content_value);
        }
        else
        {
            $categoryOption = $this->getCategoryOption();
            $cmsOption = $this->getCMSOptions();
            $supplierOption = $this->getSupplierOption();
            $manufacturerOption = $this->getManufacturerOption();
            $pageOption = $this->getPagesOption();
        }
        $hookOption = '';
        if ($edit && strlen($item->content_key) > 0)
        {
            $moduleOption = $this->getModulesOption($item->content_key);
            if (strlen($item->content_value) > 0)
            {
                $hookOption = $this->getHookOptionByModuleName($item->content_key, $item->content_value);
            }
            else
            {
                $hookOption = $this->getHookOptionByModuleName($item->content_key);
            }
        }
        else
        {
            $moduleOption = $this->getModulesOption();
        }
        $this->context->smarty->assign(array(
            'item' => $item,
            'categoryOption' => $categoryOption,
            'cmsOption' => $cmsOption,
            'supplierOption' => $supplierOption,
            'manufacturerOption' => $manufacturerOption,
            'pageOption' => $pageOption,
            'moduleOption' => $moduleOption,
            'hookOption' => $hookOption,
            'ajaxPath' => $this->_path . 'ajax.php?secure_key=' . $this->secure_key));
        $iso = Language::getIsoById((int)($this->context->language->id));
        $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en');
        $ad = dirname($_SERVER["PHP_SELF"]);
        $html = '<script type="text/javascript">
                        var iso = \'' . $isoTinyMCE . '\' ;
                        var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
                        var ad = \'' . $ad . '\' ;
                        $(document).ready(function(){
                            tinySetup({
                                editor_selector :"rte",
                                theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,fontselect,fontsizeselect",
                                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,codemagic,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                                theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
                                theme_advanced_toolbar_location : "top",
                                theme_advanced_toolbar_align : "left",
                                theme_advanced_statusbar_location : "bottom",
                                theme_advanced_resizing : false,
                                extended_valid_elements: \'pre[*],script[*],style[*]\',
                                valid_children: "+body[style|script],pre[script|div|p|br|span|img|style|h1|h2|h3|h4|h5],*[*]",
                                valid_elements : \'*[*]\',
                                force_p_newlines : false,
                                cleanup: false,
                                forced_root_block : false,
                                force_br_newlines : true
                            });
                        });
                    </script>';
        return $html . $this->display(__file__, 'views/templates/admin/item_form.tpl');
    }
    private function getFooterRow($active = null)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advance_footer_row`' . (!is_null($active) && $active ?
            ' WHERE active = 1' : '') . ' ORDER BY  `position` ASC';
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
    private function getSupplierOption($selected_id = null)
    {
        $html = '';
        $suppliers = Supplier::getSuppliers(false, $this->context->language->id);
        foreach ($suppliers as $supplier)
        {
            $html .= '<option value="' . $supplier['id_supplier'] . '" ' . ((!is_null($selected_id) && $selected_id ==
                $supplier['id_supplier']) ? 'selected = "selected"' : '') . '>' . $supplier['name'] . '</option>';
        }
        return $html;
    }
    private function getManufacturerOption($selected_id = null)
    {
        $html = '';
        $manufacturers = Manufacturer::getManufacturers(false, $this->context->language->id);
        foreach ($manufacturers as $manufacturer)
        {
            $html .= '<option value="' . $manufacturer['id_manufacturer'] . '"' . ((!is_null($selected_id) && $selected_id ==
                $manufacturer['id_manufacturer']) ? 'selected = "selected"' : '') . '>' . $manufacturer['name'] .
                '</option>';
        }
        return $html;
    }
    private function getBlocks($row, $total = false)
    {
        $id_lang = $this->context->language->id;
        if ($total)
        {
            $sql = 'SELECT count(id) FROM `' . _DB_PREFIX_ . 'advance_footer_blocks` b
LEFT JOIN `' . _DB_PREFIX_ . 'advance_footer_blocks_lang` bl ON (b.`id_block` = bl.`id_block`)
WHERE `id_row` = ' . (int)$row . ' AND
bl.`id_lang` = ' . (int)$id_lang;
        }
        else
        {
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advance_footer_blocks` b
LEFT JOIN `' . _DB_PREFIX_ . 'advance_footer_blocks_lang` bl ON (b.`id_block` = bl.`id_block`)
WHERE `id_row` = ' . (int)$row . ' AND
bl.`id_lang` = ' . (int)$id_lang . ' ORDER BY  b.`position` ASC';
        }
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
    public function getCategoryOption($selected = null, $id_category = 1, $id_lang = false, $id_shop = false,
        $recursive = true)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id)) return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
        }
        $shop = (object)Shop::getShop((int)$category->getShopID());
        if ($category->id != 1) $html .= '<option ' . ($selected == $category->id ? 'selected="selected"' :
                '') . ' value="' . (int)$category->id . '">' . $category->name . '</option>';
        if (isset($children) && count($children))
            foreach ($children as $child)
            {
                $html .= $this->getCategoryOption($selected, (int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
            }
        return $html;
    }
    public function getCMSOptions($selected = null, $parent = 0, $depth = 1, $id_lang = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
        $pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);
        foreach ($pages as $page) $html .= '<option ' . ($selected == $page['id_cms'] ?
                'selected="selected"' : '') . ' value="' . $page['id_cms'] . '">' . $page['meta_title'] .
                '</option>';
        foreach ($categories as $category)
        {
            $html .= $this->getCMSOptions($selected, $category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
        }
        return $html;
    }
    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
    {
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        if ($recursive === false)
        {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
FROM `' . _DB_PREFIX_ . 'cms_category` bcp
INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
ON (bcp.`id_cms_category` = cl.`id_cms_category`)
WHERE cl.`id_lang` = ' . (int)$id_lang . '
AND bcp.`id_parent` = ' . (int)$parent;
            return Db::getInstance()->executeS($sql);
        }
        else
        {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
FROM `' . _DB_PREFIX_ . 'cms_category` bcp
INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
ON (bcp.`id_cms_category` = cl.`id_cms_category`)
WHERE cl.`id_lang` = ' . (int)$id_lang . '
AND bcp.`id_parent` = ' . (int)$parent;
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result)
            {
                $sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
                if ($sub_categories && count($sub_categories) > 0) $result['sub_categories'] = $sub_categories;
                $categories[] = $result;
            }
            return isset($categories) ? $categories : false;
        }
    }
    private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
    {
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
FROM `' . _DB_PREFIX_ . 'cms` c
INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
ON (c.`id_cms` = cs.`id_cms`)
INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
ON (c.`id_cms` = cl.`id_cms`)
WHERE c.`id_cms_category` = ' . (int)$id_cms_category . '
AND cs.`id_shop` = ' . (int)$id_shop . '
AND cl.`id_lang` = ' . (int)$id_lang . '
AND c.`active` = 1
ORDER BY `position`';
        return Db::getInstance()->executeS($sql);
    }
    private function getModules()
    {
        $id_shop = (int)Context::getContext()->shop->id;
        $results = Db::getInstance()->ExecuteS('
SELECT m.*
FROM `' . _DB_PREFIX_ . 'module` m
JOIN `' . _DB_PREFIX_ . 'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = ' . (int)
            ($id_shop) . ')
WHERE m.`name` <> \'' . $this->name . '\'');
        if (count($results) > 0)
        {
            $modules = array();
            foreach ($results as $result)
            {
                if ($this->getHooksByModuleName($result['name'])) $modules[] = $result;
            }
        }
        return $modules;
    }
    private function getModulById($id_module)
    {
        return Db::getInstance()->getRow('
SELECT m.*
FROM `' . _DB_PREFIX_ . 'module` m
JOIN `' . _DB_PREFIX_ . 'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = ' . (int)
            ($this->context->shop->id) . ')
WHERE m.`id_module` = ' . $id_module);
    }
    private function getModulesOption($selected = null)
    {
        $modules = $this->getModules();
        $html = '';
        if (count($modules) > 0)
        {
            foreach ($modules as $m)
            {
                if (is_null($selected)) $html .= '<option value="' . $m['name'] . '">' . $m['name'] . '</option>';
                else  $html .= '<option ' . ($selected == $m['name'] ? 'selected="selected"' : '') . ' value="' . $m['name'] .
                        '">' . $m['name'] . '</option>';
            }
        }
        return $html;
    }
    private function getHookByArrName($arrName)
    {
        $result = Db::getInstance()->ExecuteS('
SELECT `id_hook`, `name`
FROM `' . _DB_PREFIX_ . 'hook`
WHERE `name` IN (\'' . implode("','", $arrName) . '\')');
        return $result;
    }
    private function getHooksByModuleName($module_name)
    {
        $moduleInstance = Module::getInstanceByName($module_name);
        $hooks = array();
        if ($this->hookAssign)
        {
            foreach ($this->hookAssign as $hook)
            {
                if (_PS_VERSION_ < "1.5")
                {
                    if (is_callable(array($moduleInstance, 'hook' . $hook)))
                    {
                        $hooks[] = $hook;
                    }
                }
                else
                {
                    $retro_hook_name = Hook::getRetroHookName($hook);
                    if (is_callable(array($moduleInstance, 'hook' . $hook)) || is_callable(array($moduleInstance, 'hook' .
                            $retro_hook_name)))
                    {
                        $hooks[] = $retro_hook_name;
                    }
                }
            }
        }
        $results = self::getHookByArrName($hooks);
        return $results;
    }
    public function getHookOptionByModuleName($module_name, $selected = null)
    {
        $hooks = $this->getHooksByModuleName($module_name);
        if (count($hooks) > 0)
        {
            $html = '';
            foreach ($hooks as $h)
            {
                $html .= '<option ' . ($selected == $h['name'] ? 'selected="selected"' : '') . ' value="' . $h['name'] .
                    '">' . $h['name'] . '</option>';
            }
            return $html;
        }
        return;
    }
    public function getModuleAssign($module_name = '', $hook_name = '')
    {
        $module = Module::getInstanceByName($module_name);
        if (_PS_VERSION_ <= "1.5")
        {
            $id_hook = Hook::get($hook_name);
        }
        else
        {
            $id_hook = Hook::getIdByName($hook_name);
        }
        if (Validate::isLoadedObject($module) && $module->id)
        {
            if (!isset($hookArgs['cookie']) or !$hookArgs['cookie']) $hookArgs['cookie'] = $this->context->cookie;
            if (!isset($hookArgs['cart']) or !$hookArgs['cart']) $hookArgs['cart'] = $this->context->cart;
            if (_PS_VERSION_ < "1.5")
            {
                //return self::lofHookExec( $hook_name, array(), $module->id, $array );
                $hook_name = strtolower($hook_name);
                if (!Validate::isHookName($hook_name)) die(Tools::displayError());
                $altern = 0;
                if (is_callable(array($module, 'hook' . $hook_name)))
                {
                    $hookArgs['altern'] = ++$altern;
                    $output = call_user_func(array($module, 'hook' . $hook_name), $hookArgs);
                }
                return $output;
            }
            else
            {
                $hook_name = substr($hook_name, 7, strlen($hook_name));
                if (!Validate::isHookName($hook_name)) die(Tools::displayError());
                $retro_hook_name = Hook::getRetroHookName($hook_name);
                $hook_callable = is_callable(array($module, 'hook' . $hook_name));
                $hook_retro_callable = is_callable(array($module, 'hook' . $retro_hook_name));
                $output = '';
                if (($hook_callable || $hook_retro_callable) && Module::preCall($module->name))
                {
                    if ($hook_callable) $output = $module->{'hook' . $hook_name}($hookArgs);
                    else
                        if ($hook_retro_callable) $output = $module->{'hook' . $retro_hook_name}($hookArgs);
                }
                return $output;
            }
        }
        return '';
    }
    public function getPagesOption($selected = null)
    {
        $files = Meta::getMetasByIdLang((int)$this->context->cookie->id_lang);
        $html = '';
        foreach ($files as $file)
        {
            $html .= '<option ' . ($selected == $file['page'] ? 'selected="selected"' : '') . ' value="' . $file['page'] .
                '">' . (($file['title'] != '') ? $file['title'] : $file['page']) . '</option>';
        }
        return $html;
    }
    public function hookFooter($params)
    {

        if (!$this->isCached('advfooter.tpl', $this->getCacheId()))
        {
            $footer_rows = $this->getFooterRow(true);
            if (count($footer_rows))
            {
                foreach ($footer_rows as &$row)
                {
                    $blocks = $this->getBlocks($row['id_row']);
                    if (count($blocks) > 0)
                    {
                        foreach ($blocks as &$block)
                        {
                            $block_obj = new FBlock($block['id_block']);
                            //$block['items'] = $block_obj->getItems();
                            $items = $block_obj->getItems();
                            if (count($items) > 0)
                            {
                                $it = array();
                                foreach ($items as $item)
                                {
                                    $it[$item['id_item']]['type'] = $item['itemtype'];
                                    switch ($item['itemtype'])
                                    {
                                        case 'link':
                                            switch ($item['content_key'])
                                            {
                                                case 'category':
                                                    $it[$item['id_item']]['html'] = '<a href="' . $this->context->link->getCategoryLink($item['content_value']) .
                                                        '" title="' . $item['title'] . '">' . ($item['display_title'] ? $item['title'] : '') . '</a>';
                                                    break;
                                                case 'cms':
                                                    $it[$item['id_item']]['html'] = '<a href="' . $this->context->link->getCMSLink($item['content_value']) .
                                                        '" title="' . $item['title'] . '">' . ($item['display_title'] ? $item['title'] : '') . '</a>';
                                                    break;
                                                case 'manufacturer':
                                                    $it[$item['id_item']]['html'] = '<a href="' . $this->context->link->getManufacturerLink($item['content_value']) .
                                                        '" title="' . $item['title'] . '">' . ($item['display_title'] ? $item['title'] : '') . '</a>';
                                                    break;
                                                case 'supplier':
                                                    $it[$item['id_item']]['html'] = '<a href="' . $this->context->link->getSupplierLink($item['content_value']) .
                                                        '" title="' . $item['title'] . '">' . ($item['display_title'] ? $item['title'] : '') . '</a>';
                                                    break;
                                                case 'page':
                                                    $it[$item['id_item']]['html'] = '<a href="' . $this->context->link->getPageLink($item['content_value']) .
                                                        '" title="' . $item['title'] . '">' . ($item['display_title'] ? $item['title'] : '') . '</a>';
                                                    break;
                                                case 'other':
                                                    $it[$item['id_item']]['html'] = '<a href="' . $item['content_value'] . '" title="' . $item['title'] .
                                                        '">' . ($item['display_title'] ? $item['title'] : '') . '</a>';
                                                    break;
                                            }
                                            break;
                                        case 'html':
                                            $it[$item['id_item']]['html'] = $item['text'];
                                            break;
                                        case 'module':
                                            $it[$item['id_item']]['html'] = $this->getModuleAssign($item['content_key'], $item['content_value']);
                                            break;
                                    }
                                }
                                $block['items'] = $it;
                            }
                        }
                        $row['blocks'] = $blocks;
                    }
                }
            }
            $this->smarty->assign(array('footers' => $footer_rows));
        }
        return $this->display(__file__, 'advfooter.tpl', $this->getCacheId());
    }
    public function hookDisplayHeader($params)
    {
        //$this->context->controller->addCSS(($this->_path) . 'css/advancefooter.css','all');
        $this->context->controller->addJS(($this->_path) . 'js/advancefooter.js');
    }
    public function hookDisplayBackOfficeHeader()
    {
        
        if (Tools::getValue('configure') != $this->name) return;
        $iso = Language::getIsoById((int)($this->context->language->id));        
        $ad = dirname($_SERVER["PHP_SELF"]);
        $this->smarty->assign(array('ad' => $ad, 'iso'=>$iso));
        $this->context->controller->addCSS($this->_path . 'css/admin.css');
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS($this->_path . 'js/tinymce.inc.js');
        $this->context->controller->addJS($this->_path . 'js/jquery-ui.js');
        $this->context->controller->addJS($this->_path . 'js/admin_footer.js');
    }
} ?>