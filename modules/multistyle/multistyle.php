<?php
/*
*  @author OvicSoft <reply.ovic@gmail.com>
*  @copyright  2014 OvicSoft
*/
if (!defined('_PS_VERSION_'))
	exit;
class MultiStyle extends Module
{
	protected static $cache_products;

	public function __construct()
	{
		$this->name = 'multistyle';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'OvicSoft';
		$this->need_instance = 0;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Supershop - Multi font and multi color');
		$this->description = $this->l('Use google font and custom color.');
	}

	public function install()
	{
        $defaultfont  = htmlentities("<link href='http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>");
        if (is_string($defaultfont) === true)
        	$defaultfont = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($defaultfont)));
        $defaultfont = !is_string($defaultfont)? $defaultfont : stripslashes($defaultfont);
        Configuration::updateValue('FONT_LINK', $defaultfont);
        Configuration::updateValue('MAIN_COLOR','#ff9933');
        Configuration::updateValue('OPTION1_SECOND_COLOR','#e62e04');
        Configuration::updateValue('OPTION2_SECOND_COLOR','#283442');
        Configuration::updateValue('OPTION3_SECOND_COLOR','#e62e04');
        Configuration::updateValue('OPTION4_SECOND_COLOR','#2C5987');
        Configuration::updateValue('OPTION5_SECOND_COLOR','#94c67b');
        Configuration::updateValue('LINK_COLOR','#666');
        Configuration::updateValue('LINK_HOVER_COLOR','#e62e04');
        Configuration::updateValue('BTN_COLOR','#666');
        Configuration::updateValue('BTN_HOVER_COLOR','#ff9933');
        Configuration::updateValue('BTN_TEXT_COLOR','#FFFFFF');
        Configuration::updateValue('BTN_TEXT_HOVER_COLOR','#FFFFFF');
        //Configuration::updateValue('BLOCK_COLOR','#333');
//        Configuration::updateValue('BLOCK_TEXT_COLOR','#fff');
//        Configuration::updateValue('CATEGORY_COLOR','#666');
//        Configuration::updateValue('CATEGORY_HOVER_COLOR','#767676');
		if (!parent::install()
            || !$this->registerHook('displayHeader')
		)
			return false;
		return true;
	}

	public function uninstall()
	{
	    Configuration::deleteByName('FONT_LINK');
        Configuration::deleteByName('MAIN_COLOR');
        Configuration::deleteByName('OPTION1_SECOND_COLOR');
        Configuration::deleteByName('OPTION2_SECOND_COLOR');
        Configuration::deleteByName('OPTION3_SECOND_COLOR');
        Configuration::deleteByName('OPTION4_SECOND_COLOR');
        Configuration::deleteByName('OPTION5_SECOND_COLOR');
        Configuration::deleteByName('LINK_COLOR');
        Configuration::deleteByName('LINK_HOVER_COLOR');
        Configuration::deleteByName('BTN_COLOR');
        Configuration::deleteByName('BTN_HOVER_COLOR');
        Configuration::deleteByName('BTN_TEXT_COLOR');
        Configuration::deleteByName('BTN_TEXT_HOVER_COLOR');
        //Configuration::deleteByName('BLOCK_COLOR');
        //Configuration::deleteByName('BLOCK_TEXT_COLOR');
        //Configuration::deleteByName('CATEGORY_COLOR');
        //Configuration::deleteByName('CATEGORY_HOVER_COLOR');
		return parent::uninstall();
	}

    public function getContent()
	{
		$output = '';
		$errors = array();
		if (Tools::isSubmit('submitAxanMultiSave'))
		{
			Configuration::updateValue('FONT_LINK', $this->getHtmlValue('FONT_LINK'));
            Configuration::updateValue('MAIN_COLOR', Tools::getValue("MAIN_COLOR"));
            Configuration::updateValue('OPTION1_SECOND_COLOR', Tools::getValue("OPTION1_SECOND_COLOR"));
            Configuration::updateValue('OPTION2_SECOND_COLOR', Tools::getValue("OPTION2_SECOND_COLOR"));
            Configuration::updateValue('OPTION3_SECOND_COLOR', Tools::getValue("OPTION3_SECOND_COLOR"));
            Configuration::updateValue('OPTION4_SECOND_COLOR', Tools::getValue("OPTION4_SECOND_COLOR"));
            Configuration::updateValue('OPTION5_SECOND_COLOR', Tools::getValue("OPTION5_SECOND_COLOR"));
            Configuration::updateValue('LINK_COLOR', Tools::getValue("LINK_COLOR"));
            Configuration::updateValue('LINK_HOVER_COLOR', Tools::getValue("LINK_HOVER_COLOR"));
            Configuration::updateValue('BTN_COLOR', Tools::getValue("BTN_COLOR"));
            Configuration::updateValue('BTN_HOVER_COLOR', Tools::getValue("BTN_HOVER_COLOR"));
            Configuration::updateValue('BTN_TEXT_COLOR', Tools::getValue("BTN_TEXT_COLOR"));
            Configuration::updateValue('BTN_TEXT_HOVER_COLOR', Tools::getValue("BTN_TEXT_HOVER_COLOR"));
            //Configuration::updateValue('BLOCK_COLOR', Tools::getValue("BLOCK_COLOR"));
            //Configuration::updateValue('BLOCK_TEXT_COLOR', Tools::getValue("BLOCK_TEXT_COLOR"));
            //Configuration::updateValue('CATEGORY_COLOR', Tools::getValue("CATEGORY_COLOR"));
            //Configuration::updateValue('CATEGORY_HOVER_COLOR', Tools::getValue("CATEGORY_HOVER_COLOR"));

            $output .= $this->displayConfirmation($this->l('Your settings have been updated.'));
		}
		return $output.$this->renderForm();
	}

    private function getHtmlValue($key, $default_value = false)
	{
		if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));
        $ret = htmlentities($ret);
		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : stripslashes($ret);
	}

    public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Google font link'),
						'name' => 'FONT_LINK',
                        'desc' => $this->l("Example: <link href='http://fonts.useso.com/css?family=Gilda+Display' rel='stylesheet' type='text/css'>"),
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Main color'),
						'name' => 'MAIN_COLOR',
                        'size' => 30,

					),
                    array(
						'type' => 'color',
						'label' => $this->l('Link color'),
						'name' => 'LINK_COLOR',
                        'size' => 30,

					),
                    array(
						'type' => 'color',
						'label' => $this->l('Link hover color'),
						'name' => 'LINK_HOVER_COLOR',
						'size' => 30,
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Button background'),
						'name' => 'BTN_COLOR',
                        'size' => 30,

					),
                    array(
						'type' => 'color',
						'label' => $this->l('Button background Hover'),
						'name' => 'BTN_HOVER_COLOR',
						'size' => 30,
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Button text color'),
						'name' => 'BTN_TEXT_COLOR',
                        'size' => 30,

					),
                    array(
						'type' => 'color',
						'label' => $this->l('Button Text Hover'),
						'name' => 'BTN_TEXT_HOVER_COLOR',
						'size' => 30,
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Second color - option1'),
						'name' => 'OPTION1_SECOND_COLOR',
						'size' => 30,
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Second color - option2'),
						'name' => 'OPTION2_SECOND_COLOR',
						'size' => 30,
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Second color - option3'),
						'name' => 'OPTION3_SECOND_COLOR',
						'size' => 30,
					),
                    array(
						'type' => 'color',
						'label' => $this->l('Second color - option4'),
						'name' => 'OPTION4_SECOND_COLOR',
						'size' => 30,
					),
                     array(
						'type' => 'color',
						'label' => $this->l('Second color - option5'),
						'name' => 'OPTION5_SECOND_COLOR',
						'size' => 30,
					),
                    //array(
