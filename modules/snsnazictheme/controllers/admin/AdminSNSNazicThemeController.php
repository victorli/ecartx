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
include_once(dirname(dirname(dirname(__FILE__))) . '/snsnaziccore.php');

class AdminSNSNazicThemeController extends ModuleAdminController {
    public $SNSCore;
    public function __construct() {
        parent::__construct();
        if (!(bool)Tools::getValue('ajax'))
        	Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure=snsnazictheme');
        else $this->SNSCore = new SNSNazicCore;
    }
	public function ajaxProcessSectionSubmit()
	{
		$this->SNSCore->updateFields(Tools::getValue('name'));
		die(Tools::jsonEncode(array(
			'success' => true
		)));
	}
	public function ajaxProcessClearCss()
	{
		$this->clearCacheCss();
		die(Tools::jsonEncode(array(
			'success' => true
		)));
	}
	public function clearCacheCss() {
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
            		if($delall && (preg_match("/css$/i", $file) || preg_match("/js$/i", $file))) {
					    $filePath = $directory.$file;
						unlink($filePath);
            		} elseif (preg_match("/css$/i", $file) && preg_match("/^theme-/i", $file)) {
					    $filePath = $directory.$file;
						unlink($filePath);
					}
	            }
	        }
	        closedir($handle);
	    }
	}
}
