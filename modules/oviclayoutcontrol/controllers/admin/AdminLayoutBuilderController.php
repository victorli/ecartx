<?php
require_once (dirname(__file__) . '/../../class/Options.php');
require_once(dirname(__FILE__).'/../../oviclayoutcontrol.php');

class AdminLayoutBuilderController extends ModuleAdminController {
    public function __construct() {
        $this->module = 'oviclayoutcontrol';
        $this->lang = true;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        parent::__construct();
    }
    public function renderList(){
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $view = Tools::getValue('view','displaylist');
        $tpl = $this->createTemplate('oviclayoutbuilder.tpl');
        $tpl->assign( array(
            'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutBuilder'),
            'absoluteUrl' => __PS_BASE_URI__.'modules/'.$this->module->name,
            'thumbnails_dir' => __PS_BASE_URI__.'modules/oviclayoutcontrol',
            'logo_url' =>  $this->context->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO')),
            'view' => $view
        ));
        if ($view == 'displaylist'){
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
            
            $tpl->assign(array(
                'options' => $options
            ));
        }elseif($view == 'setting'){
            $id_option = (int)Tools::getValue('id_option');
            if ($id_option && Validate::isUnsignedId($id_option) && OvicLayoutControl::isAvailablebyId($id_option)){
                $option = new Options($id_option);
            }else{
                $option = new Options();
            }
            if (Tools::isSubmit('submitnewOption')){
                $option->column = Tools::getValue('colselected');
                $option->active = (int)Tools::getValue('active');
                foreach ($languages as $language)
                {
                    $option->name[$language['id_lang']] = Tools::getValue('option_name_' . $language['id_lang']);
                }
                $option->image = Tools::getValue('old_img');
            }elseif (Tools::isSubmit('submitCopyOption')){
                $id_copy_option = Tools::getValue('id_copy_option');
                $copy_option = new Options($id_copy_option);
                $option->name = $copy_option->name;
                $option->column = $copy_option->column;
                $option->active = $copy_option->active;
                $tpl->assign('id_copy_option',$id_copy_option);
            }
            $lang_ul = '<ul class="dropdown-menu">';
            foreach ($languages as $lg)
            {
                $lang_ul .= '<li><a href="javascript:hideOtherLanguage(' . $lg['id_lang'] . ');" tabindex="-1">' . $lg['name'] .
                    '</a></li>';
            }
            $lang_ul .= '</ul>';
            $tpl->assign(array(
                'option' => $option,
                'lang_ul' => $lang_ul,
                'langguages' => array(
                    'default_lang' => $id_lang_default,
                    'all' => $languages,
                    'lang_dir' => _THEME_LANG_DIR_)
            ));
        }elseif($view == 'detail'){
            $id_option = (int)Tools::getValue('id_option');
            if ($id_option && Validate::isUnsignedId($id_option)){
                $optionTheme = new Options($id_option);
                $displayLeft = false;
                $displayRight = false;
                $homeWidth = 12;
                if (substr_count($optionTheme->column,'1')>0 || substr_count($optionTheme->column,'0')>0){
                    $displayLeft = true;
                    $homeWidth -= 3;
                }
                if (substr_count($optionTheme->column,'2')>0 || substr_count($optionTheme->column,'0')>0){
                    $displayRight = true;
                    $homeWidth -= 3;
                }
                $optionModulesHook = OvicLayoutControl::getOptionModulesHook($id_option);
                $tpl->assign(array(
                    'id_option' => $id_option,
                    'optionModulesHook' => $optionModulesHook,
                    'displayLeft' => $displayLeft,
                    'displayRight' => $displayRight,
                    'homeWidth' => $homeWidth,
                    'moduleDir' => _MODULE_DIR_,
                    'templatePath' => $this->getTemplatePath(),
                ));
            }
        }
        return $tpl->fetch();
    }


    public function setMedia(){
        parent::setMedia();
        $this->addJqueryPlugin('fancybox');
		$this->addJqueryUi('ui.sortable');
        $this->addJS(_PS_MODULE_DIR_.$this->module->name.'/js/layoutbuilder.js');
    }

    private function echoArr($arr = array(),$die = false){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        if ($die)
            die();
    }
    /**
	 * Process posting data
	 */
	public function postProcess() {
	   $errors = array();
	   $languages = Language::getLanguages(false);
        if (Tools::isSubmit('submitnewOption')){
            $id_option = Tools::getValue('id_option');
            if ($id_option && Validate::isUnsignedId($id_option)){
                $option = new Options($id_option);
                $addaction = false;
            }else{
                $option = new Options();
                $addaction = true;
            }
            $option->column = Tools::getValue('colselected');
            $option->active = (int)Tools::getValue('active');
            $name_set = false;
            foreach ($languages as $language)
            {
                $option_name = Tools::getValue('option_name_' . $language['id_lang']);
                if (strlen($option_name) > 0)
                {
                    $name_set = true;
                }
                $option->name[$language['id_lang']] = $option_name;
            }
            if (!$name_set)
            {
                $lang_title = Language::getLanguage($this->context->language->id);
                $errors[] = Tools::displayError('This item title field is required at least in ' . $lang_title['name']);
            }
            if (isset($_FILES['option_img']) && strlen($_FILES['option_img']['name']) > 0)
            {
                $img_name = time() . $_FILES['option_img']['name'];
                $img_name = preg_replace('/[^A-Za-z0-9\-.]/', '', $img_name); // Removes special chars.
                //_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->name .DIRECTORY_SEPARATOR
                $main_name = _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR.'oviclayoutcontrol'.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR. $img_name;
                if (!move_uploaded_file($_FILES['option_img']['tmp_name'], $main_name))
                {
                    $errors[] = Tools::displayError('An error occurred during the image upload.');
                }
                else
                {
                    $option->image = $img_name;
                    if (Tools::getValue('old_img') != '')
                    {
                        $filename = Tools::getValue('old_img');
                        if (file_exists(_PS_MODULE_DIR_ . 'oviclayoutcontrol/thumbnails/' . $filename))
                        {
                            @unlink(_PS_MODULE_DIR_ . 'oviclayoutcontrol/thumbnails/' . $filename);
                        }
                    }
                }
            }
            else
            {
                $option->image = Tools::getValue('old_img');
            }
            if (!count($errors)){
                if ($addaction)
                {
                    if (!$option->add())
                        $errors[] = Tools::displayError('An error occurred while saving data.');
                    else{
                        if (Tools::getIsset('id_copy_option')){
                            $id_copy_option = Tools::getValue('id_copy_option');
                            OvicLayoutControl::copyHookModule($id_copy_option,$option->id);
                        }else{
                            OvicLayoutControl::registerDefaultHookModule($option->id);
                        }
                    }
                }else{
                    if (!$option->update())
                        $errors[] = Tools::displayError('An error occurred while update data.');
                }
                if (!count($errors)){
                    Tools::redirectAdmin(self::$currentIndex.'&conf=3&token='.Tools::getValue('token'));
    			}
            }
        }elseif (Tools::isSubmit('changeactive'))
        {
            $id_option = Tools::getValue('id_option');
            if ($id_option && Validate::isUnsignedId($id_option)){
                $option = new Options($id_option);
                $option->active = !$option->active;
                if (!$option->update())
                {
                    $errors[] = Tools::displayError('Could not change');
                }
                else
                {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=5&token='.Tools::getValue('token'));
                }
            }
        }
        elseif (Tools::isSubmit('removeoption')){
            $id_option = Tools::getValue('id_option');
            if ($id_option && Validate::isUnsignedId($id_option)){
                $option = new Options($id_option);
                if (!$option->delete())
                {
                    $errors[] = Tools::displayError('An error occurred during deletion');
                }
                else
                {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=1&token='.Tools::getValue('token'));
                }
            }
        }else{
            $this->context->controller->errors = array_merge($this->context->controller->errors, $errors);
        }
        parent::postProcess();
	}
    /**
	 * Display add new module form
	 */
    public function ajaxProcessdisplayModulesHook(){
        $result = "";
        $id_hook = (int)Tools::getValue('id_hook');
        $hookname = Hook::getNameById($id_hook);
        $id_option = (int)Tools::getValue('id_option');
        if ($hookname && Validate::isHookName($hookname) && $id_option && Validate::isUnsignedId($id_option)){
            if ($hookname =='displayHomeTab' || $hookname =='displayHomeTabContent')
                $optionModulesHook = OvicLayoutControl::getModuleExecList($hookname);
            else
                $optionModulesHook = OvicLayoutControl::getModuleExecList();

            $moduleOption = '';
            $HookedModulesArr = OvicLayoutControl::getModulesHook($id_option, $hookname);
            $HookedModulesArr = Tools::jsonDecode($HookedModulesArr['modules'],true);
            //$result .= print_r($HookedModules);
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
                    if ($hookname =='displayHomeTab' || $hookname =='displayHomeTabContent'){
                        if (in_array($module['id_module'],$HookedModules)){
                            $disableModule = true;
                        }
                    }else{
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
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutBuilder').'&ajax=1&action=getModuleHookOption',
                'id_hook' => $id_hook,
                'hookname' => $hookname,
                'id_option' => $id_option,
                'moduleOption' => $moduleOption,
            ));
            $result .= $tpl->fetch();
        }
        die(Tools::jsonEncode($result));
    }
    /**
	 * Display change hook off module
	 */
    public function ajaxProcessdisplayChangeHook(){
        $result = "";
        $id_hook = (int)Tools::getValue('id_hook');
        $hookname = Hook::getNameById($id_hook);
        $id_option = (int)Tools::getValue('id_option');
        if ($hookname && Validate::isHookName($hookname) && $id_option && Validate::isUnsignedId($id_option)){
            $id_module = (int)Tools::getValue('id_module');
            $id_hookexec = (int)Tools::getValue('id_hookexec');
            if ($id_module && Validate::isUnsignedId($id_module) && $id_hookexec && Validate::isUnsignedId($id_hookexec)){
                $moduleObject = Module::getInstanceById($id_module);
                $optionModules = OvicLayoutControl::getModulesHook($id_option, $hookname);
                $optionModules = Tools::jsonDecode($optionModules['modules'],true);
                $hookOptions =  OvicLayoutControl::getHookOptionByModule($optionModules, $hookname ,$moduleObject, $id_hookexec);
            }
            $tpl = $this->createTemplate('changehook.tpl');
            $tpl->assign( array(
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutBuilder'),
                'id_hook' => $id_hook,
                'id_option' => $id_option,
                'old_hook' => $id_hookexec,
                'id_module' => $id_module,
                'hookOptions' => $hookOptions,
            ));
            $result .= $tpl->fetch();
        }
        die(Tools::jsonEncode($result));
    }

    /**
	 * get all hook off module, return list option hook
	 */
    public function ajaxProcessgetModuleHookOption(){
        $html = '';
        $id_module = (int)Tools::getValue('id_module');
        $id_hook = (int)Tools::getValue('id_hook');
        $hookname = Hook::getNameById($id_hook);
        $id_option = (int)Tools::getValue('id_option');
        if ($id_module && Validate::isUnsignedId($id_module) && $hookname && Validate::isHookName($hookname)){
            $moduleObject = Module::getInstanceById($id_module);
            $optionModules = OvicLayoutControl::getModulesHook($id_option, $hookname);
            $optionModules = Tools::jsonDecode($optionModules['modules'],true);
            $html = OvicLayoutControl::getHookOptionByModule($optionModules, $hookname,$moduleObject);
        }
        echo $html;
    }
    /**
	 * insert new module to a hook
	 */
    public function ajaxProcessaddModuleHook(){
        $result = array();
        $id_hook = (int)Tools::getValue('id_hook');
        $hookname = Hook::getNameById($id_hook);
        $id_option = (int)Tools::getValue('id_option');
        $id_hookexec = (int)Tools::getValue('id_hookexec');
        $hookexec_name = Hook::getNameById($id_hookexec);
        $id_module = (int)Tools::getValue('id_module');
        if ($id_module && Validate::isUnsignedId($id_module) && $hookexec_name && Validate::isHookName($hookexec_name)){
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getModulesHook($id_option, $hookname);
            $HookedModulesArr = Tools::jsonDecode($HookedModulesArr['modules'],true);
            if (!is_array($HookedModulesArr)){
                $HookedModulesArr = array();
            }
            $moduleHook = array();
            $moduleHook[] = $moduleObject->name;
            $moduleHook[] = $hookexec_name;
            $HookedModulesArr[] = $moduleHook;
            $result['status'] = OvicLayoutControl::registerHookModule($id_option, $hookname, Tools::jsonEncode($HookedModulesArr),$this->context->shop->id);
            $result['msg'] = $this->l('Successful creation');
            $tpl = $this->createTemplate('module.tpl');
            $tpl->assign( array(
                'id_hookexec' => $id_hookexec,
                'hookexec_name' => $hookexec_name,
                'modulePosition' => count((array)$HookedModulesArr),
                'moduleDir' => _MODULE_DIR_,
                'moduleObject' => $moduleObject,
                'id_hook' => $id_hook,
                'id_option' => $id_option,
                'postUrl' => self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminLayoutBuilder'),
            ));
            $result['html'] = $tpl->fetch();
        }
        Tools::clearCache();
        die(Tools::jsonEncode($result));
    }
    /**
	 * Change hook execute off module
	 */
    public function ajaxProcessChangeModuleHook(){
        $result = array();
        $id_hook = (int)Tools::getValue('id_hook');
        $hookname = Hook::getNameById($id_hook);
        $id_option = (int)Tools::getValue('id_option');
        $id_hookexec = (int)Tools::getValue('id_hookexec');
        $hookexec_name = Hook::getNameById($id_hookexec);
        $old_hook = (int)Tools::getValue('old_hook');
        $id_module = (int)Tools::getValue('id_module');

        if ($id_module && Validate::isUnsignedId($id_module) && $hookname && Validate::isHookName($hookname) && $hookexec_name && Validate::isHookName($hookexec_name)){
            $result['status'] = true;
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getModulesHook($id_option, $hookname);
            $HookedModulesArr = Tools::jsonDecode($HookedModulesArr['modules'],true);
            if (!is_array($HookedModulesArr)){
                $result['status'] = false;
            }
            if ($result['status']){
                $moduleHook = array();
                $moduleHook[] = $moduleObject->name;
                $moduleHook[] = Hook::getNameById($old_hook);
                $key = array_search($moduleHook,$HookedModulesArr);
                $result['test'] = $key;
                if (array_key_exists($key,$HookedModulesArr)){
                    $moduleHook[1] = $hookexec_name;
                    $HookedModulesArr[$key] = $moduleHook;
                    $result['status'] = OvicLayoutControl::registerHookModule($id_option, $hookname, Tools::jsonEncode($HookedModulesArr),$this->context->shop->id);
                    $result['moduleinfo'] = $moduleObject->name.'-'.$hookexec_name;
                }
            }
        }
        die(Tools::jsonEncode($result));
    }
    /**
	 * remove a module from a hook
	 */
    public function ajaxProcessremoveModuleHook(){
        $result = array();
        $hookname = Tools::getValue('hookname');
        $id_option = (int)Tools::getValue('id_option');
        $id_hookexec = (int)Tools::getValue('id_hookexec');
        $hookexec_name = Hook::getNameById($id_hookexec);
        $id_module = (int)Tools::getValue('id_module');

        if ($id_module && Validate::isUnsignedId($id_module) && $hookname && Validate::isHookName($hookname) && $hookexec_name && Validate::isHookName($hookexec_name)){
            $moduleObject = Module::getInstanceById($id_module);
            $HookedModulesArr = OvicLayoutControl::getModulesHook($id_option, $hookname);
            $HookedModulesArr = Tools::jsonDecode($HookedModulesArr['modules'],true);
            $HookedModulesArr = array_values($HookedModulesArr);
            $moduleHook = array();
            $moduleHook[] = $moduleObject->name;
            $moduleHook[] = $hookexec_name;
            if ($HookedModulesArr && is_array($HookedModulesArr) && sizeof($HookedModulesArr)){
                $key = array_search($moduleHook,$HookedModulesArr);
                unset($HookedModulesArr[$key]);
            }
            $HookedModulesArr = array_values($HookedModulesArr);
            $result['status'] = OvicLayoutControl::registerHookModule($id_option, $hookname, Tools::jsonEncode($HookedModulesArr),$this->context->shop->id);
            $result['msg'] = $this->l('Successful deletion');
                //$this->displayError
        }
        Tools::clearCache();
        die(Tools::jsonEncode($result));
    }
    public function ajaxProcessupdateHook(){
        $result = array();
        $id_option = (int)Tools::getValue('id_option');
        $datahooks = Tools::getValue('datahook');
        $datahooks = Tools::jsonDecode($datahooks,true);
        if ($id_option && Validate::isUnsignedId($id_option) && $datahooks && is_array($datahooks) && sizeof($datahooks)>0){
            foreach ($datahooks as $hookmodules){
                $res = array();
                $hookName = key($hookmodules);
                $res['status'] = OvicLayoutControl::registerHookModule($id_option, $hookName, Tools::jsonEncode($hookmodules[$hookName]),$this->context->shop->id);
                $res['hookname'] = $hookName;
                $result[] = $res;
            }
        }
        Tools::clearCache();
        die(Tools::jsonEncode($result));
    }
}
