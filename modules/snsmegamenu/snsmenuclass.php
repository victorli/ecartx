<?php
/*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class SNSMenuClass {
	public static function gets($id_lang, $id_megamenu = null, $id_shop)
	{
		$sql = 'SELECT l.id_megamenu, l.is_drop, l.new_window, s.name, ll.link, ll.label, ll.customhtml
				FROM '._DB_PREFIX_.'snsmegamenu l
				LEFT JOIN '._DB_PREFIX_.'snsmegamenu_lang ll ON (l.id_megamenu = ll.id_megamenu AND ll.id_lang = '.(int)$id_lang.' AND ll.id_shop='.(int)$id_shop.')
				LEFT JOIN '._DB_PREFIX_.'shop s ON l.id_shop = s.id_shop
				WHERE 1 '.((!is_null($id_megamenu)) ? ' AND l.id_megamenu = "'.(int)$id_megamenu.'"' : '').'
				AND l.id_shop IN (0, '.(int)$id_shop.')';

		return Db::getInstance()->executeS($sql);
	}

	public static function get($id_megamenu, $id_lang, $id_shop)
	{
		return self::gets($id_lang, $id_megamenu, $id_shop);
	}

	public static function getLinkLang($id_megamenu, $id_shop)
	{
		$ret = Db::getInstance()->executeS('
			SELECT l.id_megamenu, l.is_drop, l.new_window, ll.link, ll.label, ll.customhtml, ll.id_lang
			FROM '._DB_PREFIX_.'snsmegamenu l
			LEFT JOIN '._DB_PREFIX_.'snsmegamenu_lang ll ON (l.id_megamenu = ll.id_megamenu AND ll.id_shop='.(int)$id_shop.')
			WHERE 1
			'.((!is_null($id_megamenu)) ? ' AND l.id_megamenu = "'.(int)$id_megamenu.'"' : '').'
			AND l.id_shop IN (0, '.(int)$id_shop.')
		');

		$link = array();
		$label = array();
		$customhtml = array();
		$is_drop = false;
		$new_window = false;

		foreach ($ret as $line) {
			$link[$line['id_lang']] = Tools::safeOutput($line['link']);
			$label[$line['id_lang']] = Tools::safeOutput($line['label']);
			$customhtml[$line['id_lang']] = $line['customhtml'];
			$is_drop = (bool)$line['is_drop'];
			$new_window = (bool)$line['new_window'];
		}

		return array('link' => $link, 'label' => $label, 'customhtml' => $customhtml, 'is_drop' => $is_drop, 'new_window' => $new_window);
	}

	public static function add($link, $label, $customhtml, $isDrop, $newWindow = 0, $id_shop) {
		if(!is_array($label))
			return false;
		if(!is_array($link))
			return false;
		if(!is_array($customhtml))
			return false;
		Db::getInstance()->insert(
			'snsmegamenu',
			array(
				'new_window'=>(int)$newWindow,
				'is_drop'=>(int)$isDrop,
				'id_shop' => (int)$id_shop
			)
		);
		$id_megamenu = Db::getInstance()->Insert_ID();

		$result = true;

		foreach ($label as $id_lang=>$label)
		$result &= Db::getInstance()->insert(
			'snsmegamenu_lang',
			array(
				'id_megamenu'=>(int)$id_megamenu,
				'id_lang'=>(int)$id_lang,
				'id_shop'=>(int)$id_shop,
				'label'=>pSQL($label),
				'link'=>pSQL($link[$id_lang]),
				'customhtml'=>pSQL($customhtml[$id_lang], true)
			)
		);

		return $result;
	}

	public static function update($link, $labels, $customhtml, $is_drop = 0, $newWindow = 0, $id_shop, $id_link)
	{
		if(!is_array($labels))
			return false;
		if(!is_array($link))
			return false;
		if(!is_array($customhtml))
			return false;

		Db::getInstance()->update(
			'snsmegamenu',
			array(
				'is_drop'=>(int)$is_drop,
				'new_window'=>(int)$newWindow,
				'id_shop' => (int)$id_shop
			),
			'id_megamenu = '.(int)$id_link
		);

		foreach ($labels as $id_lang => $label)
			Db::getInstance()->update(
				'snsmegamenu_lang',
				array(
					'id_shop'=>(int)$id_shop,
					'label'=>pSQL($label),
					'link'=>pSQL($link[$id_lang]),
					'customhtml'=>pSQL($customhtml[$id_lang], true)
				),
				'id_megamenu = '.(int)$id_link.' AND id_lang = '.(int)$id_lang
			);
	}


	public static function remove($id_megamenu, $id_shop)
	{
		$result = true;
		$result &= Db::getInstance()->delete('snsmegamenu', 'id_megamenu = '.(int)$id_megamenu.' AND id_shop = '.(int)$id_shop);
		$result &= Db::getInstance()->delete('snsmegamenu_lang', 'id_megamenu = '.(int)$id_megamenu);
		
		return $result;
	}

}
