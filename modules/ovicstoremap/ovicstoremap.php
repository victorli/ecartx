<?php
/*
* 2014 Fashion
*
*  @author Fashion modules
*  @copyright  2014 Fashion modules
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_'))
	exit;

class OvicStoreMap extends Module
{
    public function __construct()
    {
        $this->name = 'ovicstoremap';
        $this->tab = 'front_office_features';
        $this->version = 1.0;
		$this->author = 'OvicSoft';
		$this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();

		$this->displayName = $this->l('Supershop - Store Map');
        $this->description = $this->l('Displays store location with google map.');
    }

	public function install()
	{
	   $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		return parent::install() &&
            Configuration::updateValue('STORE_CONTACT_INFO', array($id_lang_default => '<h3 class="page-subheading">Information</h3>
<p>Lorem ipsum dolor sit amet onsectetuer adipiscing elit. Mauris fermentum dictum magna. Sed laoreet aliquam leo. Ut tellus dolor dapibus eget. Mauris tincidunt aliquam lectus sed vestibulum. Vestibulum bibendum suscipit mattis.</p>
<ul>
<li>Praesent nec tincidunt turpis.</li>
<li>Aliquam et nisi risus.&nbsp;Cras ut varius ante.</li>
<li>Ut congue gravida dolor, vitae viverra dolor.</li>
</ul>
<br />
<ul class="store_info">
<li><i class="icon-home"></i>Our business address is 1063 Freelon Street San Francisco, CA 95108</li>
<li><i class="icon-tablet"></i><span>+ 021.343.7575</span></li>
<li><i class="icon-tablet"></i><span>+ 020.566.6666</span></li>
<li><i class="icon-envelope"></i>Email: <span><a href="mailto:%73%75%70%70%6f%72%74@%6b%75%74%65%74%68%65%6d%65.%63%6f%6d">support@kutetheme.com</a></span></li>
</ul>'),true) &&
			$this->registerHook('displayStoreMap') &&
            $this->registerHook('displayContactInfo') &&
			$this->registerHook('header');
    }

    public function getContent()
	{
		$html = '';
        $languages = Language::getLanguages();
        if (Tools::isSubmit('submitGlobal'))
		{
            $STORE_CONTACT_INFO = array();
            foreach ($languages as $lg){
                $STORE_CONTACT_INFO[$lg['id_lang']] = Tools::getValue('STORE_CONTACT_INFO_'.$lg['id_lang']);
            }
            Configuration::updateValue('STORE_CONTACT_INFO', $STORE_CONTACT_INFO,true);

            $this->_clearCache('store_contactinfo.tpl');
            if (!isset($errors) || count($errors)<1)
                $html .= $this->displayConfirmation($this->l('Your settings have been updated.'));
		}
        $html .= $this->displayForm();
        return $html;
    }

    private function displayForm(){
        $languages = Language::getLanguages();
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $STORE_CONTACT_INFO = array();
        foreach ($languages as $lg){
            $STORE_CONTACT_INFO[$lg['id_lang']] = Configuration::get('STORE_CONTACT_INFO',$lg['id_lang']);
        }
        $lang_ul = '<ul class="dropdown-menu">';
        foreach ($languages as $lg){
            $lang_ul .='<li><a href="javascript:hideOtherLanguage('.$lg['id_lang'].');" tabindex="-1">'.$lg['name'].'</a></li>';
        }
        $lang_ul .='</ul>';
        $this->context->smarty->assign(array(
            'lang_ul' => $lang_ul,
            'imgpath' => $this->_path.'img/',
            'postAction' => AdminController::$currentIndex .'&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'STORE_CONTACT_INFO' => $STORE_CONTACT_INFO,
            'langguages' => array(
				'default_lang' => $id_lang_default,
				'all' => $languages,
				'lang_dir' => _THEME_LANG_DIR_)
		));
        $iso = Language::getIsoById((int)($this->context->language->id));
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ?
			$iso : 'en');
		$ad = dirname($_SERVER["PHP_SELF"]);
		$html = '<script type="text/javascript" src="' . __PS_BASE_URI__ .'js/tiny_mce/tiny_mce.js"></script>
                    <script type="text/javascript" src="' . __PS_BASE_URI__ .'js/tinymce.inc.js"></script>
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
        return  $html.$this->display(__FILE__, 'views/templates/admin/main.tpl');
    }

	public function hookdisplayStoreMap($params)
	{
	   	$distanceUnit = Configuration::get('PS_DISTANCE_UNIT');
		if (!in_array($distanceUnit, array('km', 'mi')))
			$distanceUnit = 'km';
	   $this->context->smarty->assign(array(
			'defaultLat' => (float)Configuration::get('PS_STORES_CENTER_LAT'),
			'defaultLong' => (float)Configuration::get('PS_STORES_CENTER_LONG'),
			'storeName' => Configuration::get('PS_SHOP_NAME')
		));
		return $this->display(__FILE__, 'ovicstoremap.tpl');
	}

    public function hookdisplayContactInfo(){
        if (!$this->isCached('store_contactinfo.tpl', $this->getCacheId()))
		{
			$this->context->smarty->assign(array(
                'STORE_CONTACT_INFO' => Configuration::get('STORE_CONTACT_INFO',$this->context->language->id),
            ));
		}
		return $this->display(__FILE__, 'store_contactinfo.tpl', $this->getCacheId());
    }

	public function hookHeader($params)
	{
	   
        if (!empty($this->context->controller->page_name)){
            $page_name =$this->context->controller->page_name;
        }
        elseif (!empty($this->context->controller->php_self)){
            $page_name = $this->context->controller->php_self;
        }
        elseif (Tools::getValue('fc') == 'module' && $module_name != '' && (Module::
            getInstanceByName($module_name) instanceof PaymentModule))
            $page_name = 'module-payment-submit';
        // @retrocompatibility Are we in a module ?
        elseif (preg_match('#^' . preg_quote($this->context->shop->physical_uri, '#') .
            'modules/([a-zA-Z0-9_-]+?)/(.*)$#', $_SERVER['REQUEST_URI'], $m)){
                $page_name = 'module-' . $m[1] . '-' . str_replace(array('.php', '/'), array('',
                    '-'), $m[2]);
            }
            
        else {
            $page_name = Dispatcher::getInstance()->getController();
            $page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_' . $page_name : $page_name);
        }
       
        // CSS in global.css file
		//$this->context->controller->addCSS(($this->_path).'css/ovicstoremap.css', 'all');
        $default_country = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
        if ($page_name == 'contact') {
            $this->context->controller->addJS('http'.((Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 's' : '').'://maps.google.com/maps/api/js');
            $this->context->controller->addJS($this->_path.'ovicstoremap.js');    
        }
        $this->context->smarty->assign(array(
            'HOOK_DISPLAYSTOREMAP' => Hook::exec('displayStoreMap'),
            'HOOK_DISPLAYCONTACTINFO' => Hook::exec('displayContactInfo'),
        ));
	}
}
