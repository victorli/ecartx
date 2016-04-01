<?php
/*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class CategorySizeChart extends ObjectModel
{
    public $id;
	public $id_category;
	//public $url;
	public $image;
	public $active;
	public $position;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'category_sizechart',
		'primary' => 'id_category_sizechart',
		'multilang' => true,
		'fields' => array(
			'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'id_category' =>    array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			//'url' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
			'image' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
		)
	);

	public	function __construct($id_sizechart = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_sizechart, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'categorysizechart_shop` (`id_shop`, `id_category_sizechart`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}

	public function delete()
	{
		$res = true;

		$images = $this->image;
		foreach ($images as $image)
		{
			if (preg_match('/sample/', $image) === 0)
				if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
					$res &= @unlink(dirname(__FILE__).'/images/'.$image);
		}

		$res &= $this->reOrderPositions();

		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'categorysizechart_shop`
			WHERE `id_category_sizechart` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}

	public function reOrderPositions()
	{
		$id_sizechart = $this->id;
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(csc.`position`) as position
			FROM `'._DB_PREFIX_.'category_sizechart` csc, `'._DB_PREFIX_.'categorysizechart_shop` cs
			WHERE csc.`id_category_sizechart` = cs.`id_category_sizechart` AND cs.`id_shop` = '.(int)$id_shop
		);

		if ((int)$max == (int)$id_sizechart)
			return true;

		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT csc.`position` as position, csc.`id_category_sizechart` as id_sizechart
			FROM `'._DB_PREFIX_.'category_sizechart` csc
			LEFT JOIN `'._DB_PREFIX_.'categorysizechart_shop` cs ON (csc.`id_category_sizechart` = cs.`id_category_sizechart`)
			WHERE cs.`id_shop` = '.(int)$id_shop.' AND csc.`position` > '.(int)$this->position
		);

		foreach ($rows as $row)
		{
			$current_sizechart = new CategorySizeChart($row['id_sizechart']);
			--$current_sizechart->position;
			$current_sizechart->update();
			unset($current_sizechart);
		}
		return true;
	}

}
