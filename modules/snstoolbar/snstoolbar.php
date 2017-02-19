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

class SNSToolbar extends Module {

    private $qr_api_url = 'http://pan.baidu.com/share/qrcode?';
    
	public function __construct()
	{
		$this->name = 'snstoolbar';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'SNS Theme';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		$this->bootstrap = true;
		$this->_directory = dirname(__FILE__);

		parent::__construct();

		$this->displayName = $this->l('SNS Toolbar');
		$this->description = $this->l('This is module display toobar');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	public function install()
	{
		if (!parent::install()) return false;

		// Activate every option by default
		Configuration::updateValue('SNSTB_STATUS', '1');
		Configuration::updateValue('SNSTB_COMPARE', '1');
		Configuration::updateValue('SNSTB_QRCODE', '1');

		$this->registerHook('displayFooter');

		$this->snsCreateTab();
		return true;
	}


	public function uninstall()
	{
		Configuration::deleteByName('SNSTB_STATUS');
		Configuration::deleteByName('SNSTB_COMPARE');
		Configuration::deleteByName('SNSTB_QRCODE');

		$this->snsDeleteTab();
		return parent::uninstall();
	}
    private function _generateQR()
    {
        $image_link = $this->qr_api_url.'w=150&h=150&url='.urlencode(Tools::getProtocol(Tools::usingSecureMode()).$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); 
        $this->smarty->assign(array(
            'size' => 150,
            'image_link' => $image_link
        ));
        return true;        
    }
	private function snsCreateTab()
	{
		$response = true;
		$parent_tab_id = Tab::getIdFromClassName('AdminSNS');
		if ($parent_tab_id)
			$parent_tab = new Tab($parent_tab_id);
		else
		{
			$parent_tab = new Tab();
			$parent_tab->active = 1;
			$parent_tab->name = array();
			$parent_tab->class_name = 'AdminSNS';
			foreach (Language::getLanguages() as $lang)
				$parent_tab->name[$lang['id_lang']] = 'SNS Theme';

			$parent_tab->id_parent = 0;
			$parent_tab->module = $this->name;
			$response &= $parent_tab->add();
		}

		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminSNSToolbar';
		$tab->name = array();
		foreach (Language::getLanguages() as $lang)
			$tab->name[$lang['id_lang']] = 'SNS Toolbar';

		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		$response &= $tab->add();

		return $response;
	}
	private function snsDeleteTab()
	{
		$id_tab = Tab::getIdFromClassName('AdminSNSToolbar');
		$parent_tab_id = Tab::getIdFromClassName('AdminSNS');

		$tab = new Tab($id_tab);
		$tab->delete();

		$tab_count = Tab::getNbTabs($parent_tab_id);
		if ($tab_count == 0)
		{
			$parent_tab = new Tab($parent_tab_id);
			$parent_tab->delete();
		}
		return true;
	}
	public function getConfigFieldsValues()
	{
		$val = array();
		$val['SNSTB_STATUS'] = Configuration::get('SNSTB_STATUS');
		$val['SNSTB_COMPARE'] = Configuration::get('SNSTB_COMPARE');
		$val['SNSTB_QRCODE'] = Configuration::get('SNSTB_QRCODE');
		return $val;
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitSNSToolbar'))
		{
			Configuration::updateValue('SNSTB_STATUS', Tools::getValue('SNSTB_STATUS'));
			Configuration::updateValue('SNSTB_COMPARE', Tools::getValue('SNSTB_COMPARE'));
			Configuration::updateValue('SNSTB_QRCODE', Tools::getValue('SNSTB_QRCODE'));

			$output .= $this->displayConfirmation($this->l('Settings updated'));
			Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('snstoolbar.tpl'));
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true)
									.'&conf=6&configure='.$this->name
									.'&tab_module='.$this->tab
									.'&module_name='.$this->name);
		}
		$output .= $this->getFormHTML();
		return $output;
	}
	public function getFormHTML()
	{
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSNSToolbar';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
									.'&configure='.$this->name
									.'&tab_module='.$this->tab
									.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		$fields_form = array();
		$fields_form[0] = array(
			array(
				'type' => 'select',
				'label' => $this->l('Status'),
				'name' => 'SNSTB_STATUS',
				'options' => array(
					'query' => array(
						array('id' => '2', 'name' => 'Collapse'),
						array('id' => '1', 'name' => 'Expand'),
						array('id' => '0', 'name' => 'Disable')
					),
					'id' => 'id',
					'name' => 'name'
				),
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Compare'),
				'name' => 'SNSTB_COMPARE',
				'values' => array(
					array(
						'id' => 'SNSTB_COMPARE_ON',
						'value' => 1,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'SNSTB_COMPARE_OFF',
						'value' => 0,
						'label' => $this->l('Disabled')
					)
				)
			),
			array(
				'type' => 'switch',
				'label' => $this->l('QR Code'),
				'name' => 'SNSTB_QRCODE',
				'values' => array(
					array(
						'id' => 'SNSTB_QRCODE_ON',
						'value' => 1,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'SNSTB_QRCODE_OFF',
						'value' => 0,
						'label' => $this->l('Disabled')
					)
				)
			)
		);
		return $helper->generateForm(array(
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('General Options'),
						'icon' => 'icon-cogs'
					),
					'input' => $fields_form[0],
					'submit' => array(
						'title' => $this->l('Save')
					)
				)
			)
		));
	}
	public function getConfigLang($field)
	{
		$lang = $this->context->language->id;
		if (is_bool(Configuration::get($field, $this->context->language->id)))
			$lang = Configuration::get('PS_LANG_DEFAULT');
		else
			$lang = $this->context->language->id;
		return $this->replaceLinkContent(Configuration::get($field, $lang), true);
	}
	protected function displaySNSToolbar()
	{
		if (!Configuration::get('SNSTB_STATUS')) return;
		
		$start_status = (Configuration::get('SNSTB_STATUS') == 1) ? 1 : 0;
		
		$SNS_TOOLBAR_DISPLAY = (isset($_COOKIE['SNS_TOOLBAR_DISPLAY'])) ? $_COOKIE['SNS_TOOLBAR_DISPLAY'] : $start_status;
		
		$this->_generateQR();
		$this->context->smarty->assign(array(
			'SNSTB_COMPARE' => Configuration::get('SNSTB_COMPARE'),
			'SNSTB_QRCODE' => Configuration::get('SNSTB_QRCODE'),
			'SNS_TOOLBAR_DISPLAY' => $SNS_TOOLBAR_DISPLAY
		));
		return $this->display(__FILE__, 'snstoolbar.tpl');
	}
	public function hookDisplayFooter()
	{
		return $this->displaySNSToolbar();
	}
	public function replaceLinkContent($string, $out = false)
	{
		if($out) return str_replace('[__PS_BASE_URI__]', _PS_BASE_URL_.__PS_BASE_URI__, $string);
		else  return str_replace(_PS_BASE_URL_.__PS_BASE_URI__, '[__PS_BASE_URI__]', $string);
	}
}