//						'type' => 'color',
//						'label' => $this->l('Block title background'),
//						'name' => 'BLOCK_COLOR',
//                        'size' => 30,
//
//					),
//                    array(
//						'type' => 'color',
//						'label' => $this->l('Block title color'),
//						'name' => 'BLOCK_TEXT_COLOR',
//						'size' => 30,
//					),
//                    array(
//						'type' => 'color',
//						'label' => $this->l('Category background'),
//						'name' => 'CATEGORY_COLOR',
//                        'size' => 30,
//
//					),
//                    array(
//						'type' => 'color',
//						'label' => $this->l('Category background hover'),
//						'name' => 'CATEGORY_HOVER_COLOR',
//						'size' => 30,
//					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int)Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitAxanMultiSave';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array(
                'FONT_LINK'=> html_entity_decode(Tools::getValue('FONT_LINK',Configuration::get('FONT_LINK'))),
                'MAIN_COLOR'=> Tools::getValue('MAIN_COLOR',Configuration::get('MAIN_COLOR')),
                'OPTION1_SECOND_COLOR'=> Tools::getValue('OPTION1_SECOND_COLOR',Configuration::get('OPTION1_SECOND_COLOR')),
                'OPTION2_SECOND_COLOR'=> Tools::getValue('OPTION2_SECOND_COLOR',Configuration::get('OPTION2_SECOND_COLOR')),
                'OPTION3_SECOND_COLOR'=> Tools::getValue('OPTION3_SECOND_COLOR',Configuration::get('OPTION3_SECOND_COLOR')),
                'OPTION4_SECOND_COLOR'=> Tools::getValue('OPTION4_SECOND_COLOR',Configuration::get('OPTION4_SECOND_COLOR')),
                'OPTION5_SECOND_COLOR'=> Tools::getValue('OPTION5_SECOND_COLOR',Configuration::get('OPTION5_SECOND_COLOR')),
                'LINK_COLOR'=> Tools::getValue('LINK_COLOR',Configuration::get('LINK_COLOR')),
                'LINK_HOVER_COLOR'=> Tools::getValue('LINK_HOVER_COLOR',Configuration::get('LINK_HOVER_COLOR')),
                'BTN_COLOR'=> Tools::getValue('BTN_COLOR',Configuration::get('BTN_COLOR')),
                'BTN_HOVER_COLOR'=> Tools::getValue('BTN_HOVER_COLOR',Configuration::get('BTN_HOVER_COLOR')),
                'BTN_TEXT_COLOR'=> Tools::getValue('BTN_TEXT_COLOR',Configuration::get('BTN_TEXT_COLOR')),
                'BTN_TEXT_HOVER_COLOR'=> Tools::getValue('BTN_TEXT_HOVER_COLOR',Configuration::get('BTN_TEXT_HOVER_COLOR')),
                //'BLOCK_COLOR'=> Tools::getValue('BLOCK_COLOR',Configuration::get('BLOCK_COLOR')),
                //'BLOCK_TEXT_COLOR'=> Tools::getValue('BLOCK_TEXT_COLOR',Configuration::get('BLOCK_TEXT_COLOR')),
                //'CATEGORY_COLOR'=> Tools::getValue('CATEGORY_COLOR',Configuration::get('CATEGORY_COLOR')),
                //'CATEGORY_HOVER_COLOR'=> Tools::getValue('CATEGORY_HOVER_COLOR',Configuration::get('CATEGORY_HOVER_COLOR')),
            ),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
    private function hex2rgba($hex) {
       $hex = str_replace("#", "", $hex);
       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgba = 'rgba('.$r.','.$g.','.$b.',0.8)';
       return $rgba;
    }

    public function hookDisplayHeader($params)
	{
	   $linkfont =  Configuration::get('FONT_LINK');
       $start = strpos($linkfont,'family');
       $linkfont = substr_replace($linkfont,'',0,$start+7);
       $start = strpos($linkfont,"'");
       $linkfont = substr_replace($linkfont,'',$start,strlen($linkfont));
       if (strpos($linkfont,":")>0){
            $start = strpos($linkfont,":");
            $linkfont = substr_replace($linkfont,'',$start,strlen($linkfont));
       }
       $font_name = str_replace('+',' ',$linkfont);
       $linkfont =  Configuration::get('FONT_LINK');
       $start = strpos($linkfont,'http');
       $substr = substr_replace($linkfont,'',$start,strlen($linkfont)-$start);
       $start = strpos($linkfont,'://');
       $linkfont = substr_replace($linkfont,'',0,$start);
       $linkfont = $substr.(empty( $_SERVER['HTTPS'] ) ? 'http' : 'https') .$linkfont;
       $maincolor = Configuration::get('MAIN_COLOR');
       $option1Secondcolor = Configuration::get('OPTION1_SECOND_COLOR');
       $option2Secondcolor = Configuration::get('OPTION2_SECOND_COLOR');
       $option3Secondcolor = Configuration::get('OPTION3_SECOND_COLOR');
       $option4Secondcolor = Configuration::get('OPTION4_SECOND_COLOR');
       $option5Secondcolor = Configuration::get('OPTION5_SECOND_COLOR');
       $linkcolor = Configuration::get('LINK_COLOR');
       $linkHovercolor = Configuration::get('LINK_HOVER_COLOR');
       $btncolor = Configuration::get('BTN_COLOR');
       
       $btnHovercolor = Configuration::get('BTN_HOVER_COLOR');
       $btntextcolor = Configuration::get('BTN_TEXT_COLOR');
       $btntextHovercolor = Configuration::get('BTN_TEXT_HOVER_COLOR');
       //$blocktitlebg = Configuration::get('BLOCK_COLOR');
       //$blocktitletext = Configuration::get('BLOCK_TEXT_COLOR');
       //$categorycolor = Configuration::get('CATEGORY_COLOR');
       //$categoryhovercolor = Configuration::get('CATEGORY_HOVER_COLOR');
       $grbacolor = $this->hex2rgba($maincolor);
       $this->context->smarty->assign(array(
			'linkfont' => $linkfont,
			'fontname' => $font_name,
			'maincolor' => $maincolor,
            'option1Secondcolor' => $option1Secondcolor,
            'option2Secondcolor' => $option2Secondcolor,
            'option3Secondcolor' => $option3Secondcolor,
            'option4Secondcolor' => $option4Secondcolor,
            'option5Secondcolor' => $option5Secondcolor,
            'linkcolor' => $linkcolor,
            'linkHovercolor' => $linkHovercolor,
            'btncolor' => $btncolor,
            'btnHovercolor' => $btnHovercolor,
            'btntextcolor' => $btntextcolor,
            'btntextHovercolor' => $btntextHovercolor,
            'grbacolor' => $grbacolor
            //'blocktitlebg' => $blocktitlebg,
            //'blocktitletext' => $blocktitletext,
           // 'categorycolor' => $categorycolor,
            //'categoryhovercolor' => $categoryhovercolor
	      	));
       return $this->display(__FILE__, 'multistyle.tpl');
	}
 }