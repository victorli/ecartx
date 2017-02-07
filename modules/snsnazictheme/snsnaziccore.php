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

include_once(dirname(__FILE__) . '/snsnazicclass.php');
include_once(dirname(__FILE__) . '/snsnazicproduct.php');

class SNSNazicCore extends Module {
	var $urlConfig = '';
	var $xmlConfig = array();
	
    public $themeFields;
    public $SNSClass;

	public function __construct() {
		parent::__construct();
		$this->SNSClass = new SNSNazicClass;
		$this->themeFields = $this->SNSClass->getThemeFields();
		$this->addModMedia();
		$this->SNSClass->delField();
		
		global $currentIndex;
		$this->secure_key = Tools::encrypt($this->name);
		$this->urlConfig = $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getValue('token');
		$this->xmlConfig = $this->getXMLConfig();
	}
	protected function installFixtures() {
		$languages = Language::getLanguages(false);
  		foreach($this->themeFields as $key => $value) {
  			if(!$value['lang']) $this->updateValue($key, $value['default'], true);
		}
		foreach ($languages as $lang) $this->installFixture((int)$lang['id_lang']);
		return true;
	}
	
	protected function installFixture($id_lang) {
		foreach($this->themeFields as $key => $value) {
			if(is_array($value) && $value['lang']) {
				$values[$key][(int)$id_lang] = $value['default'];
				$this->updateValue($key, $values[$key], true);
			}
		}
	}
	protected function addModMedia() {
		$this->context->controller->addJquery();	
		$this->context->controller->addJqueryUI('ui.sortable');
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/js/jquery.cookie.js');
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/js/editarea/edit_area_full.js');
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/iconpicker/js/iconset-fontawesome.min.js');
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/iconpicker/js/bootstrap-iconpicker.min.js');
		$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/iconpicker/css/bootstrap-iconpicker.min.css', 'all');
		$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/iconpicker/css/font-awesome.min.css', 'all');
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/assets/admin/js/sns-scripts.js');
	}

