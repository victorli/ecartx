<?php
/**
* 2015 SNSTheme
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
*  @author    SNSTheme <contact@snstheme.com>
*  @copyright 2015 SNSTheme
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SNSTheme
*/

if (!defined('_PS_VERSION_')) exit;
include_once(dirname(__FILE__) . '/snsnaziccore.php');

class SNSNazicTheme extends SNSNazicCore {
	public function __construct() {
		$this->name = 'snsnazictheme';
		$this->tab = 'home';
		$this->version = '1.0';
		$this->author = 'SNS Theme';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6');
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('SNS Nazic Theme');
		$this->description = $this->l('Config params of theme');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}
	public function install($delete_params = true) {
		if (Shop::isFeatureActive())
	    	Shop::setContext(Shop::CONTEXT_ALL);
	 
	  	if (!parent::install() 
	  		|| !$this->registerHook('header')
			|| !$this->registerHook('displaySlideshow')
	  		|| !$this->registerHook('displayBackOfficeHeader')
			|| !$this->registerHook('actionAdminControllerSetMedia')
			|| !$this->registerHook('actionOrderStatusPostUpdate')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
			|| !$this->registerHook('innerLeftProduct')
			|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('displaySecondImage')

			|| !$this->registerHook('leftColumn')
			
			|| !$this->registerHook('displayFeaturedProduct')
			|| !$this->registerHook('displayNewProduct')
			|| !$this->registerHook('displaySpecialProduct')
			|| !$this->registerHook('displayDeal')
			|| !$this->registerHook('displayBestsale')

			|| !$this->registerHook('displayNewletter')

	  		)
	    	return false;
	    
	  	Configuration::updateValue('PS_USE_HTMLPURIFIER', '0');
	  	// Activate every option by default
	  	if ($delete_params) $this->installFixtures();
		
		$this->_createTab();
	  	return true;
	}
	public function uninstall($delete_params = true) {
  		foreach($this->themeFields as $key => $value) {
  			if ($delete_params) Configuration::deleteByName($key);
		}
	    $this->_deleteTab();
	    return parent::uninstall();
	}
	public function reset()
	{
		if (!$this->uninstall(false))
			return false;
		if (!$this->install(false))
			return false;

		return true;
	}
	public function hookActionAdminControllerSetMedia($params) {
		$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/css/sns-styles.css', 'all');
	}
	public function hookDisplayBackOfficeHeader($params) {
		$baseUri = $this->getBaseUrl().__PS_BASE_URI__;
		return '<script type="text/javascript">
					var baseUri = "'.$baseUri.'";
					var ad_basename = "'.basename(_PS_ADMIN_DIR_).'";
				</script>';
	}
	public function hookDisplaySlideshow($params) {
		if(!Configuration::get('SNS_NAZ_SLSTATUS')) return;
		if (!$this->isCached('slideshowimgs.tpl', $this->getCacheId())){
			$imgs = $this->snsUnSerialize(Configuration::get('SNS_NAZ_SLIMGS', $this->context->language->id));
			$auto = ((int)Configuration::get('SNS_NAZ_SLAUTO')) ? (int)Configuration::get('SNS_NAZ_SLAUTO') : false;
			$animateIn = Configuration::get('SNS_NAZ_SLANIMATEIN');
			$animateOut = Configuration::get('SNS_NAZ_ANIMATEOUT');
			$this->smarty->assign(array(
				'imgs' => $imgs,
				'auto' => $auto,
				'animateIn' => $animateIn,
				'animateOut' => $animateOut
			));
		}
		return $this->display(__FILE__, 'slideshowimgs.tpl', $this->getCacheId());
	}
	public function getProducts($params, $source, $nb) {
		$id_lang = $this->context->language->id;
		
		if (!Configuration::get('PS_CATALOG_MODE')) {
			$products = array();
			if($source == 'deals') {
				$products = SNSNazicProduct::getDealsProducts($id_lang, 0, $nb);
			} elseif ($source == 'specials') {
				$products = Product::getPricesDrop($id_lang, 0, $nb);
			} elseif ($source == 'viewed') {
				$products = SNSNazicProduct::getViewedProduct($params, $id_lang, 0, $nb);
			} elseif ($source == 'topsale') {
				$products = ProductSale::getBestSalesLight($id_lang, 0, $nb);
			} elseif ($source == 'new') {
				$products = SNSNazicProduct::getNewProducts(Configuration::get('PS_NB_DAYS_NEW_PRODUCT'), $id_lang, 0, $nb);
			} else {
				$products = Product::getProductsProperties($id_lang, Product::getProducts($id_lang, 0, $nb, 'date_add', 'ASC'));
			}
			
			$list = array();
			if (count($products)) {
				foreach ($products as $product)
				{
					$obj     = new Product((int) ($product['id_product']), false, $this->context->language->id);
					$images  = $obj->getImages($this->context->language->id);
					$_images = array();
					$id_image = '';
					if (!empty($images)) {
						foreach ($images as $k => $image) {
							if($image['cover']) $id_image = $obj->id . '-' . $image['id_image'];
							$_images[] = $obj->id . '-' . $image['id_image'];
						}
					}
					$id_image = ($id_image != '') ? $id_image : $_images[0];
					$product['id_image'] = $id_image;
					$list[] = $product;
				}
			}
			return array(
					'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
					'products' => $list
				);
		}
	}
	public function hookDisplaySecondImage($params) {
		if (!$this->isCached('displaySecondImage.tpl', $this->getCacheId($params['id_product']))) {
			$id_lang = $this->context->language->id;
			$obj     = new Product((int) ($params['id_product']), false, $id_lang);
			$images  = $obj->getImages($this->context->language->id);
			$_images = array();
			if (!empty($images)) {
				foreach ($images as $k => $image) {
					if($image['cover']){
						$_images['cover'] = $obj->id . '-' . $image['id_image'];
					} else {
						$_images['gallery'][] = $obj->id . '-' . $image['id_image'];
					}
					
				}
			}
			
			$this->smarty->assign(array(
				'link_rewrite' => $params['link_rewrite'],
				'images' => $_images,
				'listimg' => $_images['gallery'],
				'gallery_img' => Configuration::get('SNS_NAZ_GALLERYIMG')
			));
		}
		return $this->display(__FILE__, 'displaySecondImage.tpl', $this->getCacheId($params['id_product']));
	}


	
	public function hookInnerLeftProduct($params) {
		if (!$this->isCached('innerleftproduct.tpl', $this->getCacheId()))
			$this->smarty->assign( $this->getProducts($params, 'viewed', 10) );
		
		return $this->display(__FILE__, 'innerleftproduct.tpl', $this->getCacheId());
	}
	private function getCurrentProduct($products, $id_current)
	{
		if ($products)
		{
			foreach ($products as $key => $product)
			{
				if ($product['id_product'] == $id_current)
					return $key;
			}
		}

		return false;
	}
	public function hookDisplayFooterProduct($params) {
		$id_product = (int)$params['product']->id;
		$product = $params['product'];

		$cache_id = 'footerproduct|'.$id_product.'|'.(isset($params['category']->id_category) ? (int)$params['category']->id_category : (int)$product->id_category_default);

		if (!$this->isCached('footerproduct.tpl', $this->getCacheId($cache_id)))
		{
			$category = false;
			if (isset($params['category']->id_category))
				$category = $params['category'];
			else
			{
				if (isset($product->id_category_default) && $product->id_category_default > 1)
					$category = new Category((int)$product->id_category_default);
			}

			if (!Validate::isLoadedObject($category) || !$category->active)
				return false;

			// Get infos
			$category_products = $category->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
			$nb_category_products = (int)count($category_products);
			$middle_position = 0;

			// Remove current product from the list
			if (is_array($category_products) && count($category_products))
			{
				foreach ($category_products as $key => $category_product)
				{
					if ($category_product['id_product'] == $id_product)
					{
						unset($category_products[$key]);
						break;
					}
				}

				$taxes = Product::getTaxCalculationMethod();
				if (Configuration::get('PRODUCTSCATEGORY_DISPLAY_PRICE'))
				{
					foreach ($category_products as $key => $category_product)
					{
						if ($category_product['id_product'] != $id_product)
						{
							if ($taxes == 0 || $taxes == 2)
							{
								$category_products[$key]['displayed_price'] = Product::getPriceStatic(
									(int)$category_product['id_product'],
									true,
									null,
									2
								);
							} elseif ($taxes == 1)
							{
								$category_products[$key]['displayed_price'] = Product::getPriceStatic(
									(int)$category_product['id_product'],
									false,
									null,
									2
								);
							}
						}
					}
				}

				// Get positions
				$middle_position = (int)round($nb_category_products / 2, 0);
				$product_position = $this->getCurrentProduct($category_products, (int)$id_product);

				// Flip middle product with current product
				if ($product_position)
				{
					$tmp = $category_products[$middle_position - 1];
					$category_products[$middle_position - 1] = $category_products[$product_position];
					$category_products[$product_position] = $tmp;
				}

				// If products tab higher than 30, slice it
				if ($nb_category_products > 30)
				{
					$category_products = array_slice($category_products, $middle_position - 15, 30, true);
					$middle_position = 15;
				}
			}

			// Display tpl
			$this->smarty->assign(
				array(
					'categoryProducts' => $category_products,
					'middlePosition' => (int)$middle_position,
					'ProdDisplayPrice' => Configuration::get('PRODUCTSCATEGORY_DISPLAY_PRICE')
				)
			);
		}

		return $this->display(__FILE__, 'footerproduct.tpl', $this->getCacheId($cache_id));
	}



