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

include_once(_PS_MODULE_DIR_.'categoryslider/CategorySlide.php');

class CategorySlider extends Module
{
	private $_html = '';

	public function __construct()
	{
		$this->name = 'categoryslider';
		$this->tab = 'front_office_features';
		$this->version = '1.2.1';
		$this->author = 'OvicSoft';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);

		parent::__construct();
        $this->bootstrap = true;
		$this->displayName = $this->l('Supershop - Category Image slider.');
		$this->description = $this->l('Adds an image slider to category page.');
	}

	/**
	 * @see Module::install()
	 */
	public function install()
	{
		/* Adds Module */
		if (parent::install() && $this->registerHook('displayHeader') && $this->registerHook('displayBackOfficeHeader') && $this->registerHook('displayCategorySlider') && $this->registerHook('actionShopDataDuplication'))
		{
			/* Sets up configuration */
			$res = Configuration::updateValue('CATESLIDER_WIDTH', '870');
			$res &= Configuration::updateValue('CATESLIDER_HEIGHT', '290');
			$res &= Configuration::updateValue('CATESLIDER_SPEED', '1500');
			$res &= Configuration::updateValue('CATESLIDER_PAUSE', '3000');
			$res &= Configuration::updateValue('CATESLIDER_LOOP', '1');
			/* Creates tables */
			$res &= $this->createTables();

			/* Adds samples */
		//	if ($res)
//				$this->installSamples()
            $root_cate = Category::getRootCategories($this->context->cookie->id_lang);
            foreach ($root_cate as $cate){
                if ($cate['name'] == 'Home'){
                    Configuration::updateValue('ROOT_CATEGORY', $cate['id_category']);
                    break;
                }

            }
			return $res;
		}
		return false;
	}

	/**
	 * Adds samples
	 */
	private function installSamples()
	{
		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 5; ++$i)
		{
			$slide = new CategorySlide();
			$slide->position = $i;
			$slide->active = 1;
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = 'Sample '.$i;
				$slide->description[$language['id_lang']] = 'This is a sample picture';
				$slide->legend[$language['id_lang']] = 'sample-'.$i;
				$slide->url[$language['id_lang']] = 'http://www.prestashop.com';
				$slide->image[$language['id_lang']] = 'sample-'.$i.'.jpg';
			}
			$slide->add();
		}
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		/* Deletes Module */
		if (parent::uninstall())
		{
			/* Deletes tables */
			$res = $this->deleteTables();
			/* Unsets configuration */
			$res &= Configuration::deleteByName('CATESLIDER_WIDTH');
			$res &= Configuration::deleteByName('CATESLIDER_HEIGHT');
			$res &= Configuration::deleteByName('CATESLIDER_SPEED');
			$res &= Configuration::deleteByName('CATESLIDER_PAUSE');
			$res &= Configuration::deleteByName('CATESLIDER_LOOP');
			return $res;
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		/* Slides */
		$res = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'categoryslider` (
				`id_categoryslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id_categoryslider_slides`, `id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'categoryslider_slides` (
			  `id_categoryslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `id_category` int(10) unsigned,
			  `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  PRIMARY KEY (`id_categoryslider_slides`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides lang configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'categoryslider_slides_lang` (
			  `id_categoryslider_slides` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `legend` varchar(255) NOT NULL,
			  `url` varchar(255) NOT NULL,
			  `image` varchar(255) NOT NULL,
			  PRIMARY KEY (`id_categoryslider_slides`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		return $res;
	}

	/**
	 * deletes tables
	 */
	protected function deleteTables()
	{
		$slides = $this->getSlides();
		foreach ($slides as $slide)
		{
			$to_del = new CategorySlide($slide['id_slide']);
			$to_del->delete();
		}
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS `'._DB_PREFIX_.'categoryslider`, `'._DB_PREFIX_.'categoryslider_slides`, `'._DB_PREFIX_.'categoryslider_slides_lang`;
		');
	}

	public function getContent()
	{
		$this->context->controller->addJS($this->_path.'js/admin_cateslide.js');
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
		if (Tools::isSubmit('submitSlide') || Tools::isSubmit('delete_id_slide') ||
			Tools::isSubmit('submitSlider') ||
			Tools::isSubmit('changeStatus'))
		{
			if ($this->_postValidation())
				$this->_postProcess();
			$this->_html .= $this->_displayForm();
		}
		elseif (Tools::isSubmit('addSlide') || (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))))
			$this->_html .= $this->_displayAddForm();
		else
			$this->_html .= $this->_displayForm();

		return $this->_html;
	}
    public function hookDisplayBackOfficeHeader()
	{
		if (Tools::getValue('configure') != $this->name)
			return;
		//$this->context->controller->addCSS($this->_path.'css/admin.css');
		//$this->context->controller->addJquery();
        //$this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        //$this->context->controller->addJS(_PS_JS_DIR_.'tinymce.inc.js');
        //$this->context->controller->addJS($this->_path.'js/jquery-ui.js');
		
	}
	private function _displayForm()
	{
		/* Gets Slides */
		$id_lang = $this->context->cookie->id_lang;
        $root_cate_id = Configuration::get('ROOT_CATEGORY');
        $id_category = Tools::getValue('id_category',$root_cate_id);
        $current_cate = new Category($id_category,$id_lang);
        $parent_cate = new Category($current_cate->id_parent,$id_lang);
        $categories = Category::getChildren($id_category,$id_lang);

        $displayList = false;
        $slides = array();
        if (Tools::getValue('id_category') &&  $id_category > $root_cate_id)
        {
            $displayList = true;
            $slides = $this->getSlides(null,$id_category);
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
            'slide_width' => Configuration::get('CATESLIDER_WIDTH'),
            'slide_height' => Configuration::get('CATESLIDER_HEIGHT'),
            'slide_speed' => Configuration::get('CATESLIDER_SPEED'),
            'slide_pause' => Configuration::get('CATESLIDER_PAUSE'),
            'slide_loop' =>Configuration::get('CATESLIDER_LOOP'),
            'categoryTree' => $categoryTree,
            'displayList' => $displayList,
            'current_cate' => $current_cate,
            'slides' => $slides,
        ));
        return $this->display(__FILE__, 'views/templates/admin/main.tpl');
	}

	private function _displayAddForm()
	{
		/* Sets Slide : depends if edited or added */
		$slide = null;
        $id_category = null;
		if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
			$slide = new CategorySlide((int)Tools::getValue('id_slide'));
        if (Tools::isSubmit('id_category'))
            $id_category = (int)Tools::getValue('id_category');
		/* Checks if directory is writable */
		if (!is_writable('.'))
			$this->adminDisplayWarning(sprintf($this->l('Modules %s must be writable (CHMOD 755 / 777)'), $this->name));

		/* Gets languages and sets which div requires translations */
		//$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		//$languages = Language::getLanguages(false);

        $this->context->smarty->assign(array(
            'slide' => $slide,
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

		/* Validation for Slider configuration */
		if (Tools::isSubmit('submitSlider'))
		{

			if (!Validate::isInt(Tools::getValue('CATESLIDER_SPEED')) || !Validate::isInt(Tools::getValue('CATESLIDER_PAUSE')) ||
				!Validate::isInt(Tools::getValue('CATESLIDER_WIDTH')) || !Validate::isInt(Tools::getValue('CATESLIDER_HEIGHT')))
					$errors[] = $this->l('Invalid values');
		} /* Validation for status */
		elseif (Tools::isSubmit('changeStatus'))
		{
			if (!Validate::isInt(Tools::getValue('id_slide')))
				$errors[] = $this->l('Invalid slide');
		}
		/* Validation for Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Checks state (active) */
			if (!Validate::isInt(Tools::getValue('active_slide')) || (Tools::getValue('active_slide') != 0 && Tools::getValue('active_slide') != 1))
				$errors[] = $this->l('Invalid slide state');
			/* Checks position */
			if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0))
				$errors[] = $this->l('Invalid slide position');
			/* If edit : checks id_slide */
			if (Tools::isSubmit('id_slide'))
			{
				if (!Validate::isInt(Tools::getValue('id_slide')) && !$this->slideExists(Tools::getValue('id_slide')))
					$errors[] = $this->l('Invalid id_slide');
			}
			/* Checks title/url/legend/description/image */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (Tools::strlen(Tools::getValue('title_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The title is too long.');
				if (Tools::strlen(Tools::getValue('legend_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The legend is too long.');
				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The URL is too long.');
				if (Tools::strlen(Tools::getValue('description_'.$language['id_lang'])) > 4000)
					$errors[] = $this->l('The description is too long.');
				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_'.$language['id_lang'])))
					$errors[] = $this->l('The URL format is not correct.');
				if (Tools::getValue('image_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
				if (Tools::getValue('image_old_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
			}
			/* Checks title/url/legend/description for default lang */
			$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
			if (Tools::strlen(Tools::getValue('title_'.$id_lang_default)) == 0)
				$errors[] = $this->l('The title is not set.');
			if (Tools::strlen(Tools::getValue('legend_'.$id_lang_default)) == 0)
				$errors[] = $this->l('The legend is not set.');
			if (Tools::strlen(Tools::getValue('url_'.$id_lang_default)) == 0)
				$errors[] = $this->l('The URL is not set.');
			if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_'.$id_lang_default]) || empty($_FILES['image_'.$id_lang_default]['tmp_name'])))
				$errors[] = $this->l('The image is not set.');
			if (Tools::getValue('image_old_'.$id_lang_default) && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default)))
				$errors[] = $this->l('The image is not set.');
		} /* Validation for deletion */
		elseif (Tools::isSubmit('delete_id_slide') && (!Validate::isInt(Tools::getValue('delete_id_slide')) || !$this->slideExists((int)Tools::getValue('delete_id_slide'))))
			$errors[] = $this->l('Invalid id_slide');

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
		$errors = array();

		/* Processes Slider */
		if (Tools::isSubmit('submitSlider'))
		{
			$res = Configuration::updateValue('CATESLIDER_WIDTH', (int)Tools::getValue('slide_width'));
			$res &= Configuration::updateValue('CATESLIDER_HEIGHT', (int)Tools::getValue('slide_height'));
			$res &= Configuration::updateValue('CATESLIDER_SPEED', (int)Tools::getValue('slide_speed'));
			$res &= Configuration::updateValue('CATESLIDER_PAUSE', (int)Tools::getValue('slide_pause'));
			$res &= Configuration::updateValue('CATESLIDER_LOOP', (int)Tools::getValue('slide_loop'));
			$this->clearCache();
			if (!$res)
				$errors[] = $this->displayError($this->l('The configuration could not be updated.'));
			$this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
		} /* Process Slide status */
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_slide'))
		{
			$slide = new CategorySlide((int)Tools::getValue('id_slide'));
			if ($slide->active == 0)
				$slide->active = 1;
			else
				$slide->active = 0;
			$res = $slide->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
		}
		/* Processes Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Sets ID if needed */
			if (Tools::getValue('id_slide'))
			{
				$slide = new CategorySlide((int)Tools::getValue('id_slide'));
				if (!Validate::isLoadedObject($slide))
				{
					$this->_html .= $this->displayError($this->l('Invalid id_slide'));
					return;
				}
			}
			else
				$slide = new CategorySlide();

			/* Sets position */
			$slide->position = (int)Tools::getValue('position');
			/* Sets active */
			$slide->active = (int)Tools::getValue('active_slide');
            $slide->id_category = (int)Tools::getValue('id_category');
			/* Sets each langue fields */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
				$slide->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);
				$slide->legend[$language['id_lang']] = Tools::getValue('legend_'.$language['id_lang']);
				$slide->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);

				/* Uploads image and sets slide */
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
					$slide->image[$language['id_lang']] = Tools::encrypt($_FILES['image_'.($language['id_lang'])]['name'].$salt).'.'.$type;
				}
				elseif (Tools::getValue('image_old_'.$language['id_lang']) != '')
					$slide->image[$language['id_lang']] = Tools::getValue('image_old_'.$language['id_lang']);
			}

			/* Processes if no errors  */
			if (!$errors)
			{
				/* Adds */
				if (!Tools::getValue('id_slide'))
				{
					if (!$slide->add())
						$errors[] = $this->displayError($this->l('The slide could not be added.'));
				}
				/* Update */
				elseif (!$slide->update())
					$errors[] = $this->displayError($this->l('The slide could not be updated.'));
				$this->clearCache();
			}
		} /* Deletes */
		elseif (Tools::isSubmit('delete_id_slide'))
		{
			$slide = new CategorySlide((int)Tools::getValue('delete_id_slide'));
			$res = $slide->delete();
			$this->clearCache();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete');
			else
				$this->_html .= $this->displayConfirmation($this->l('Slide deleted'));
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitSlide') && Tools::getValue('id_slide'))
			$this->_html .= $this->displayConfirmation($this->l('Slide updated'));
		elseif (Tools::isSubmit('submitSlide'))
			$this->_html .= $this->displayConfirmation($this->l('Slide added'));
	}

	private function _prepareHook($params)
	{
		if (!$this->isCached('categoryslider.tpl', $this->getCacheId()))
		{
			$slider = array(
				'width' => Configuration::get('CATESLIDER_WIDTH'),
				'height' => Configuration::get('CATESLIDER_HEIGHT'),
				'speed' => Configuration::get('CATESLIDER_SPEED'),
				'pause' => Configuration::get('CATESLIDER_PAUSE'),
				'loop' => Configuration::get('CATESLIDER_LOOP'),
			);
            $id_category = Tools::getValue('id_category');
			$slides = $this->getSlides(true,$id_category);
			if (!$slides)
				return false;

			$this->smarty->assign('categoryslider_slides', $slides);
			$this->smarty->assign('categoryslider', $slider);
		}

		return true;
	}

	public function hookdisplayCategorySlider($params)
	{
         $module_name = '';
		if (Validate::isModuleName(Tools::getValue('module')))
			$module_name = Tools::getValue('module');
            
        //if (!$this->isCached('categoryslider.tpl', $this->getCacheId()))
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
		$slider = array(
			'width' => Configuration::get('CATESLIDER_WIDTH'),
			'height' => Configuration::get('CATESLIDER_HEIGHT'),
			'speed' => Configuration::get('CATESLIDER_SPEED'),
			'pause' => Configuration::get('CATESLIDER_PAUSE'),
			'loop' => Configuration::get('CATESLIDER_LOOP'),
		);
		$slides = $this->getSlides(true,$id_category);
        $check_slide = true;
		if (!$slides && count($slides)<1)
			$check_slide = false;

		$this->smarty->assign('categoryslider_slides', $slides);
		$this->smarty->assign('categoryslider', $slider);
		
		if(!$check_slide) return;
		// Check if not a mobile theme
		//if ($this->context->getMobileDevice() != false)
		//	return false;
		
		return $this->display(__FILE__, 'categoryslider.tpl');
	}
    public function hookDisplayHeader($params) {
        // CSS in global.css
		//$this->context->controller->addCSS($this->_path.'bx_styles.css');
        $this->context->controller->addJS($this->_path.'js/categoryslider.js');
        
        $page_name = Dispatcher::getInstance()->getController();
        $page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_' . $page_name : $page_name);
        
        if ($page_name =='category' || $page_name =='product'){
            // Register hook
            $this->context->smarty->assign(array(
                'HOOK_OVIC_CATEGORYSLIDER' => Hook::exec('displayCategorySlider'),
            ));   
        }
    }
	public function clearCache()
	{
		$this->_clearCache('categoryslider.tpl');
	}

	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'categoryslider (id_categoryslider_slides, id_shop)
		SELECT id_categoryslider_slides, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'categoryslider
		WHERE id_shop = '.(int)$params['old_id_shop']);
		$this->clearCache();
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
		/* Style & js for fieldset 'slides configuration' */
		$html = '
		<style>
		#slides li {
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
				var $mySlides = $("#slides");
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
				SELECT MAX(hss.`position`) AS `next_position`
				FROM `'._DB_PREFIX_.'categoryslider_slides` hss, `'._DB_PREFIX_.'categoryslider` hs
				WHERE hss.`id_categoryslider_slides` = hs.`id_categoryslider_slides` AND hs.`id_shop` = '.(int)$this->context->shop->id
		);

		return (++$row['next_position']);
	}

	public function getSlides($active = null,$id_category = null)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_categoryslider_slides` as id_slide,
					   hssl.`image`,
					   hss.`position`,
                       hss.`id_category`,
					   hss.`active`,
					   hssl.`title`,
					   hssl.`url`,
					   hssl.`legend`,
					   hssl.`description`
			FROM '._DB_PREFIX_.'categoryslider hs
			LEFT JOIN '._DB_PREFIX_.'categoryslider_slides hss ON (hs.id_categoryslider_slides = hss.id_categoryslider_slides)
			LEFT JOIN '._DB_PREFIX_.'categoryslider_slides_lang hssl ON (hss.id_categoryslider_slides = hssl.id_categoryslider_slides)
			WHERE (id_shop = '.(int)$id_shop.')'.
            ($id_category ? ' AND hss.`id_category` = '.(int)$id_category : ' ').'
			AND hssl.id_lang = '.(int)$id_lang.
			($active ? ' AND hss.`active` = 1' : ' ').'
			ORDER BY hss.position');
	}
	public function displayStatus($id_slide, $active, $id_category)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$img = ((int)$active == 0 ? 'disabled.gif' : 'enabled.gif');
		$html = '<a href="'.AdminController::$currentIndex.
				'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_slide='.(int)$id_slide.'&id_category='.(int)$id_category.'" title="'.$title.'"><img src="'._PS_ADMIN_IMG_.''.$img.'" alt="" /></a>';
		return $html;
	}

	public function slideExists($id_slide)
	{
		$req = 'SELECT hs.`id_categoryslider_slides` as id_slide
				FROM `'._DB_PREFIX_.'categoryslider` hs
				WHERE hs.`id_categoryslider_slides` = '.(int)$id_slide;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}

}
