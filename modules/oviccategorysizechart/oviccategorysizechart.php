<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 * @version 1.2 (2012-03-14)
 */

if (!defined('_PS_VERSION_'))
	exit;

include_once(_PS_MODULE_DIR_.'oviccategorysizechart/categorysizechart.php');

class OvicCategorySizeChart extends Module
{
	private $_html = '';

	public function __construct()
	{
		$this->name = 'oviccategorysizechart';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'OvicSoft';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);

		parent::__construct();
        $this->bootstrap = true;
		$this->displayName = $this->l('Supershop - Categories size chart.');
		$this->description = $this->l('Display size chart for  categories.');
	}

	/**
	 * @see Module::install()
	 */
	public function install()
	{
		/* Adds Module */
		if (parent::install() 
			&& $this->registerHook('displayHeader') 
			&& $this->registerHook('displayBackOfficeHeader') 
			&& $this->registerHook('displayOvicCategorySizeChart') 
			&& $this->registerHook('actionShopDataDuplication')){
			$res = '';
			/* Creates tables */
			$res &= $this->createTables();
            $root_cate = Category::getRootCategories($this->context->cookie->id_lang);
            foreach ($root_cate as $cate){
                if ($cate['name'] == 'Home'){
                    Configuration::updateValue('ROOT_CATEGORY', $cate['id_category']);
                    break;
                }
            }
			return true;
		}
		return false;
	}


	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		/* Deletes Module */
		if (parent::uninstall()){			
			$res = $this->deleteTables();
			return $res;
		}
		return false;
	}
	public function reset()
	{
		if (!$this->uninstall()) return false;
		if (!$this->install()) return false;        
		return true;
	}
	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		/* Category Size chart - shop */
		$res = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'categorysizechart_shop` (
				`id_category_sizechart` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id_category_sizechart`, `id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		/* Category Size chart */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'category_sizechart` (
			  `id_category_sizechart` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `id_category` int(10) unsigned,
			  `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  PRIMARY KEY (`id_category_sizechart`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		/* Category Size chart - lang */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'category_sizechart_lang` (
			  `id_category_sizechart` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `image` varchar(255) NOT NULL,
			  PRIMARY KEY (`id_category_sizechart`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		return $res;
	}

	/**
	 * deletes tables
	 */
	protected function deleteTables()
	{
		return DB::getInstance()->execute('DROP TABLE IF EXISTS 
		`'._DB_PREFIX_.'categorysizechart_shop`, 
		`'._DB_PREFIX_.'category_sizechart`, 
		`'._DB_PREFIX_.'category_sizechart_lang`');
	}

	public function getContent()
	{
		$this->context->controller->addJS($this->_path.'js/admin_catesizechart.js');
		$this->_html .= $this->headerHTML();
		$this->_html .= '<h2>'.$this->displayName.'.</h2>';

        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
  		$languages = Language::getLanguages(false);
        $lang_ul = '<ul class="dropdown-menu">';
        foreach ($languages as $lg){
            $lang_ul .='<li><a href="javascript:hideOtherLanguage('.$lg['id_lang'].');" tabindex="-1">'.$lg['name'].'</a></li>';
        }
        $lang_ul .='</ul>';

        $this->context->smarty->assign(array(
            'postAction' => AdminController::$currentIndex .'&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'imgpath' => $this->_path.'images/',
            'lang_ul' => $lang_ul,
            'langguages' => array(
				'default_lang' => $id_lang_default,
				'all' => $languages,
				'lang_dir' => _THEME_LANG_DIR_)
        ));
		/* Validate & process */
		if (Tools::isSubmit('submitSizechart') || Tools::isSubmit('delete_id_sizechart') || Tools::isSubmit('changeStatus'))
		{
			if ($this->_postValidation()){
			     $this->_postProcess();
			}
			$this->_html .= $this->_displayForm();
		}
		elseif (Tools::isSubmit('addSizechart') || (Tools::isSubmit('id_sizechart') && $this->sizeChartExists((int)Tools::getValue('id_sizechart'))))
			$this->_html .= $this->_displayAddForm();
		else
			$this->_html .= $this->_displayForm();

		return $this->_html;
	}
    public function hookDisplayBackOfficeHeader()
	{
		if (Tools::getValue('configure') != $this->name)
			return;
		
	}
	private function _displayForm()
	{
		/* Gets Sizechart */
		$id_lang = $this->context->cookie->id_lang;
        $root_cate_id = Configuration::get('ROOT_CATEGORY');
        $id_category = Tools::getValue('id_category',$root_cate_id);
        $current_cate = new Category($id_category,$id_lang);
        $parent_cate = new Category($current_cate->id_parent,$id_lang);
        $categories = Category::getChildren($id_category,$id_lang);

        $displayList = false;
        $sizecharts = array();
        if (Tools::getValue('id_category') &&  $id_category > $root_cate_id)
        {
            $displayList = true;
            $sizecharts = $this->getSizechart(null,$id_category);
        }
        //category tree
		// Generate category selection tree
		$tree = new HelperTreeCategories('categories-tree', $this->l('Filter by category'));
		$tree->setAttribute('is_category_filter', (bool)$id_category)
			->setAttribute('base_url', preg_replace('#&id_category=[0-9]*#', '', AdminController::$currentIndex).'&token='.Tools::getAdminTokenLite('AdminModules'))
			->setInputName('id-category')
			->setSelectedCategories(array((int)$id_category));
		$categoryTree = $tree->render();
        $this->context->smarty->assign(array(
            'categoryTree' => $categoryTree,
            'displayList' => $displayList,
            'current_cate' => $current_cate,
            'sizecharts' => $sizecharts,
        ));
        return $this->display(__FILE__, 'views/templates/admin/main.tpl');
	}

	private function _displayAddForm()
	{
		/* Sets Slide : depends if edited or added */
		$sizechart = null;
        $id_category = null;
		if (Tools::isSubmit('id_sizechart') && $this->sizeChartExists((int)Tools::getValue('id_sizechart')))
			$sizechart = new CategorySizeChart((int)Tools::getValue('id_sizechart'));
        if (Tools::isSubmit('id_category'))
            $id_category = (int)Tools::getValue('id_category');
		/* Checks if directory is writable */
		if (!is_writable('.'))
			$this->adminDisplayWarning(sprintf($this->l('Modules %s must be writable (CHMOD 755 / 777)'), $this->name));

		/* Gets languages and sets which div requires translations */
		//$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		//$languages = Language::getLanguages(false);

        $this->context->smarty->assign(array(
            'sizechart' => $sizechart,
            'id_category' => $id_category,
            'newposition' => $this->getNextPosition(),
        ));
        $id_lang = $this->context->language->id;
        $iso = Language::getIsoById((int)($id_lang));
        $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ?
            $iso : 'en');
        $ad = dirname($_SERVER["PHP_SELF"]);
        $html = '
            <script type="text/javascript" src="' . __PS_BASE_URI__ .
            'js/tiny_mce/tiny_mce.js"></script>
            <script type="text/javascript" src="' . __PS_BASE_URI__ .
            'js/tinymce.inc.js"></script>
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

	private function _postValidation()
	{
		$errors = array();
 
	   if (Tools::isSubmit('changeStatus'))
		{
			if (!Validate::isInt(Tools::getValue('id_sizechart')))
				$errors[] = $this->l('Invalid sizechart');
		}
		/* Validation for Slide */
		elseif (Tools::isSubmit('submitSizechart'))
		{
			/* Checks state (active) */
			if (!Validate::isInt(Tools::getValue('active_sizechart')) || (Tools::getValue('active_sizechart') != 0 && Tools::getValue('active_sizechart') != 1))
				$errors[] = $this->l('Invalid sizechart state');
			/* Checks position */
			if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0))
				$errors[] = $this->l('Invalid sizechart position');
			/* If edit : checks id_sizechart */
			if (Tools::isSubmit('id_sizechart'))
			{
				if (!Validate::isInt(Tools::getValue('id_sizechart')) && !$this->sizeChartExists(Tools::getValue('id_sizechart')))
					$errors[] = $this->l('Invalid id_sizechart');
			}
			/* Checks title/url/legend/description/image */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
			//	if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 255)
//					$errors[] = $this->l('The URL is too long.');
//				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_'.$language['id_lang'])))
//					$errors[] = $this->l('The URL format is not correct.');
				if (Tools::getValue('image_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
				if (Tools::getValue('image_old_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
			}
			/* Checks title/url/legend/description for default lang */
			$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		
			//if (Tools::strlen(Tools::getValue('url_'.$id_lang_default)) == 0)
//				$errors[] = $this->l('The URL is not set.');
			if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_'.$id_lang_default]) || empty($_FILES['image_'.$id_lang_default]['tmp_name'])))
				$errors[] = $this->l('The image is not set.');
			if (Tools::getValue('image_old_'.$id_lang_default) && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default)))
				$errors[] = $this->l('The image is not set.');
		} /* Validation for deletion */
		elseif (Tools::isSubmit('delete_id_sizechart') && (!Validate::isInt(Tools::getValue('delete_id_sizechart')) || !$this->sizeChartExists((int)Tools::getValue('delete_id_sizechart'))))
			$errors[] = $this->l('Invalid id_sizechart');

		/* Display errors if needed */
		if (count($errors))
		{
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}

		/* Returns if validation is ok */
		return true;
	}

	private function _postProcess()
	{
	   print_r($_POST);
		$errors = array();
	   /* Process Slide status */
       
		if (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_sizechart'))
		{
			$sizechart = new CategorySizeChart((int)Tools::getValue('id_sizechart'));
			if ($sizechart->active == 0)
				$sizechart->active = 1;
			else
				$sizechart->active = 0;
			$res = $sizechart->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
		}
		/* Processes Slide */
		elseif (Tools::isSubmit('submitSizechart'))
		{
			/* Sets ID if needed */
			if (Tools::getValue('id_sizechart'))
			{
				$sizechart = new CategorySizeChart((int)Tools::getValue('id_sizechart'));
				if (!Validate::isLoadedObject($sizechart))
				{
					$this->_html .= $this->displayError($this->l('Invalid id_sizechart'));
					return;
				}
			}
			else
				$sizechart = new CategorySizeChart();
            
			/* Sets position */
			$sizechart->position = (int)Tools::getValue('position');
			/* Sets active */
			$sizechart->active = (int)Tools::getValue('active_sizechart');
            $sizechart->id_category = (int)Tools::getValue('id_category');
			/* Sets each langue fields */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				//$sizechart->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);

				/* Uploads image and sets sizechart */
				$type = strtolower(substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
				$imagesize = array();
				$imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
				if (isset($_FILES['image_'.$language['id_lang']]) &&
					isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
					!empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
					!empty($imagesize) &&
					in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
					in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
				{
					$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
					$salt = sha1(microtime());
					if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']]))
						$errors[] = $error;
					elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name))
						return false;
					elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/images/'.Tools::encrypt($_FILES['image_'.$language['id_lang']]['name'].$salt).'.'.$type, null, null, $type))
						$errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
					if (isset($temp_name))
						@unlink($temp_name);
					$sizechart->image[$language['id_lang']] = Tools::encrypt($_FILES['image_'.($language['id_lang'])]['name'].$salt).'.'.$type;
				}
				elseif (Tools::getValue('image_old_'.$language['id_lang']) != '')
					$sizechart->image[$language['id_lang']] = Tools::getValue('image_old_'.$language['id_lang']);
			}

			/* Processes if no errors  */
			if (!$errors)
			{
				/* Adds */
				if (!Tools::getValue('id_sizechart'))
				{
					if (!$sizechart->add())
						$errors[] = $this->displayError($this->l('The sizechart could not be added.'));
				}
				/* Update */
				elseif (!$sizechart->update())
					$errors[] = $this->displayError($this->l('The sizechart could not be updated.'));
				$this->clearCache();
			}
		} /* Deletes */
		elseif (Tools::isSubmit('delete_id_sizechart'))
		{
			$sizechart = new CategorySizeChart((int)Tools::getValue('delete_id_sizechart'));
			$res = $sizechart->delete();
			$this->clearCache();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete');
			else
				$this->_html .= $this->displayConfirmation($this->l('Slide deleted'));
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitSizechart') && Tools::getValue('id_sizechart'))
			$this->_html .= $this->displayConfirmation($this->l('Slide updated'));
		elseif (Tools::isSubmit('submitSizechart'))
			$this->_html .= $this->displayConfirmation($this->l('Slide added'));
	}

	private function _prepareHook($params)
	{
		if (!$this->isCached('oviccategorysizechart.tpl', $this->getCacheId()))
		{
            $id_category = Tools::getValue('id_category');
			$sizecharts = $this->getSizechart(true,$id_category);
			if (!$sizecharts)
				return false;

			$this->smarty->assign('category_sizechart', $sizecharts);
		}

		return true;
	}

	public function hookdisplayOvicCategorySizeChart($params)
	{
         $module_name = '';
		if (Validate::isModuleName(Tools::getValue('module')))
			$module_name = Tools::getValue('module');
       
        if (!empty($this->context->controller->php_self))
            $page_name = $this->context->controller->php_self;
        elseif (Tools::getValue('fc') == 'module' && $module_name != '' && (Module::
            getInstanceByName($module_name) instanceof PaymentModule))
            $page_name = 'module-payment-submit';
        // @retrocompatibility Are we in a module ?
        elseif (preg_match('#^' . preg_quote($this->context->shop->physical_uri, '#') .
            'modules/([a-zA-Z0-9_-]+?)/(.*)$#', $_SERVER['REQUEST_URI'], $m))
            $page_name = 'module-' . $m[1] . '-' . str_replace(array('.php', '/'), array('',
                    '-'), $m[2]);
        else {
            $page_name = Dispatcher::getInstance()->getController();
            $page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_' . $page_name : $page_name);
        }
        if (strlen($page_name) <= 0)
            return '';
        if ($page_name =='category'){
            $id_category = (int)Tools::getValue('id_category');
        }elseif($page_name =='product'){
            $id_product = (int)Tools::getValue('id_product');
            $product = new Product($id_product, true, $this->context->language->id, $this->context->shop->id);
            $id_category = $product->id_category_default;
        }
	       
        if(!isset($id_category) or is_null($id_category)) 
            return;
		$sizecharts = $this->getSizechart(true,$id_category);
        $check_sizechart = true;
		if (!$sizecharts && count($sizecharts)<1)
			$check_sizechart = false;
            
        if ($page_name =='category' || $page_name =='product'){
            $this->smarty->assign('category_sizechart', $sizecharts);
        }else {
            return;
        }
		
		if(!$check_sizechart)
			return;
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		return $this->display(__FILE__, 'oviccategorysizechart.tpl');
	}
    public function hookDisplayHeader($params) {
        $this->context->controller->addjQueryPlugin(array('fancybox'));
        $this->context->controller->addJS($this->_path.'js/oviccategorysizechart.js');
        
        // Register hook
        $this->context->smarty->assign(array(
            'HOOK_OVIC_CATEGORYSIZECHART' => Hook::exec('displayOvicCategorySizeChart'),
        ));
    }
	public function clearCache()
	{
		$this->_clearCache('oviccategorysizechart.tpl');
	}

	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'categorysizechart_shop (id_category_sizechart, id_shop)
		SELECT id_category_sizechart, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'categorysizechart_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
		$this->clearCache();
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
		/* Style & js for fieldset 'sizecharts configuration' */
		$html = '
		<style>
		#sizecharts li {
			list-style: none;
			margin: 0 0 4px 0;
			padding: 10px;
			background-color: #F4E6C9;
			border: #CCCCCC solid 1px;
			color:#000;
		}
		</style>

		<script type="text/javascript">
			$(function() {
				var $mySlides = $("#sizecharts");
				$mySlides.sortable({
					opacity: 0.6,
					cursor: "move",
					update: function() {
						var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
						$.post("'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
						}
					});
				$mySlides.hover(function() {
					$(this).css("cursor","move");
					},
					function() {
					$(this).css("cursor","auto");
				});
			});
		</script>';
		return $html;
	}

	public function getNextPosition()
	{
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
				SELECT MAX(csc.`position`) AS `next_position`
				FROM `'._DB_PREFIX_.'category_sizechart` csc, `'._DB_PREFIX_.'categorysizechart_shop` cs
				WHERE csc.`id_category_sizechart` = cs.`id_category_sizechart` AND cs.`id_shop` = '.(int)$this->context->shop->id
		);

		return (++$row['next_position']);
	}

	public function getSizechart($active = null,$id_category = null)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT cs.`id_category_sizechart` as id_sizechart,
					   cscl.`image`,
					   csc.`position`,
                       csc.`id_category`,
					   csc.`active`
			FROM '._DB_PREFIX_.'categorysizechart_shop cs
			LEFT JOIN '._DB_PREFIX_.'category_sizechart csc ON (cs.id_category_sizechart = csc.id_category_sizechart)
			LEFT JOIN '._DB_PREFIX_.'category_sizechart_lang cscl ON (csc.id_category_sizechart = cscl.id_category_sizechart)
			WHERE (id_shop = '.(int)$id_shop.')'.
            ($id_category ? ' AND csc.`id_category` = '.(int)$id_category : ' ').'
			AND cscl.id_lang = '.(int)$id_lang.
			($active ? ' AND csc.`active` = 1' : ' ').'
			ORDER BY csc.position');
	}
	public function displayStatus($id_sizechart, $active, $id_category)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$img = ((int)$active == 0 ? 'disabled.gif' : 'enabled.gif');
		$html = '<a href="'.AdminController::$currentIndex.
				'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_sizechart='.(int)$id_sizechart.'&id_category='.(int)$id_category.'" title="'.$title.'"><img src="'._PS_ADMIN_IMG_.''.$img.'" alt="" /></a>';
		return $html;
	}

	public function sizeChartExists($id_sizechart)
	{
		$req = 'SELECT cs.`id_category_sizechart` as id_sizechart
				FROM `'._DB_PREFIX_.'categorysizechart_shop` cs
				WHERE cs.`id_category_sizechart` = '.(int)$id_sizechart;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}

}