	public function hookLeftColumn($params) {
		if (!$this->isCached('columntwo.tpl', $this->getCacheId()))
		return $this->display(__FILE__, 'columntwo.tpl');
	}

	// Display featured product
	public function hookdisplayFeaturedProduct($params) {
		if (!$this->isCached('featuredproduct.tpl', $this->getCacheId()))
			$this->smarty->assign( $this->getProducts($params, '', 10) );
		return $this->display(__FILE__, 'featuredproduct.tpl', $this->getCacheId());
	}

	// Display new product
	public function hookdisplayNewProduct($params) {
		if (!$this->isCached('newproduct.tpl', $this->getCacheId()))
			$this->smarty->assign( $this->getProducts($params, 'new', 10) );
		return $this->display(__FILE__, 'newproduct.tpl', $this->getCacheId());
	}

	// Display special product
	public function hookdisplaySpecialProduct($params) {
		if (!$this->isCached('specialproduct.tpl', $this->getCacheId()))
			$this->smarty->assign( $this->getProducts($params, 'specials', 10) );
		return $this->display(__FILE__, 'specialproduct.tpl', $this->getCacheId());
	}

	// Display deal product
	public function hookdisplayDeal($params) {

		// $new = new Blocknewsletter;
		// return $new->hookDisplayLeftColumn($params);

		if (!$this->isCached('dealsproduct.tpl', $this->getCacheId()))
			$this->smarty->assign( $this->getProducts($params, 'deals', 10) );
		return $this->display(__FILE__, 'dealsproduct.tpl', $this->getCacheId());
	}