	public function getXMLConfig() {
		if( !file_exists( _PS_ALL_THEMES_DIR_.Context::getContext()->shop->getTheme().'/config.xml') ){
			return ;
		}
		$xmlc = simplexml_load_file( _PS_ALL_THEMES_DIR_.Context::getContext()->shop->getTheme().'/config.xml' );
		if( !$xmlc ){
			return null;
		}
		$cfg = array("font-sizes"=>"", "font-familys"=>"", "googlefonts"=>"");

		if( isset($xmlc->fontsizes) ){
			$temp =  (array)$xmlc->fontsizes; 
			$cfg["fontsizes"] = $temp["fontsize"];
		}
		if( isset($xmlc->fontfamilys) ){
			$temp =  (array)$xmlc->fontfamilys; 
			$cfg["fontfamilys"] = $temp["fontfamily"];
		}
		if( isset($xmlc->googlefonts) ){
			$temp =  (array)$xmlc->googlefonts; 
			$cfg["googlefonts"] = $temp["googlefont"];
		}
		return $cfg;
	}
	public function getPatterns( $field = 'SNS_NAZ_BODYIMG', $valcpl = '' ){
		$patterns = array();
		$path = _PS_ALL_THEMES_DIR_. _THEME_NAME_."/img/patterns";
		$regex = '/(\.gif)|(.jpg)|(.png)|(.bmp)$/i';
		if( !is_dir($path) ) return;
		
		$dk =  opendir ( $path );
		$files = array();
		while ( false !== ($filename = readdir ( $dk )) ) {
			if (preg_match ( $regex, $filename )) {
				$files[] = $filename;
			}
		}
		foreach( $files as $p ) $patterns[] = $p;
		
		return $patterns;
	}
	public function getBaseUrl(){
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']))
			return 'https://'.Tools::getShopDomain();
		else
			return 'http://'.Tools::getShopDomain();
	}
	protected function getPatternsHTML($front=false, $curr=false) {
		$html = '<div class="radio_img_group">';
		$patternsURL = $this->getBaseUrl().__PS_BASE_URI__."themes/"._THEME_NAME_."/img/patterns/";
		
		if($front) $inputName = 'SNS_NAZCP_BODYIMG';
		else $inputName = 'SNS_NAZ_BODYIMG';
		
		if(is_array($this->getPatterns())) {
			if($front && $curr) 
				$curent = $curr;
			else
				$curent = Tools::getValue('SNS_NAZ_BODYIMG', Configuration::get('SNS_NAZ_BODYIMG'));
			
			foreach ($this->getPatterns() as $val) {
				$checked = '';
				if($curent == $val) $checked = ' checked="checked"';
				$html .= '<label title="'.$val.'" style="display: inline-block;">';
				$html .= '<input'.$checked.' type="radio" class="skip_uniform" value="'.$val.'" name="'.$inputName.'">';
				$html .= '<span style="background-image: url('.$patternsURL.$val.')"></span>';
				$html .= '</label>';
			}
		}
		$html .= '</div>';
		
		return $html;
	}
	public function snsSerialize ($serializ) {
		if(!is_array($serializ)) return;
		foreach($serializ as $key => $row) {
			if(count($row)) {
				$remove = true;
				foreach($row as $fieldk => $field) {
					$remove &= ((bool)$field) ? false : true;
					if($field) {
						$serializ[$key][$fieldk] = $this->SNSClass->replaceLinkContent($field);
					}
				}
				if($remove) unset($serializ[$key]);
			} else {
				unset($serializ[$key]);
			}
		}
		return serialize($serializ);
	}
	public function snsUnSerialize ($serialized) {
		$fields = Tools::unSerialize($serialized);
		
		if($fields) {
			foreach($fields as $id => $field) {
				foreach($field as $name => $v) {
					$fields[$id][$name] = $this->SNSClass->replaceLinkContent($v, true);
				}
			}
			return $fields;
		}
		return false;
	}
	public function updateFields($section = false) {
		$fields = $this->SNSClass->getThemeFields($section);
		
		$languages = Language::getLanguages(false);
    	$submitValues = array();
    	$additem_val = array();
    	
		foreach ($languages as $lang) {
	  		foreach($fields as $key => $value) {
	  			if($value['lang'] && $value['type'] == false) {
					$submitValues[$key][$lang['id_lang']] = $this->SNSClass->replaceLinkContent(Tools::getValue($key.'_'.$lang['id_lang']));
	  			}
	  			if($value['type'] == 'additem'){
					$additem_val[$key][$lang['id_lang']] = $this->snsSerialize(Tools::getValue($key.'_'.$lang['id_lang']));
	  			}
			}
		}
  		foreach($fields as $key => $value) {
  			if($value['lang'] && $value['type'] == false) {
				$this->updateValue($key, $submitValues[$key], true);
  			} elseif($value['type'] == 'additem'){
				$this->updateValue($key, $additem_val[$key], true);
  			} elseif($value['type'] == 'multiple_select'){
				$multiple = Tools::getValue ($key);
				$field_select = ( is_array($multiple) && !empty( $multiple ) ) ? implode (',', $multiple) : '';
				$this->updateValue($key, $field_select, true);
  			} else {
				$this->updateValue($key, $this->SNSClass->replaceLinkContent(Tools::getValue($key)), true);
  			}
		}
		$this->_snsClearCache();
		if($section = false || $section = 'snsp_general')
			$this->updateValue('PS_QUICK_VIEW', Tools::getValue('PS_QUICK_VIEW'), true);
		
		return true;
	}
	public function getContent() {
	    $output = null;
	    if (Tools::isSubmit('submit'.$this->name)) {  
	    	$this->updateFields();
    	    $output .= $this->displayConfirmation($this->l('Settings updated'));
	    }
	    $output .= $this->getFormHTML();
	    return $output;
	}
	public function getConfigFieldsValues() {
		
		$values = array();
		$languages = Language::getLanguages(false);
		
  		foreach($this->themeFields as $key => $value) {
  			if(!$value['lang']) {
  				if($value['type'] == 'multiple_select'){
					$kv = Configuration::get($key);
					$values[$key.'[]'] = ( isset($kv) && $kv !== false ) ? explode(',', $kv) : false;
  				} else {
					$values[$key] = $this->SNSClass->replaceLinkContent(Configuration::get($key), true);
  				}
  			}
		}
		foreach ($languages as $lang) {
	  		foreach($this->themeFields as $key => $value) {
	  			if($value['lang'] && $value['type'] == false) {
					$values[$key][$lang['id_lang']] = $this->SNSClass->replaceLinkContent(Configuration::get($key, $lang['id_lang']), true);
	  			}
			}
		}
		
		$values['PS_QUICK_VIEW'] = Configuration::get('PS_QUICK_VIEW');
		
		return $values;
	}

