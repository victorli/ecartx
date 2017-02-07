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

include_once('../../config/config.inc.php');
include_once('../../init.php');
if (!Tools::getValue('action') && !Tools::getValue('color1')) die(1);

if (Tools::getValue('action') == 'compilescss') {
	
	$scssDir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/sass/';
	$cssDir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/css/';
	$themeColor = Tools::getValue('color1');
	$themeColor = strtolower($themeColor);
	$themeCssName = 'theme-' . str_replace("#", "", $themeColor) . '.css';
	
	require "scssphp/scss.inc.php";
	require "scssphp/compass/compass.inc.php";
	
	$scss = new scssc();
	new scss_compass($scss);

	$scss->setFormatter('scss_formatter_compressed');
	$scss->addImportPath($scssDir);
	
	$variables = '$color1: '.$themeColor.';';

	$string_sass = $variables . file_get_contents($scssDir . "theme.scss");
	$string_css = $scss->compile($string_sass);
	
	file_put_contents($cssDir . $themeCssName, $string_css);
	
    die(Tools::jsonEncode(array()));
}