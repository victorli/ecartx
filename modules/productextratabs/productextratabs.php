<?php

if (!defined('_PS_VERSION_'))
    exit;
include_once (dirname(__file__) . '/class/Extratabs.php');
class ProductExtraTabs extends Module{
    public function __construct()
    {
        $this->name = 'productextratabs';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'OvicSoft';
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Supershop - Extra Tabs');
        $this->description = $this->l('Add additional tabs to their products to provide more relevant information details.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    }
    public function install()
    {
        if (!parent::install() || !$this->registerHook('productTab') ||
            !$this->registerHook('displayBackOfficeHeader') ||
            !$this->registerHook('productTabContent') || !$this->installDB())
            return false;
        return true;
    }
    public function installDb()
	{
		return (Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extra_tabs` (
			`id_tab` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`position` int(10) unsigned NOT NULL DEFAULT \'0\',
            `active` TINYINT(1) unsigned DEFAULT 1,
            `category` varchar(255) NOT NULL,
            `product` varchar(255) NOT NULL,
			PRIMARY KEY(`id_tab`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8')&&
        Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extra_tabs_lang` (
			`id_tab` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
			`title` varchar(255),
            `content` text ,
			PRIMARY KEY(`id_tab`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8')&&
        Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extra_tabs_shop` (
			`id_tab` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
			PRIMARY KEY(`id_tab`,`id_shop`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8'));
	}
    public function uninstall()
	{
		if (!parent::uninstall() ||
			!$this->uninstallDB())
			return false;
		return true;
	}
    private function uninstallDb()
	{
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS `'._DB_PREFIX_.'extra_tabs`, `'._DB_PREFIX_.'extra_tabs_lang`, `'._DB_PREFIX_.'extra_tabs_shop`;
		');
    }
    public function getContent()
    {
        $output ='';
        $errors = array();
        if (Tools::isSubmit('submitSaveTab')){
            if (Tools::isSubmit('id_tab')){
               $extra_tab = new Extratabs((int)Tools::getValue('id_tab'));
               if (!Validate::isLoadedObject($extra_tab))
				{
					$errors[] = $this->displayError($this->l('Invalid id_slide'));
					return;
				}
            }else{
                $extra_tab = new Extratabs();
            }
            /* Sets position */
			$extra_tab->position = (int)Tools::getValue('position');
			/* Sets active */
			$extra_tab->active = (int)Tools::getValue('active');
            $extra_tab->category = Tools::getValue('category');
            $extra_tab->product = Tools::getValue('product');
            $languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
                $extra_tab->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
                $extra_tab->content[$language['id_lang']] = Tools::getValue('tabcontent_'.$language['id_lang']);
			}
            if (!$errors){
                /* Adds */
				if (!Tools::isSubmit('id_tab'))
				{
					if (!$extra_tab->add())
						$errors[] = $this->displayError($this->l('The tab could not be added.'));
				}
				/* Update */
				elseif (!$extra_tab->update())
					$errors[] = $this->displayError($this->l('The tab could not be updated.'));
                Tools::redirectAdmin(AdminController::$currentIndex .
            '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'));
            }
        }elseif(Tools::isSubmit('removetab')){
            if (Tools::isSubmit('id_tab')){
               $extra_tab = new Extratabs((int)Tools::getValue('id_tab'));
               if (!$extra_tab->delete()){
                    $errors[] = $this->displayError('Could not delete');
               }
            }
        }elseif(Tools::isSubmit('changeactive')){
            if (Tools::isSubmit('id_tab')){
               $extra_tab = new Extratabs((int)Tools::getValue('id_tab'));
               $extra_tab->active = !$extra_tab->active;
               if (!$extra_tab->update()){
                    $errors[] = $this->displayError('Could not change');
               }else{
                Tools::redirectAdmin(AdminController::$currentIndex .
            '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'));
               }
            }
        }
        $this->context->smarty->assign(array(
            'postAction' => AdminController::$currentIndex .'&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'ajaxurl' => $this->_path.'ajax.php',
        ));
        if (count($errors))
            $output .= $this->displayError(implode('<br />', $errors));
        if (Tools::isSubmit('itemsubmit'))
            return $output.$this->displayItemForm();
        else
            return $output.$this->displayForm();
    }
    public function hookDisplayBackOfficeHeader()
	{
		
		if (Tools::getValue('configure') != $this->name)
			return;
        $iso = Language::getIsoById((int)($this->context->language->id));        
        $ad = dirname($_SERVER["PHP_SELF"]);
        $this->smarty->assign(array('ad' => $ad, 'iso'=>$iso));
        
		$this->context->controller->addCSS($this->_path.'css/admin.css');
		$this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS($this->_path.'js/tinymce.inc.js');
        $this->context->controller->addJS($this->_path.'js/jquery-ui.js');
		$this->context->controller->addJS($this->_path.'js/admin_extratabs.js');
	}
    public function displayForm(){
        $extra_tabs = $this->getTabs();
        $this->context->smarty->assign('tabs', $extra_tabs);
        $html = $this->display(__FILE__, 'views/templates/admin/main.tpl');
        return $html;
    }

    public function displayItemForm(){
        $extra_tab = null;
        if (Tools::isSubmit('id_tab')){
            $id_tab = (int)Tools::getValue('id_tab');
            $extra_tab = new Extratabs($id_tab);
        }
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
  		$languages = Language::getLanguages(false);
        $lang_ul = '<ul class="dropdown-menu">';
        foreach ($languages as $lg){
            $lang_ul .='<li><a href="javascript:hideOtherLanguage('.$lg['id_lang'].');" tabindex="-1">'.$lg['name'].'</a></li>';
        }					
        $lang_ul .='</ul>';
        if (!is_null($extra_tab))
            $product_option = $this->getProductOption($extra_tab->category,$extra_tab->product);
        else
            $product_option = $this->getProductOption();
        $category_option = (isset($extra_tab->category)? $this->getCategoryOption(1,$extra_tab->category):$this->getCategoryOption());
        $this->context->smarty->assign(array(
            'tab'=> $extra_tab,
            'lang_ul' => $lang_ul,
            'product_option' => $product_option,
            'category_option' => $category_option,
            'langguages' => array(
				'default_lang' => $id_lang_default,
				'all' => $languages,
				'lang_dir' => _THEME_LANG_DIR_)
        ));
        $id_lang = $this->context->language->id;
        $iso = Language::getIsoById((int)($id_lang));
        $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ?
            $iso : 'en');
        $ad = dirname($_SERVER["PHP_SELF"]);
        $html = '
    			<script type="text/javascript">
    			var iso = \'' . $isoTinyMCE . '\' ;
    			var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
    			var ad = \'' . $ad . '\' ;
    			$(document).ready(function(){
    			tinySetup({
    				editor_selector :"rte",
            		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,fontselect,fontsizeselect",
            		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,codemagic,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",            		theme_advanced_toolbar_location : "top",
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
    			});</script>';
        return $html.$this->display(__FILE__, 'views/templates/admin/itemform.tpl');
    }
    
    private function getProductOption($id_category = null, $selected = null){
        if (is_null($id_category) || $id_category = 'all')
            return '';
        $html ='';
        $products =  $this->getProductsByCategory($id_category);
        if ($products && count($products)>0)
            foreach ($products as $pro){
                if (!is_null($selected))
                    $html .= '<option '.($pro['id_product']==$selected? 'selected="selected"':'').' value="'.$pro['id_product'].'">'.$pro['name'].'</option>';
                else
                    $html .= '<option value="'.$pro['id_product'].'">'.$pro['name'].'</option>';
            }
        return $html;
    }
    public function getTabs($active=null){
        $id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT  et.*,etl.title,etl.content
			FROM '._DB_PREFIX_.'extra_tabs et
			LEFT JOIN '._DB_PREFIX_.'extra_tabs_lang etl ON (et.id_tab = etl.id_tab)
			LEFT JOIN '._DB_PREFIX_.'extra_tabs_shop ets ON (et.id_tab = ets.id_tab)
			WHERE (ets.`id_shop` = '.(int)$id_shop.')
			AND etl.`id_lang` = '.(int)$id_lang.
			($active ? ' AND et.`active` = 1' : ' ').'
			ORDER BY et.position');
    }

    public function getProductsByCategory($id_category = 1){
        $id_shop = $this->context->shop->id;
        return DB::getInstance()->ExecuteS('SELECT pl.id_product, pl.name
                                            FROM '._DB_PREFIX_.'category_product AS cp, '._DB_PREFIX_.'product_lang AS pl
                                            WHERE
                                                cp.id_product = pl.id_product AND
                                                cp.id_category = '.$id_category.' AND
                                                pl.id_shop = '.$id_shop.' AND
                                                pl.id_lang = '.(int)(Configuration::get('PS_LANG_DEFAULT')));


    }

    public function getCategoryOption($id_category = 1, $selected = null, $id_lang = false, $id_shop = false,
        $recursive = true, $link = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id))
            return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)
                $id_shop);
            $spacer = str_repeat('&nbsp;', 5 * (int)$category->level_depth);
        }
        $shop = (object)Shop::getShop((int)$category->getShopID());
        if ($category->id != 1)
            if ($link)
                $html .= '<option '.($selected ==$category->id? 'selected="selected" ':'').'value="' . $this->context->link->getCategoryLink($category->
                    id) . '">'.(isset($spacer) ? $spacer : '') . $category->name . '</option>';
            else
                $html .= '<option '.($selected ==$category->id? 'selected="selected" ':'').'value="' . (int)$category->id . '">'.(isset($spacer) ? $spacer : '') . $category->name .
                    '</option>';
        if (isset($children) && count($children))
            foreach ($children as $child)
            {
                $html .= $this->getCategoryOption((int)$child['id_category'],$selected, (int)$id_lang, (int)
                    $child['id_shop'], $recursive, $link);
            }
        return $html;
    }
    public function getTabsByProduct($id_product = null){
        if (!$id_product)
            return;
        $product = new Product($id_product);
        $tabs = $this->getTabs(true);
        $result=array();
        foreach ($tabs as $tab){
            if(($tab['category']== 'all')|| ($tab['category']==$product->id_category_default && $tab['product']=='all')||($tab['category']==$product->id_category_default && (int)$tab['product']==$id_product))
                $result[] = $tab;
        }
        return $result;

    }
    public function hookProductTab($id_tab_content){

        $id_product = (int)Tools::getValue('id_product');
        $tabs = $this->getTabsByProduct($id_product);
        if (count($tabs)>0){
            $this->smarty->assign('tabs',$tabs);
            return $this->display(__FILE__, 'productextratabs.tpl');
        }
    }
    public function hookProductTabContent($params){
        $id_product = (int)Tools::getValue('id_product');
        $tabs = $this->getTabsByProduct($id_product);
        if (count($tabs)>0){
            $this->smarty->assign('tabs',$tabs);
            return $this->display(__FILE__, 'productextratabcontent.tpl');
        }
    }
}