	// Display bestsale product
	public function hookdisplayBestsale($params) {
		if (!$this->isCached('bestsalesproduct.tpl', $this->getCacheId()))
			$this->smarty->assign( $this->getProducts($params, 'topsale', 10) );
		return $this->display(__FILE__, 'bestsalesproduct.tpl', $this->getCacheId());
	}

	// Display newletter in home
	public function hookdisplayNewletter($params) { 
	
		 if (class_exists('Blocknewsletter')) {
		   $news = new Blocknewsletter;
		   return $news->hookDisplayLeftColumn($params);
		  } else { return false; }
	}


	
	public function hookHeader() {
		global $cookie, $smarty, $cart;
	//	$this->clearCacheCss();
		
		$snsvar = array();

	//	$this->registerHook('displayNewletter');

  		foreach($this->themeFields as $key => $value) {
  			if($value['lang']) {
  				//
//				$languages = Language::getLanguages(false);
//				foreach ($languages as $lang) {
//					$values[$key][(int)$lang['id_lang']] = Configuration::get($key, Configuration::get('PS_LANG_DEFAULT'));
//					Configuration::updateValue($key, $values[$key], true);
//				}
				//
  				if(is_bool(Configuration::get($key, $this->context->language->id))) {
  					if($value['type'] == 'additem') {
  						$snsvar[$key] = $this->snsUnSerialize(Configuration::get($key, Configuration::get('PS_LANG_DEFAULT')));
  					} else {
						$snsvar[$key] = $this->SNSClass->replaceLinkContent(Configuration::get($key, Configuration::get('PS_LANG_DEFAULT')), true);
  					}
  				} else {
  					if($value['type'] == 'additem') {
  						$snsvar[$key] = $this->snsUnSerialize(Configuration::get($key, $this->context->language->id));
  					} else {
						$snsvar[$key] = $this->SNSClass->replaceLinkContent(Configuration::get($key, $this->context->language->id), true);
  					}
  				}
  			} else {
				$snsvar[$key] = $this->SNSClass->replaceLinkContent(Configuration::get($key), true);
  			}
		}
		$SNS_NAZ_SHOWCPANEL = Configuration::get('SNS_NAZ_SHOWCPANEL');
		if($SNS_NAZ_SHOWCPANEL){
			// CSS, JS for cpanel
			$this->context->controller->addCSS(__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/sns-cpanel.css', 'all');
			$this->context->controller->addCSS(__PS_BASE_URI__.'modules/'.$this->name.'/assets/front/css/jquery.miniColors.css', 'all');
			$this->context->controller->addJS(__PS_BASE_URI__.'modules/'.$this->name.'/assets/front/js/jquery.miniColors.min.js', 'all');

			if( Tools::getIsset('SNS_NAZCP_APPLY') && strtolower( Tools::getValue('SNS_NAZCP_APPLY') ) == "apply" ){
			//	die('xxxx');
		  		foreach($this->themeFields as $key => $value) {
                    if(Tools::getIsset(str_replace('SNS_NAZ_', 'SNS_NAZCP_', $key))){
                        $cookie->__set(str_replace('SNS_NAZ_', 'SNS_NAZCP_', $key), Tools::getValue(str_replace('SNS_NAZ_', 'SNS_NAZCP_', $key)) );
                    }
				}
				Tools::redirect( "index.php" );
			}
            if( Tools::getIsset('SNS_NAZCP_RESET') && strtolower( Tools::getValue('SNS_NAZCP_RESET') ) == "reset" ){
	  			foreach($this->themeFields as $key => $value) {
					$cookie->__unset(str_replace('SNS_NAZ_', 'SNS_NAZCP_', $key));
				}
				Tools::redirect( "index.php" );	
			}
			// Set value for params
	  		foreach($this->themeFields as $key => $value) {
				if($cookie->__get(str_replace('SNS_NAZ_', 'SNS_NAZCP_', $key))){
					$snsvar[$key] = $cookie->__get( str_replace('SNS_NAZ_', 'SNS_NAZCP_', $key));
	  			}
			}
			
			$this->context->controller->addJS(__PS_BASE_URI__.'themes/'._THEME_NAME_.'/js/sns-cpanel.js', 'all');
			$smarty->assign(array(
				'SNS_NAZ_XMLCFG'				=> $this->xmlConfig,
				'SNS_NAZ_PATTERN'				=> $this->getPatternsHTML(true, $snsvar['SNS_NAZ_BODYIMG']),
			));
		}
		
		$SNS_NAZ_GOOGLEFONT 		= $snsvar['SNS_NAZ_GOOGLEFONT'];
		$SNS_NAZ_GOOGLETARGETS 		= $snsvar['SNS_NAZ_GOOGLETARGETS'];
		$SNS_NAZ_FONTFAMILY 		= $snsvar['SNS_NAZ_FONTFAMILY'];
		$SNS_NAZ_FONTSIZE   		= $snsvar['SNS_NAZ_FONTSIZE'];
		$SNS_NAZ_BODYCOLOR  		= $snsvar['SNS_NAZ_BODYCOLOR'];
		$SNS_NAZ_BODYIMG    		= $snsvar['SNS_NAZ_BODYIMG'];
		
		$googlefontName = explode('|', $SNS_NAZ_GOOGLEFONT);
		$this->context->controller->addCSS('https://fonts.googleapis.com/css?family=' . $googlefontName[0], 'all');
		$gfontcss = '';
		if($SNS_NAZ_GOOGLETARGETS) {
			$gfontcss .= $SNS_NAZ_GOOGLETARGETS . '{ font-family: '.$googlefontName[1].' !important; }';
		}
		$patternsURL = $this->getBaseUrl().__PS_BASE_URI__."themes/"._THEME_NAME_."/img/patterns/";
		$SNS_NAZ_STYLE = '<style type="text/css">';
		$SNS_NAZ_STYLE .= 'body{ 
							font-family:'.$SNS_NAZ_FONTFAMILY.';
							font-size:'.$SNS_NAZ_FONTSIZE.';
							background-color:'.$SNS_NAZ_BODYCOLOR.';
							background-image: url("'.$patternsURL . $SNS_NAZ_BODYIMG .'");
							background-attachment: fixed;
							background-position: center top;
						}';
		$SNS_NAZ_STYLE .= $gfontcss;
		$SNS_NAZ_STYLE .= $snsvar['SNS_NAZ_CUSTOMCSS'];
		$SNS_NAZ_STYLE .= '</style>';
		
		$SNS_NAZ_SCRIPT = '';
		if($snsvar['SNS_NAZ_CUSTOMJS']) {
			$SNS_NAZ_SCRIPT .= '<script>';
			$SNS_NAZ_SCRIPT .= $snsvar['SNS_NAZ_CUSTOMJS'];
			$SNS_NAZ_SCRIPT .= '</script>';
		}
	
		$snsvar['SNS_NAZ_STYLE'] = $SNS_NAZ_STYLE;
		$snsvar['SNS_NAZ_SCRIPT'] = $SNS_NAZ_SCRIPT;
		$snsvar['WISHLIST_LINK'] = $this->context->link->getModuleLink('blockwishlist', 'mywishlist');
		$snsvar['ORDER_PROCESS'] = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order';

		$smarty->assign(array(
			'THEME_INFO' => $this->name . ' - ' . $this->version
		));
		$smarty->assign( $snsvar );

		// compile scss
		$scssDir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/sass/';
		$cssDir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/css/';
		
		if($snsvar['SNS_NAZ_THEMECOLORRAND']) {
			$themeColor = ($this->randColor($snsvar['SNS_NAZ_THEMECOLORRANDIN'])) ? $this->randColor($snsvar['SNS_NAZ_THEMECOLORRANDIN']) : $snsvar['SNS_NAZ_THEMECOLOR'];
		} else {
			$themeColor = $snsvar['SNS_NAZ_THEMECOLOR'];
		}
		$themeColor = strtolower($themeColor);
		$themeCssName = 'theme-' . str_replace("#", "", $themeColor) . '.css';
		if(($snsvar['SNS_NAZ_SCSSCOMPILE'] == 2 && !file_exists($cssDir . $themeCssName)) || $snsvar['SNS_NAZ_SCSSCOMPILE'] == 1) {
			require "scssphp/scss.inc.php";
			require "scssphp/compass/compass.inc.php";
			
			$scss = new scssc();
			new scss_compass($scss);
			
			if($snsvar['SNS_NAZ_SCSSFORMAT']) $cssFormat = $snsvar['SNS_NAZ_SCSSFORMAT'];
			else $cssFormat = 'scss_formatter_compressed';
			
			$scss->setFormatter($cssFormat);
			$scss->addImportPath($scssDir);
			
			$variables = '$color1: '.$themeColor.';';
		
			$string_sass = $variables . file_get_contents($scssDir . "theme.scss");
			$string_css = $scss->compile($string_sass);
			$string_css = preg_replace('/\/\*[\s\S]*?\*\//', '', $string_css); // remove mutiple comments
			file_put_contents($cssDir . $themeCssName, $string_css);
		}

		// end compile scss
		$this->context->controller->addCSS(array(
			__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/'.$themeCssName
		));
		$this->context->controller->removeCSS(array(
			__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/modules/blockcategories/blockcategories.css',
			__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/modules/blocklayered/blocklayered.css',
		));
		$jsDefArr = array();
		$jsDefArr['KEEP_MENU'] = (bool)$snsvar['SNS_NAZ_STICKYMENU'];
		$jsDefArr['SNS_TOOLTIP'] = (bool)$snsvar['SNS_NAZ_SHOWTOOLTIP'];
		Media::addJsDef($jsDefArr);
		
		// theme js
		
		$this->context->controller->addJS(__PS_BASE_URI__.'themes/'._THEME_NAME_.'/js/plg/countdown/jquery.plugin.min.js', 'all');
		$this->context->controller->addJS(__PS_BASE_URI__.'themes/'._THEME_NAME_.'/js/plg/countdown/jquery.countdown.js', 'all');
		
	}
	public function hookAddProduct($params)
	{
		$this->_clearPrdCache('*');
	}
	public function hookUpdateProduct($params)
	{
		$this->_clearPrdCache('*');
	}
	public function hookDeleteProduct($params)
	{
		$this->_clearPrdCache('*');
	}
	public function hookActionOrderStatusPostUpdate($params)
	{
		$this->_clearPrdCache('*');
	}
	public function clearCacheCss () {
		$cssDir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/css/';
		$cssCacheDir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/cache/';
	    $this->dellCss($cssDir);
	    $this->dellCss($cssCacheDir, true);
	}
	public function dellCss ($directory, $delall = false) {
		$minute = 60;
	    if ($handle = opendir($directory)) {
	        while (false !== ($file = readdir($handle))) {
	            if ($file != '.' && $file != '..') {
            		if($delall) {
            			if(preg_match("/css$/i", $file) || preg_match("/js$/i", $file)) {
						    $filePath = $directory.$file;
						    $time_elapsed = (time() - filemtime($filePath)) / 60;
							if($time_elapsed > $minute){
								unlink($filePath);
							}
						}
            		} elseif (preg_match("/css$/i", $file) && preg_match("/^theme-/i", $file)) {
					    $filePath = $directory.$file;
					    $time_elapsed = (time() - filemtime($filePath)) / 60;
						if($time_elapsed > $minute){
							unlink($filePath);
						}
					}
	            }
	        }
	        closedir($handle);
	    }
	}
	public function _clearPrdCache($template, $cache_id = NULL, $compile_id = NULL)
	{
		parent::_clearCache('footerproduct.tpl');
		parent::_clearCache('innerleftproduct.tpl');
		parent::_clearCache('displaySecondImage.tpl');
	}
}
?>