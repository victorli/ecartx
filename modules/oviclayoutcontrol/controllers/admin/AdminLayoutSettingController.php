<?php
require_once (dirname(__file__) . '/../../class/Options.php');
require_once(dirname(__FILE__).'/../../oviclayoutcontrol.php');

class AdminLayoutSettingController extends ModuleAdminController {
    public function __construct() {
        $this->module = 'oviclayoutcontrol';
        $this->lang = true;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        parent::__construct();
    }


    public function renderList(){
        $view = Tools::getValue('view','default');
        if ($view =='default'){
            $languages = Language::getLanguages(false);
            $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
            $tpl = $this->createTemplate('oviclayout.tpl');
            $id_tab = (int)Tools::getValue('id_tab',0);
            /*
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'ovic_options` o
                LEFT JOIN `' . _DB_PREFIX_ .'ovic_options_lang` ol ON (o.`id_option` = ol.`id_option`) WHERE `id_lang`='.$id_lang_default;
              
            $options = Db::getInstance()->executeS($sql);
            */
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'ovic_options` o';
            $options = Db::getInstance()->executeS($sql);
            if ($options && is_array($options) && sizeof($options)>0)
                foreach ($options as &$option){
                    $sql = 'SELECT `name` FROM `' . _DB_PREFIX_ .'ovic_options_lang` WHERE `id_option` = '.(int)$option['id_option'].' AND `id_lang`='.$id_lang_default;
                    if ($name = Db::getInstance()->getValue($sql))
                        $option['name'] = $name;
                    else
                        $option['name'] = '';
                }
            $current_id_option = Configuration::get('OVIC_CURRENT_OPTION');
            if ($options && is_array($options) && sizeof($options)>0){
                if (!$current_id_option || !Validate::isUnsignedId($current_id_option) || !OvicLayoutControl::isAvailablebyId($current_id_option)){
                    foreach ($options as $option){
                        $current_option = new Options($option['id_option'],$this->context->language->id);
                        Configuration::updateValue('OVIC_CURRENT_OPTION',$option['id_option']);
                        break;
                    }
                }else{
                    $current_option = new Options($current_id_option,$this->context->language->id);
                }
                $selected_layout = Configuration::get('OVIC_LAYOUT_COLUMN');
                if (!$selected_layout || substr_count($current_option->column,$selected_layout)<1){
                        if (strlen($current_option->column)>0){
                            $selected_layout = (int)substr($current_option->column,0,1);
                            Configuration::updateValue('OVIC_LAYOUT_COLUMN',$selected_layout);
                            $this->ProcessLayoutColumn();
                        }
                    }
            }else{
                $tpl->assign(array('emptyOption' => Tools::displayError('There is no Option, please add new Option from Layout Builder menu.')));
            }

            //get sidebar infomation
            $pagelist = Meta::getMetas();
            $sidebarPages = array();
            if ($pagelist && is_array($pagelist) && sizeof($pagelist)>0){
                $theme = new Theme((int)$this->context->shop->id_theme);
                foreach ($pagelist as $page){
                    $sidebarPage = array();
                    $meta_object = New Meta($page['id_meta']);
        			$title = $page['page'];
        			if (isset($meta_object->title[(int)$this->context->language->id]) && $meta_object->title[(int)$this->context->language->id] != '')
        				$title = $meta_object->title[(int)$this->context->language->id];
                    $sidebarPage['id_meta'] = $page['id_meta'];
                    $sidebarPage['title'] = $title;
                    $sidebarPage['page_name'] = $page['page'];
                    $sidebarPage['displayLeft'] = $theme->hasLeftColumn($page['page'])? 1:0;
                    $sidebarPage['displayRight'] = $theme->hasRightColumn($page['page'])? 1:0;
                    $sidebarPages[] = $sidebarPage;
                }
            }

            $tpl->assign( array(
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutSetting'),
                'absoluteUrl' => __PS_BASE_URI__.'modules/'.$this->module->name,
                'id_tab' => $id_tab,
                'options' => $options,
                'current_option' => isset($current_option)? $current_option:null,
                'selected_layout' => isset($selected_layout)? $selected_layout:null,
                'sidebarPages' => $sidebarPages,
                //'multistyle' => $this->renderForm()
            ));
        }elseif ($view == 'detail'){
            $tpl = $this->createTemplate('sidebarmodule.tpl');
            $pagemeta = Tools::getValue('pagemeta');
            $meta = Meta::getMetaByPage($pagemeta,$this->context->language->id);
            $theme = new Theme((int)$this->context->shop->id_theme);
            $LeftModules = array();
            $RightModules = array();
            if ($theme->hasLeftColumn($pagemeta)){
                $LeftModules = OvicLayoutControl::getSideBarModulesByPage($pagemeta,'left');
            }
            if ($theme->hasRightColumn($pagemeta)){
                $RightModules = OvicLayoutControl::getSideBarModulesByPage($pagemeta,'right');
            }
            $tpl->assign( array(
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutSetting'),
                'leftModule' => $LeftModules,
                'rightModule' => $RightModules,
                'pagemeta' => $pagemeta,
                'pagename' => $meta['title'],
                'displayLeft' => $theme->hasLeftColumn($pagemeta),
                'displayRight' => $theme->hasRightColumn($pagemeta),
                'templatePath' => $this->getTemplatePath(),
                'moduleDir' => _MODULE_DIR_,
            ));
        }
        return $tpl->fetch();
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
						'type' => 'hidden',
						'name' => 'id_tab',
					),
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
		$helper->submit_action = 'submitMultiSave';
		$helper->currentIndex = self::$currentIndex;
		$helper->token = Tools::getAdminTokenLite('AdminLayoutSetting');
		$helper->tpl_vars = array(
			'fields_value' => array(
                'id_tab' => 1,
                'FONT_LINK'=> html_entity_decode(Tools::getValue('FONT_LINK',Configuration::get('OVIC_FONT_LINK'))),
                'MAIN_COLOR'=> Tools::getValue('MAIN_COLOR',Configuration::get('OVIC_MAIN_COLOR')),
                'BTN_COLOR'=> Tools::getValue('BTN_COLOR',Configuration::get('OVIC_BTN_COLOR')),
                'BTN_HOVER_COLOR'=> Tools::getValue('BTN_HOVER_COLOR',Configuration::get('OVIC_BTN_HOVER_COLOR')),
                'BTN_TEXT_COLOR'=> Tools::getValue('BTN_COLOR',Configuration::get('OVIC_BTN_TEXT_COLOR')),
                'BTN_TEXT_HOVER_COLOR'=> Tools::getValue('BTN_HOVER_COLOR',Configuration::get('OVIC_BTN_TEXT_HOVER_COLOR')),
            ),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
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

    public function setMedia(){
        parent::setMedia();
        $this->addJqueryPlugin(array('fancybox', 'idTabs'));
		$this->addJqueryUi('ui.sortable');
        $this->addJS(_PS_MODULE_DIR_.$this->module->name.'/js/layoutsetting.js');
    }

    private function echoArr($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    private function getIdThemeMetaByPage($page=null){
        return Db::getInstance()->getValue(
			'SELECT id_theme_meta
				FROM '._DB_PREFIX_.'theme_meta tm
				LEFT JOIN '._DB_PREFIX_.'meta m ON ( m.id_meta = tm.id_meta )
				WHERE m.page = "'.pSQL($page).'" AND tm.id_theme='.(int)$this->context->shop->id_theme
		);
    }

    private function ProcessLayoutColumn(){
        $theme = new Theme((int)$this->context->shop->id_theme);
        $layoutColumn = (int)Configuration::get('OVIC_LAYOUT_COLUMN');
        $id_theme_meta = $this->getIdThemeMetaByPage('index');
        if ($theme->hasLeftColumn('index')){
            if ($layoutColumn === 2 || $layoutColumn === 3)
                $this->processLeftMeta($id_theme_meta);
        }else{
            if ($layoutColumn === 0 || $layoutColumn === 1)
                $this->processLeftMeta($id_theme_meta);
        }
        if ($theme->hasRightColumn('index')){
            if ($layoutColumn === 1 || $layoutColumn === 3)
                $this->processRightMeta($id_theme_meta);
        }else{
            if ($layoutColumn === 0 || $layoutColumn === 2)
                $this->processRightMeta($id_theme_meta);
        }
        Tools::clearCache();
    }

    private function processLeftMeta($id_theme_meta)
	{
		$theme_meta = Db::getInstance()->getRow(
			'SELECT * FROM '._DB_PREFIX_.'theme_meta WHERE id_theme_meta = '.(int)$id_theme_meta
		);
		$result = false;
		if ($theme_meta)
		{
			$sql = 'UPDATE '._DB_PREFIX_.'theme_meta SET left_column='.(int)!(bool)$theme_meta['left_column'].' WHERE id_theme_meta='.(int)$id_theme_meta;
			$result = Db::getInstance()->execute($sql);
		}
        return $result;
    }

    private function processRightMeta($id_theme_meta)
	{
		$theme_meta = Db::getInstance()->getRow(
			'SELECT * FROM '._DB_PREFIX_.'theme_meta WHERE id_theme_meta = '.(int)$id_theme_meta
		);

		$result = false;
		if ($theme_meta)
		{
			$sql = 'UPDATE '._DB_PREFIX_.'theme_meta SET right_column='.(int)!(bool)$theme_meta['right_column'].' WHERE id_theme_meta='.(int)$id_theme_meta;
			$result = Db::getInstance()->execute($sql);
		}
        return $result;
    }
    /**
	 * Process posting data
	 */
	public function postProcess() {
	   //$errors = array();
	   $languages = Language::getLanguages(false);
        if (Tools::isSubmit('submitChangeLayout')){
            //Configuration::set('OVIC_CURRENT_OPTION',(int)Tools::getValue('id_option',Configuration::get('OVIC_CURRENT_OPTION')));
            Configuration::updateValue('OVIC_LAYOUT_COLUMN',(int)Tools::getValue('colsetting',Configuration::get('OVIC_LAYOUT_COLUMN')));
            $this->ProcessLayoutColumn();
            Tools::clearCache();
        }elseif (Tools::isSubmit('submitSelectOption')){
            $id_option = (int)Tools::getValue('id_option');
            Configuration::updateValue('OVIC_CURRENT_OPTION',$id_option);
            $optionObject = new Options($id_option);
            if (strlen($optionObject->column)>0){
                Configuration::updateValue('OVIC_LAYOUT_COLUMN',(int)substr($optionObject->column,0,1));
            }
            $this->ProcessLayoutColumn();
            Tools::clearCache();
        }elseif (Tools::isSubmit('changeleftactive')){
            $pagemeta = Tools::getValue('pagemeta');
            $id_theme_meta = $this->getIdThemeMetaByPage($pagemeta);
            $this->processLeftMeta($id_theme_meta);
            Tools::clearCache();
        }elseif (Tools::isSubmit('changerightactive')){
            $pagemeta = Tools::getValue('pagemeta');
            $id_theme_meta = $this->getIdThemeMetaByPage($pagemeta);
            $this->processRightMeta($id_theme_meta);
            Tools::clearCache();
        }elseif (Tools::isSubmit('submitMultiSave'))
		{
			Configuration::updateValue('OVIC_FONT_LINK', $this->getHtmlValue('FONT_LINK'));
            Configuration::updateValue('OVIC_MAIN_COLOR', Tools::getValue("MAIN_COLOR"));
            Configuration::updateValue('OVIC_BTN_COLOR', Tools::getValue("BTN_COLOR"));
            Configuration::updateValue('OVIC_BTN_HOVER_COLOR', Tools::getValue("BTN_HOVER_COLOR"));
            Configuration::updateValue('OVIC_BTN_TEXT_COLOR', Tools::getValue("BTN_TEXT_COLOR"));
            Configuration::updateValue('OVIC_BTN_TEXT_HOVER_COLOR', Tools::getValue("BTN_TEXT_HOVER_COLOR"));
            Tools::clearCache();

		}
        Tools::clearCache();
        parent::postProcess();
	}

    /**
	 * remove a module from a column
	 */
    public function ajaxProcessremoveSideBarModule(){
        $result = array();
        $pagemeta = Tools::getValue('pagemeta');
        $hookname = Tools::getValue('hookname');
        $id_hookexec = (int)Tools::getValue('id_hookexec');
        $hookexec_name = Hook::getNameById($id_hookexec);
        $id_module = (int)Tools::getValue('id_module');
        if ($id_module && Validate::isUnsignedId($id_module) && $hookexec_name && Validate::isHookName($hookexec_name)){
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getSideBarModulesByPage($pagemeta, $hookname, false);
            $moduleHook = array();
            $moduleHook[] = $moduleObject->name;
            $moduleHook[] = $hookexec_name;
            if ($HookedModulesArr && is_array($HookedModulesArr) && sizeof($HookedModulesArr)){
                $key = array_search($moduleHook,$HookedModulesArr);
                unset($HookedModulesArr[$key]);
            }
            $HookedModulesArr = array_values($HookedModulesArr);
            $result['status'] = OvicLayoutControl::registerSidebarModule($pagemeta, $hookname, Tools::jsonEncode($HookedModulesArr),$this->context->shop->id);
            $result['msg'] = $this->l('Successful deletion');
        }
        Tools::clearCache();
        die(Tools::jsonEncode($result));
    }
    /**
	 * Display add new module form
	 */
    public function ajaxProcessdisplayModulesHook(){
        $result = "";
        $hookColumn = Tools::getValue('hookname');
        $hookName = 'display'.ucfirst(trim($hookColumn)).'Column';
        $id_hook = Hook::getIdByName($hookName);
        $pagemeta = Tools::getValue('pagemeta');
        $optionModulesHook = OvicLayoutControl::getModuleExecList(array('displayLeftColumn','displayRightColumn'));
        $moduleOption = '';
        $HookedModulesArr = OvicLayoutControl::getSideBarModulesByPage($pagemeta, $hookColumn,false);
        $HookedModules = array();
        $Hookedexecute = array();
        if ($HookedModulesArr && is_array($HookedModulesArr) && sizeof($HookedModulesArr))
            foreach ($HookedModulesArr as $key => $HookedModule){
                $HookedModules[] = (int)$HookedModule[0];
                $Hookedexecute[] = (int)$HookedModule[1];
            }
        $allmoduleDisable = true;
        if ($optionModulesHook && count($optionModulesHook)>0){
            foreach ($optionModulesHook as $module){
                $disableModule = false;
                $moduleObject = Module::getInstanceById($module['id_module']);
                if (in_array($module['id_module'],$HookedModules)){
                    $moduleHookCallable = OvicLayoutControl::getHooksByModule($moduleObject);
                    if (count($moduleHookCallable)>1){
                        $disableModule = true;
                        foreach ($moduleHookCallable as $h){
                            if (!in_array($h['id_hook'],$Hookedexecute)){
                                $disableModule = false;
                                break;
                            }
                        }
                    }else{
                        $disableModule = true;
                    }
                }

                if ($moduleObject->tab != 'analytics_stats')
                    $moduleOption .='<option '.($disableModule? 'disabled':'').' value='.$module['id_module'].'>'.$moduleObject->displayName.'</option>';
                if (!$disableModule){
                    $allmoduleDisable = false;
                }
            }
        }
        $tpl = $this->createTemplate('new_popup.tpl');
        $tpl->assign( array(
            'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutSetting'),
            'id_hook' => $id_hook,
            'hookname' => $hookName,
            'hookcolumn' => $hookColumn,
            'pagemeta' => $pagemeta,
            'moduleOption' => $moduleOption,
        ));
        $result .= $tpl->fetch();
        die(Tools::jsonEncode($result));
    }
    /**
	 * get all hook off module, return list option hook
	 */
    public function ajaxProcessgetModuleHookOption(){
        $html = '';
        $id_module = (int)Tools::getValue('id_module');
        $hookColumn = Tools::getValue('hookcolumn');
        $hookName = 'display'.ucfirst(trim($hookColumn)).'Column';
        $pagemeta = Tools::getValue('pagemeta');
        if ($id_module && Validate::isUnsignedId($id_module) && Validate::isHookName($hookName)){
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getSideBarModulesByPage($pagemeta, $hookColumn,false);
            $html = OvicLayoutControl::getHookOptionByModule($HookedModulesArr, $hookName,$moduleObject,null,true);
        }
        echo $html;
    }
    /**
	 * insert new module to a hook
	 */
    public function ajaxProcessaddModuleHook(){
        $result = array();
        //$id_hook = (int)Tools::getValue('id_hook');
        //$id_option = (int)Tools::getValue('id_option');
        $context =  Context::getContext();
        $id_shop = $context->shop->id;
        $pagemeta = Tools::getValue('pagemeta');
        $hookcolumn = Tools::getValue('hookcolumn');

        $id_hookexec = (int)Tools::getValue('id_hookexec');
        $hookexec_name = Hook::getNameById($id_hookexec);
        $id_module = (int)Tools::getValue('id_module');
        if ($id_module && Validate::isUnsignedId($id_module) && $hookexec_name && Validate::isHookName($hookexec_name)){
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getSideBarModulesByPage($pagemeta, $hookcolumn,false);
            if (!is_array($HookedModulesArr)){
                $HookedModulesArr = array();
            }
            $moduleHook = array();
            $moduleHook[] = $moduleObject->name;
            $moduleHook[] = $hookexec_name;
            $HookedModulesArr[] = $moduleHook;

            $result['status'] = OvicLayoutControl::registerSidebarModule($pagemeta,$hookcolumn,Tools::jsonEncode($HookedModulesArr),$id_shop); //registerHookModule($id_option, $id_hook, Tools::jsonEncode($HookedModulesArr),$this->context->shop->id);
            $result['msg'] = $this->l('Successful creation');
            $tpl = $this->createTemplate('module.tpl');
            $tpl->assign( array(
                'id_hookexec' => $id_hookexec,
                'hookexec_name' => $hookexec_name,
                'modulePosition' => count((array)$HookedModulesArr),
                'moduleDir' => _MODULE_DIR_,
                'moduleObject' => $moduleObject,
                'id_hook' => $id_hook,
                'hookcolumn' => $hookcolumn,
                'id_option' => $id_option,
                'pagemeta' => $pagemeta,
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutSetting'),
            ));
            $result['html'] = $tpl->fetch();
        }
        die(Tools::jsonEncode($result));
    }

    /**
	 * overide hook process
	 */
    public function ajaxProcessdisplayChangeHook(){
        $result = "";
        $pagemeta = Tools::getValue('pagemeta');
        $hookcolumn = Tools::getValue('hookcolumn');
        $hookName = 'display'.ucfirst(trim($hookColumn)).'Column';

        if (Validate::isHookName($hookName)){
            $id_module = (int)Tools::getValue('id_module');
            $id_hookexec = (int)Tools::getValue('id_hookexec');
            if ($id_module && Validate::isUnsignedId($id_module) && $id_hookexec && Validate::isUnsignedId($id_hookexec)){
                $moduleObject = Module::getInstanceById($id_module);
                $optionModules = OvicLayoutControl::getSideBarModulesByPage($pagemeta, $hookcolumn, false);
                //$this->echoArr($optionModules);
                $hookOptions =  OvicLayoutControl::getHookOptionByModule($optionModules, $hookName ,$moduleObject, $id_hookexec,true);
            }
            $tpl = $this->createTemplate('changehook.tpl');
            $tpl->assign( array(
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutSetting'),
                'hookcolumn' => $hookcolumn,
                'pagemeta' => $pagemeta,
                'old_hook' => $id_hookexec,
                'id_module' => $id_module,
                'hookOptions' => $hookOptions,
            ));
            $result .= $tpl->fetch();
        }
        die(Tools::jsonEncode($result));
    }
    /**
	 * Change hook execute off module
	 */
    public function ajaxProcessChangeModuleHook(){
        $result = array();
        $pagemeta = Tools::getValue('pagemeta');
        $hookcolumn = Tools::getValue('hookcolumn');

        $id_hookexec = (int)Tools::getValue('id_hookexec');
        $hookexec_name = Hook::getNameById($id_hookexec);
        $old_hook = (int)Tools::getValue('old_hook');
        $id_module = (int)Tools::getValue('id_module');

        if ($id_module && Validate::isUnsignedId($id_module) && $hookexec_name && Validate::isHookName($hookexec_name)){
            $result['status'] = true;
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getSideBarModulesByPage($pagemeta, $hookcolumn,false);
            if (!is_array($HookedModulesArr)){
                $result['status'] = false;
            }
            if ($result['status']){
                $moduleHook = array();
                $moduleHook[] = $moduleObject->name;
                $moduleHook[] = Hook::getNameById($old_hook);
                $key = array_search($moduleHook,$HookedModulesArr);
                if (array_key_exists($key,$HookedModulesArr)){
                    $moduleHook[1] = $hookexec_name;
                    $HookedModulesArr[$key] = $moduleHook;
                    $result['status'] = OvicLayoutControl::registerSidebarModule($pagemeta, $hookcolumn, Tools::jsonEncode($HookedModulesArr),$this->context->shop->id);
                    $result['moduleinfo'] = $moduleObject->name.'-'.$hookexec_name;
                }
            }
        }
        Tools::clearCache();
        die(Tools::jsonEncode($result));
    }
    /**
	 * process update hook, sortable
	 */
    public function ajaxProcessupdateHook(){
        $result = array();
        $pagemeta = Tools::getValue('pagemeta');
        $datahooks = Tools::getValue('datahook');
        $datahooks = Tools::jsonDecode($datahooks,true);
        if ($datahooks && is_array($datahooks) && sizeof($datahooks)>0){
            foreach ($datahooks as $hookmodules){
                $res = array();
                $hookColumn = key($hookmodules);
                $hookName = 'display'.ucfirst(trim($hookColumn)).'Column';
                $res['status'] = OvicLayoutControl::registerSidebarModule($pagemeta, $hookColumn, Tools::jsonEncode($hookmodules[$hookColumn]),$this->context->shop->id);
                $res['hookname'] = $hookName;
                $result[] = $res;
            }
        }
        Tools::clearCache();
        die(Tools::jsonEncode($result));
    }
}