	protected function getAdditemData($field) {
		$values = array();
		$languages = Language::getLanguages(false);
		foreach ($languages as $lang) {
			$id_lang = $lang['id_lang'];
			$arrayValue = $this->snsUnSerialize(Configuration::get($field, $id_lang));
			if($arrayValue) {
				$values[$id_lang] = $arrayValue;
			}
		}
		return $values;
	}

	protected function getFormHTML() {
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'snstabs' => $this->getTabs(),
			'patterns_html' => $this->getPatternsHTML(),
			'controller_url' => $this->context->link->getAdminLink('AdminSNSNazicTheme'),
			'theme_info' => $this->displayName . ' v' . $this->version
		);

		return $helper->generateForm(array(
			'snsp_general' => $this->generalSettings('snsp_general'),
			'snsp_header' => $this->headerSettings('snsp_header'),
			'snsp_slideshow' => $this->slideshowSettings('snsp_slideshow'),
			'snsp_footer' => $this->footerSettings('snsp_footer'),
			'snsp_prdpage' => $this->prdPageSettings('snsp_prdpage'),
			'snsp_bannerhome' => $this->bannerhomeSettings('snsp_bannerhome'),
			'snsp_staticblock' => $this->StaticblockSettings('snsp_staticblock'),
			'snsp_contact' => $this->contactSettings('snsp_contact'),
			'snsp_ourbrand' => $this->ourbrandSettings('snsp_ourbrand'),
		//	'snsp_catslide' => $this->catslideSettings('snsp_catslide'),
			'snsp_advance' => $this->advanceSettings('snsp_advance'),
			'snsp_customcssjs' => $this->customCssJsSetting('snsp_customcssjs')
		));
	}
    protected function getTabs() {
        $tabArray = array(
            'General' => 'fieldset_snsp_general',
            'Header' => 'fieldset_snsp_header',
            'Slideshow' => 'fieldset_snsp_slideshow',
            'Footer' => 'fieldset_snsp_footer',
            'Product Page' => 'fieldset_snsp_prdpage',
            'Banner Home' => 'fieldset_snsp_bannerhome', 
            'Static Block'  => 'fieldset_snsp_staticblock',
            'Contact' => 'fieldset_snsp_contact',

            'Our Brand' => 'fieldset_snsp_ourbrand',
        //    'Catslider' => 'fieldset_snsp_catslide',
            'Advance' => 'fieldset_snsp_advance',
            'Custom CSS, JS' => 'fieldset_snsp_customcssjs'
        );
        return $tabArray;
    }
	protected function getListInXML($node) {
		$list = array();
		if(is_array($this->xmlConfig[$node])) {
			foreach ($this->xmlConfig[$node] as $key => $val) {
				if($node == 'googlefonts') {
					$gfontName = explode('|', $val);
					$option = array('id' => $val, 'name' => $gfontName[1]);
				} else {
					$option = array('id' => $val, 'name' => $val);
				}
				$list[] = $option;
			}
		}
		return $list;
	}
	protected function generalSettings($tab_name) {
		$fields_form = array(
			array(
				'type' => 'select',
				'label' => $this->l('Home page'),
				'name' => 'SNS_NAZ_HOMEPAGE',
				'options' => array(
					'query' => array(
						array('id' => '1', 'name' => 'default')	
					),
					'id' => 'id',
					'name' => 'name'
				),
			),
			array(
                'type' => 'color',
                'label' => $this->l('Theme Color'),
                'name' => 'SNS_NAZ_THEMECOLOR'
			),
			$this->field_onOff('SNS_NAZ_THEMECOLORRAND', 'Random theme color'),
			$this->field_text ('SNS_NAZ_THEMECOLORRANDIN', 'Random theme color in', 'fixed-width-xl', false, 'Hex colors. Separated by comma(,). Eg: #58dd90,#0095b8,#d4006f'),
			array(
				'type' => 'select',
                'label' => $this->l('Layout type'),
				'name' => 'SNS_NAZ_LAYOUTTYPE',
				'options' => array(
					'query' => array(
						array('id' => 1, 'name' => 'Full Width'),
						array('id' => 2, 'name' => 'Boxed')
					),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
				'type' => 'select',
                'label' => $this->l('Font familys'),
				'name' => 'SNS_NAZ_FONTFAMILY',
				'options' => array(
					'query' => $this->getListInXML('fontfamilys'),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
				'type' => 'select',
                'label' => $this->l('Font size'),
				'name' => 'SNS_NAZ_FONTSIZE',
				'options' => array(
					'query' => $this->getListInXML('fontsizes'),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
                'type' => 'color',
                'label' => $this->l('Body bg color'),
                'name' => 'SNS_NAZ_BODYCOLOR'
            ),


            $this->field_onOff('SNS_NAZ_CUSTOMBG', 'Use custom background'),

			array(
                'type' => 'sns_image',
                'label' => $this->l('Use new background img'),
                'hint'	=> $this->l('Only use for boxed layout'),
                'name' => 'SNS_NAZ_BGBODY',
            	'lang' => true

            ),

			array(
                'type' => 'pattern_choice',
                'label' => $this->l('Body bg img'),
                'name' => 'SNS_NAZ_BODYIMG'
            ),
			array(
                'type' => 'select',
                'label' => $this->l('Google font'),
                'name' => 'SNS_NAZ_GOOGLEFONT',
                'options' => array(
					'query' => $this->getListInXML('googlefonts'),
					'id' => 'id',
					'name' => 'name'
				)
            ),
			$this->field_textarea ('SNS_NAZ_GOOGLETARGETS', 'Google font targets'),
			$this->field_onOff('SNS_NAZ_GALLERYIMG', 'Display gallery image'),
			$this->field_onOff('PS_QUICK_VIEW', 'Quick View'),
        );
		return $this->getFormSection($fields_form, 'General settings', $tab_name);
	}
	protected function headerSettings($tab_name) {
		$fields_form = array(
			$this->field_text ('SNS_NAZ_WELCOMEMESS', 'Welcome messenger', '', true),


			$this->field_onOff('SNS_NAZ_CUSTOMLOGO', 'Use custom logo'),
			array(
                'type' => 'sns_image',
                'label' => $this->l('Custom logo'),
                'name' => 'SNS_NAZ_CUSTOMLOGO_URL',
            	'lang' => true
            ),


			$this->field_onOff('SNS_NAZ_STICKYMENU', 'Use sticky menu'),
			
        );
		return $this->getFormSection($fields_form, 'Header Settings', $tab_name);
	}
	public function getAnimate(){
		$transitions = array(
			'Attention Seekers' => array( 'bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble' ),
			'Bouncing Entrances' => array( 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp' ),
			'Bouncing Exits' => array( 'bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp' ),
			'Fading Entrances' => array( 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig' ),
			'Fading Exits' => array( 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig' ),
			'Flippers' => array( 'flip', 'flipInX', 'flipInY', 'flipOutX', 'flipOutY' ),
			'Lightspeed' => array( 'lightSpeedIn', 'lightSpeedOut' ),
			'Rotating Entrances' => array( 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight' ),
			'Rotating Exits' => array( 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight' ),
			'Sliding Entrances' => array( 'slideInUp', 'slideInDown', 'slideInLeft', 'slideInRight' ),
			'Sliding Exits' => array( 'slideOutUp', 'slideOutDown', 'slideOutLeft', 'slideOutRight' ),
			'Zoom Entrances' => array( 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp' ),
			'Zoom Exits' => array( 'zoomOut', 'zoomOutDown', 'zoomOutLeft', 'zoomOutRight', 'zoomOutUp' ),
			'Specials' => array( 'hinge', 'rollIn', 'rollOut' )
			);
		
		$query = array();
		foreach($transitions as $group => $animations) {
			$animates = array();
			foreach($animations as $animate)
				$animates[] = array('id' => $animate, 'name' => $animate);
			$query[] = array('name' => $group, 'query' => $animates);
		}
		return array(
					'optiongroup' => array(
						'label' => 'name',
						'query' => $query
					),
					'options' => array(
						'id' => 'id',
						'name' => 'name',
						'query' => 'query',
					),
				);
	}
	protected function slideshowSettings($tab_name) {
		$fields_form = array(
			$this->field_onOff('SNS_NAZ_SLSTATUS', 'Enabled'),
			array(
				'type' => 'text',
				'label' => $this->l('Auto Play'),
				'name' => 'SNS_NAZ_SLAUTO',
				'class' => 'fixed-width-xs',
				'suffix' => 'ms'
			),
			array(
				'type' => 'select',
                'label' => $this->l('Animate In'),
				'name' => 'SNS_NAZ_SLANIMATEIN',
				'options' => $this->getAnimate()
			),
			array(
				'type' => 'select',
                'label' => $this->l('Animate OUT'),
				'name' => 'SNS_NAZ_ANIMATEOUT',
				'options' => $this->getAnimate()
			),
            array(
                'label' => $this->l('Images'),
                'name' => 'SNS_NAZ_SLIMGS',
            	'lang' => true,
				'type' => 'additem',
				'btn' => $this->l('Add Image'),
				'data' => $this->getAdditemData('SNS_NAZ_SLIMGS'),
				'hint'	=> $this->l('Apply for slide products'),
				'fields' => array(
					array(
						'label' => $this->l('Title'),
						'name' => 'title',
						'type' => 'text',
						'width' => '2'
					),
					array(
						'label' => $this->l('Link'),
						'name' => 'link',
						'type' => 'text',
						'width' => '3'
					),
					array(
						'label' => $this->l('Image'),
						'name' => 'img',
						'type' => 'image',
						'width' => '4'
					),
				)
            )
        );
		return $this->getFormSection($fields_form, 'Slideshow Settings', $tab_name);
	}
	protected function bannerhomeSettings($tab_name) {
		$fields_form = array(
			$this->field_onOff('SNS_ALLOW_BANNER', 'Use banner for homepage'),
			array(
                'type' => 'sns_image',
                'label' => $this->l('Banner #1'),
                'hint'  => 'Banner for home page 1 colum',
                'name' => 'SNS_NAZ_BANNER_HOME_1',
            	'lang' => true
            ),
            array(
                'type' => 'sns_image',
                'label' => $this->l('Banner #2'),
                'hint'  => 'Banner for home page 1 colum',
                'name' => 'SNS_NAZ_BANNER_HOME_2',
            	'lang' => true
            ),
 
          
            $this->field_onOff('SNS_ALLOW_BANNER_LEFT', 'Use banner Left'),
            array(
                'type' => 'sns_image',
                'label' => $this->l('Banner colum left'),
                'hint'  => 'Banner for colum left for homepage',
                'name' => 'SNS_NAZ_BANNER_HOME_LEFT',
            	'lang' => true
            ),

            array(
                'type' => 'sns_image',
                'label' => $this->l('Banner colum left'),
                'hint'  => 'Banner for colum left for gird and list',
                'name' => 'SNS_NAZ_BANNER_GVL_LEFT',
            	'lang' => true
            ),
        );
		return $this->getFormSection($fields_form, 'Banner Home', $tab_name);
	}


	protected function staticblockSettings($tab_name) {
		$fields_form = array(
			$this->field_textarea ('SNS_NAZ_STATICBLOCK', 'Custom Block', '', true, true),
        );
		return $this->getFormSection($fields_form, 'Static Block', $tab_name);
	}




	protected function contactSettings($tab_name) {
		$fields_form = array(
			$this->field_onOff('SNS_NAZ_CONTACT_STATUS', 'Enabled'),
        //    $this->field_text ('SNS_NAZ_STORE_NAME', 'Store Name', '',  true),
            $this->field_text ('SNS_NAZ_STORE_ADDRESS', 'Store Address', '',  true),
        //    $this->field_textarea ('SNS_NAZ_STORE_INFO', 'Store Info', ' ',true,  true),
			$this->field_text ('SNS_NAZ_STORE_PHONE', 'Store Phone', '',  true),
		//	$this->field_text ('SNS_NAZ_STORE_FAX', 'Store Fax', '',  true),
			$this->field_text ('SNS_NAZ_STORE_EMAIL', 'Store Email', '',  true),
			$this->field_text ('SNS_NAZ_MAP_ZOOM', 'Map Zoom', '',  true),   


			 array(
                'label' => $this->l('Social'),
                'name' => 'SNS_NAZ_SOCIAL',
            	'lang' => true,
				'type' => 'additem',
				'iconpicker' => true,
				'btn' => $this->l('Add Item'),
				'data' => $this->getAdditemData('SNS_NAZ_SOCIAL'),
				'fields' => array(
					array(
						'label' => $this->l('Title'),
						'name' => 'title',
						'type' => 'text',
						'width' => '2'
					),
					array(
						'label' => $this->l('Link'),
						'name' => 'link',
						'type' => 'text',
						'width' => '3'
					),
					array(
						'label' => $this->l('Icon'),
						'name' => 'icon',
						'type' => 'text',
						'width' => '2'
					),
					
					array(
						'label' => $this->l('Target'),
						'name' => 'target',
						'type' => 'select',
						'width' => '1',
						'options' => array(
							'_self'		=> '_self',
							'_blank'	=> '_blank',
							'_parent'	=> '_parent',
							'_top'		=> '_top'
						)
					)
					
				)
            ),

		 	array(
	            'type' => 'sns_image',
	            'label' => $this->l('Payment logo'),
	            'name' => 'SNS_NAZ_PAYMENTLOGO',
	        	'lang' => true
           )


        );
		return $this->getFormSection($fields_form, 'Contact Settings', $tab_name);
	}



	protected function ourbrandSettings($tab_name) {
		$fields_form = array(
			$this->field_text ('SNS_NAZ_OURBRAND_TITLE', 'Title', 'fixed-width-xl', true),
			$this->field_onOff('SNS_NAZ_OURBRAND_STATUS', 'Enabled'),
            array(
                'label' => $this->l('Our brands'),
                'name' => 'SNS_NAZ_OURBRANDS',
            	'lang' => true,
				'type' => 'additem',
				'btn' => $this->l('Add Brand'),
				'data' => $this->getAdditemData('SNS_NAZ_OURBRANDS'),
				'fields' => array(
					array(
						'label' => $this->l('Title'),
						'name' => 'title',
						'type' => 'text',
						'width' => '2'
					),
					array(
						'label' => $this->l('Link'),
						'name' => 'link',
						'type' => 'text',
						'width' => '3'
					),
					array(
						'label' => $this->l('Target'),
						'name' => 'target',
						'type' => 'select',
						'width' => '1',
						'options' => array(
							'_self'		=> '_self',
							'_blank'	=> '_blank',
							'_parent'	=> '_parent',
							'_top'		=> '_top'
						)
					),
					array(
						'label' => $this->l('Logo'),
						'name' => 'logo',
						'type' => 'image',
						'width' => '4'
					),
				)
            )
        );
		return $this->getFormSection($fields_form, 'Our brands Settings', $tab_name);
	}
	
	protected function footerSettings($tab_name) {
		$fields_form = array(
			
			$this->field_textarea ('SNS_NAZ_FMIDDLE1', 'Footer middle col #1', '', true, true),
			$this->field_textarea ('SNS_NAZ_FMIDDLE2', 'Footer middle col #2', '', true, true),
			$this->field_textarea ('SNS_NAZ_FMIDDLE3', 'Footer middle col #3', '', true, true),
			$this->field_textarea ('SNS_NAZ_FMIDDLE4', 'Footer middle col #4', '', true, true),
			$this->field_textarea ('SNS_NAZ_FMIDDLE5', 'Footer middle col #5', '', true, true),


			
			$this->field_textarea ('SNS_NAZ_FMORELINKS', 'Links in bottom', '', true, true),
			$this->field_text ('SNS_NAZ_COPYRIGHT', 'Copyright', '', true),
           
        );
		return $this->getFormSection($fields_form, 'Footer Settings', $tab_name);
	}
	protected function prdPageSettings($tab_name) {
		$fields_form = array(
			$this->field_onOff('SNS_NAZ_ADDTHISBTN', 'Use sharing button of Addthis'),
			$this->field_onOff('SNS_NAZ_CUSTOMTAB', 'Custom tab'),
			$this->field_text ('SNS_NAZ_CUSTOMTAB_TITLE', 'Custom tab title', 'fixed-width-xl', true),
			$this->field_textarea ('SNS_NAZ_CUSTOMTAB_CONTENT', 'Custom tab content', '', true, true),

			$this->field_onOff('SNS_ALLOW_BANNER_LEFT_PRD', 'Use banner Left'),
      
            array(
                'type' => 'sns_image',
                'label' => $this->l('Banner colum left'),
                'hint'  => 'Banner for colum left',
                'name' => 'SNS_NAZ_BANNER_PRD_LEFT',
            	'lang' => true
            ),
           
        );
		return $this->getFormSection($fields_form, 'Product Page Settings', $tab_name);
	}
	protected function advanceSettings($tab_name) {
		$fields_form = array(
			array(
				'type' => 'select',
                'label' => $this->l('SCSS Compile'),
				'name' => 'SNS_NAZ_SCSSCOMPILE',
				'options' => array(
					'query' => array(
						array('id' => 1, 'name' => 'Alway compile'),
						array('id' => 2, 'name' => 'Only compile when don\'t have the css file')
					),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
				'type' => 'select',
                'label' => $this->l('CSS Format'),
				'name' => 'SNS_NAZ_SCSSFORMAT',
				'options' => array(
					'query' => array(
						array('id' => 'scss_formatter', 'name' => 'scss_formatter'),
						array('id' => 'scss_formatter_nested', 'name' => 'scss_formatter_nested'),
						array('id' => 'scss_formatter_compressed', 'name' => 'scss_formatter_compressed')
					),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
				'type' => 'btn_clearcss',
				'name' => 'btn_clearcss'
			),
			$this->field_onOff('SNS_NAZ_SHOWCPANEL', 'Show cpanel'),
			$this->field_onOff('SNS_NAZ_SHOWTOOLTIP', 'Show tool tip')
        );
		return $this->getFormSection($fields_form, 'Advance settings', $tab_name);
	}
	protected function customCssJsSetting($tab_name) {
		$fields_form = array(
			array(
                'type' => 'css_editor',
                'label' => $this->l('Custom Css'),
                'name' => 'SNS_NAZ_CUSTOMCSS',
			),
			array(
                'type' => 'js_editor',
                'label' => $this->l('Custom Js'),
                'name' => 'SNS_NAZ_CUSTOMJS',
			)
        );
		return $this->getFormSection($fields_form, 'Custom CSS, JS Setting', $tab_name);
	}
	protected function field_textarea ($name, $label, $class = '', $lang = false, $editor = false, $hint = '') {
		$field = array ();
		$field['type'] = 'textarea';
		$field['label'] = $this->l($label);
		$field['name'] = $name;
		if($class) $field['class'] = $class;
		if($lang) $field['lang'] = $lang;
		if($editor) $field['autoload_rte'] = $editor;
		if($hint) $field['hint'] = $this->l($hint);
		
		return $field;
	}
	protected function field_text ($name, $label, $class = '', $lang = false, $hint = '') {
		$field = array ();
		$field['type'] = 'text';
		$field['label'] = $this->l($label);
		$field['name'] = $name;
		if($class) $field['class'] = $class;
		if($lang) $field['lang'] = $lang;
		if($hint) $field['hint'] = $this->l($hint);
		
		return $field;
	}
	protected function field_onOff ($name, $label) {
		return array(
			'type' => 'switch',
			'label' => $this->l($label),
			'name' => $name,
			'values' => array(
				array(
					'id' => $name.'_ON',
					'value' => 1,
					'label' => $this->l('Enabled')
				),
				array(
					'id' => $name.'_OFF',
					'value' => 0,
					'label' => $this->l('Disabled')
				)
			)
		);
	}
	protected function getFormSection ($fields_form, $title, $tab_name, $icon = 'icon-cogs') {
		return array(
			'form' => array(
				'legend' => array(
					'title' => $title,
					'icon' => $icon
				),
				'input' => $fields_form,
				'buttons' => array(
					array(
						'title' => $this->l('Save'),
						'name' => $tab_name,
						'id' => $tab_name,
						'class' => 'btn btn-default pull-right btn-section-submit',
						'icon' => 'process-icon-save'
					)
				),
				'submit' => array(
					'title' => $this->l('Save All')
				)
			)
		);
	}
	public function randColor($colors = null, $min = 0, $max = 255) {
		if($colors != null) {
			$colorArr = explode(',', $colors);
			foreach($colorArr as $k => $color) {
				$color = str_replace(' ', '', trim($color));
				if(preg_match('/^#[a-f0-9]{6}$|^#[a-f0-9]{3}$/i', $color)) $colorArr[$k] = strtolower($color);
				else unset($colorArr[$k]);
			}
			if(count($colorArr)) {
				$rand_key = array_rand($colorArr, 1);
				return $colorArr[$rand_key];
			}
			
			$colorArr = explode(',', $colors);
			foreach($colorArr as $k => $color) {
				$color = str_replace(' ', '', trim($color));
				if(is_int($color)) $colorArr[$k] = $color;
				else unset($colorArr[$k]);
			}
			if(count($colorArr)) {
				if($colorArr[0] < $colorArr[1]) {
					$min = $colorArr[0];
					$max = $colorArr[1];
				} else {
					$min = $colorArr[1];
					$max = $colorArr[0];
				}
			}
		}
		$color = '#';
		$color .= str_pad( dechex( mt_rand( $min, $max ) ), 2, '0', STR_PAD_LEFT);
		$color .= str_pad( dechex( mt_rand( $min, $max ) ), 2, '0', STR_PAD_LEFT);
		$color .= str_pad( dechex( mt_rand( $min, $max ) ), 2, '0', STR_PAD_LEFT);
		return $color;
	}
    protected function _createTab() {
        $response = true;
        $parentTabID = Tab::getIdFromClassName('AdminSNS');
        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "AdminSNS";
            foreach (Language::getLanguages() as $lang){
                $parentTab->name[$lang['id_lang']] = "SNS Theme";
            }
            $parentTab->id_parent = 0;
            $parentTab->module = $this->name;
            $response &= $parentTab->add();
        }
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminSNSNazicTheme";
        $tab->name = array();
        foreach (Language::getLanguages() as $lang){
            $tab->name[$lang['id_lang']] = "SNS Nazic theme";
        }
        $tab->id_parent = $parentTab->id;
        $tab->module = $this->name;
        $response &= $tab->add();
        return $response;
    }
    protected function _deleteTab() {
        $id_tab = Tab::getIdFromClassName('AdminSNSNazicTheme');
        $parentTabID = Tab::getIdFromClassName('AdminSNS');
        $tab = new Tab($id_tab);
        $tab->delete();
        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }
        return true;
    }
	public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
	{
		if (!Validate::isConfigName($key))
			die(sprintf(Tools::displayError('[%s] is not a valid configuration key'), $key));
		if ($id_shop === null || !Shop::isFeatureActive())
			$id_shop = Shop::getContextShopID(true);
		if ($id_shop_group === null || !Shop::isFeatureActive())
			$id_shop_group = Shop::getContextShopGroupID(true);

		if (!is_array($values))
			$values = array($values);

//		if ($html)
//			foreach ($values as $lang => $value)
//				$values[$lang] = Tools::purifyHTML($value);
		$result = true;
		foreach ($values as $lang => $value)
		{
			$stored_value = Configuration::get($key, $lang, $id_shop_group, $id_shop);
			if ((!is_numeric($value) && $value === $stored_value) || (is_numeric($value) && $value == $stored_value && Configuration::hasKey($key, $lang)))
				continue;
			if (Configuration::hasKey($key, $lang, $id_shop_group, $id_shop))
			{
				if (!$lang)
				{
					$result &= Db::getInstance()->update('configuration', array(
						'value' => pSQL($value, $html),
						'date_upd' => date('Y-m-d H:i:s'),
					), '`name` = \''.pSQL($key).'\''.SNSNazicCore::sqlRestriction($id_shop_group, $id_shop), 1, true);
				}
				else
				{
					$sql = 'UPDATE `'._DB_PREFIX_.bqSQL('configuration').'_lang` cl
							SET cl.value = \''.pSQL($value, $html).'\',
								cl.date_upd = NOW()
							WHERE cl.id_lang = '.(int)$lang.'
								AND cl.`'.bqSQL('id_configuration').'` = (
									SELECT c.`'.bqSQL('id_configuration').'`
									FROM `'._DB_PREFIX_.bqSQL('configuration').'` c
									WHERE c.name = \''.pSQL($key).'\''
										.SNSNazicCore::sqlRestriction($id_shop_group, $id_shop)
								.')';
					$result &= Db::getInstance()->execute($sql);
				}
			}
			else
			{
				if (!$configID = Configuration::getIdByName($key, $id_shop_group, $id_shop))
				{
					$newConfig = new Configuration();
					$newConfig->name = $key;
					if ($id_shop)
						$newConfig->id_shop = (int)$id_shop;
					if ($id_shop_group)
						$newConfig->id_shop_group = (int)$id_shop_group;
					if (!$lang)
						$newConfig->value = $value;
					$result &= $newConfig->add(true, true);
					$configID = $newConfig->id;
				}

				if ($lang)
				{
					$result &= Db::getInstance()->insert('configuration_lang', array(
						'id_configuration' => $configID,
						'id_lang' => (int)$lang,
						'value' => pSQL($value, $html),
						'date_upd' => date('Y-m-d H:i:s'),
					));
				}
			}
		}
		Configuration::set($key, $values, $id_shop_group, $id_shop);
		return $result;
	}
	protected static function sqlRestriction($id_shop_group, $id_shop)
	{
		if ($id_shop)
			return ' AND id_shop = '.(int)$id_shop;
		elseif ($id_shop_group)
			return ' AND id_shop_group = '.(int)$id_shop_group.' AND (id_shop IS NULL OR id_shop = 0)';
		else
			return ' AND (id_shop_group IS NULL OR id_shop_group = 0) AND (id_shop IS NULL OR id_shop = 0)';
	}
	public function _snsClearCache($template, $cache_id = NULL, $compile_id = NULL)
	{
		//parent::_clearCache('displaySecondImage.tpl');
	}
}
?>